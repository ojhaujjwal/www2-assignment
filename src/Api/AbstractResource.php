<?php
namespace Api;

use Doctrine\Common\Collections\Collection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Json\Json;
use Zend\Paginator\Paginator;
use ZF\ApiProblem\ApiProblem;
use Doctrine\ORM\EntityManagerInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Hydrator\HydrationInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class AbstractResource
{
    const ENTITY_CLASS = '';

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var InputFilterInterface
     */
    protected $inputFilter;

    /**
     * @var HydrationInterface
     */
    protected $hydrator;

    /**
     * Constructor
     *
     * @param EntityManagerInterface $entityManager
     * @param InputFilterInterface   $inputFilter
     */
    public function __construct(EntityManagerInterface $entityManager, InputFilterInterface $inputFilter)
    {
        $this->entityManager = $entityManager;
        $this->inputFilter = $inputFilter;
        $this->hydrator = new DoctrineHydrator($entityManager);
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $method      = $request->getMethod();
        $attributes  = $request->getAttributes();
        $params      = $request->getQueryParams();

        if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
            $data = $this->getRequestData($request);
            if ($data instanceof ApiProblem) {
                return $this->prepareResponse($data);
            }
        }


        if ($method === 'GET') {
            $result = null;
            if (isset($attributes['id'])) {
                $result = $this->dispatch('fetch', [$attributes['id'], $request]);
            } else {
                $result = $this->dispatch('fetchAll', [$params, $request]);
            }

            return $this->prepareResponse($result);
        } elseif ($method === 'POST') {
            return $this->handlePost($data, $request);
        } elseif ($method === 'PUT') {
            if (isset($attributes['id'])) {
                return $this->handleUpdate($attributes['id'], $data, $request);
            }
        } elseif ($method === 'PATCH') {
            if (isset($attributes['id'])) {
                return $this->handleUpdate($attributes['id'], $data, $request);
            }
        } elseif ($method === 'DELETE') {
            if (isset($attributes['id'])) {
                return $this->handleDelete($attributes['id'], $request);
            }
        } else {
            return $this->prepareResponse((new ApiProblem(405, 'Method not allowed!'))->toArray());
        }

        return $this->prepareResponse((new ApiProblem(400, 'Bad Request'))->toArray());
    }

    private function handlePost(array $data, ServerRequestInterface $request)
    {
        $this->inputFilter->setData($data);
        if (!$this->inputFilter->isValid()) {
            return new JsonResponse($this->extractErrorMessages($this->inputFilter), 422, [], JSON_PRETTY_PRINT);
        }

        $data = $this->inputFilter->getValues();

        $entityClass = static::ENTITY_CLASS;
        if (!empty($entityClass)) {
            $data = $this->hydrator->hydrate($data, new $entityClass);
        }

        return $this->prepareResponse($this->dispatch('create', [$data, $request]), 201);
    }

    private function handleUpdate($id, array $data, ServerRequestInterface $request)
    {
        $this->inputFilter->setData($data);
        if (!$this->inputFilter->isValid()) {
            return new JsonResponse($this->extractErrorMessages($this->inputFilter), 422, [], JSON_PRETTY_PRINT);
        }

        $data = $this->inputFilter->getValues();

        $entityClass = static::ENTITY_CLASS;
        if (empty($entityClass)) {
            return $this->prepareResponse($this->dispatch('update', [$id, $data, $request]));
        }

        $entity = $this->entityManager->getRepository($entityClass)->find($id);
        if (!$entity) {
            return new JsonResponse(['message' => 'Not found'], 404, [], JSON_PRETTY_PRINT);
        }
        $entity = $this->hydrator->hydrate($data, $entity);

        return $this->prepareResponse($this->dispatch('update', [$entity, $request]));
    }

    private function handleDelete($id, ServerRequestInterface $request)
    {
        $entityClass = static::ENTITY_CLASS;
        if (empty($entityClass)) {
            return $this->prepareResponse($this->dispatch('delete', [$id, $request]));
        }

        $entity = $this->entityManager->getRepository($entityClass)->find($id);
        if (!$entity) {
            return new JsonResponse(['message' => 'Not found'], 404, [], JSON_PRETTY_PRINT);
        }

        return $this->prepareResponse($this->dispatch('delete', [$entity, $request]));
    }

    protected function prepareResponseData($result)
    {
        $entityClass = static::ENTITY_CLASS;

        if(is_object($result) && $result instanceof $entityClass) {
            return $this->hydrator->extract($result);
        }  elseif ($result instanceof Paginator) {
            return (array )$result->getCurrentItems();
        }  elseif ($result instanceof Collection) {
            return $result->map(function ($element) {
                return $this->prepareResponseData($element);
            })->toArray();
        } elseif (is_object($result) && method_exists($result, 'toArray')) {
            return $result->toArray();
        } elseif ($result instanceof \Traversable) {
            return (array) $result;
        } elseif (is_array($result)) {
            return $result;
        }

        return new ApiProblem(502, 'Bad gateway');
    }

    protected function prepareResponse($result, $defaultStatus = 200)
    {
        if ($result instanceof ApiProblem) {
            return new JsonResponse($result->toArray(), $result->status, [], JSON_PRETTY_PRINT);
        } elseif ($result instanceof JsonResponse) {
            return $result;
        }

        $result = $this->prepareResponseData($result);
        if ($result instanceof ApiProblem) {
            return $this->prepareResponse($result);
        }

        return new JsonResponse($result, $defaultStatus, [], JSON_PRETTY_PRINT);
    }

    private function dispatch($method, $arguments)
    {
        if (!method_exists($this, $method)) {
            return new ApiProblem(405, 'Method not allowed!');
        }

        return call_user_func_array([$this, $method], $arguments);
    }

    protected function getRequestData(ServerRequestInterface $request)
    {
        $body = $request->getParsedBody();

        if (!empty($body)) {
            return $body;
        }

        return $this->parseRequestData(
            $request->getBody()->getContents(),
            $request->getHeaderLine('content-type')
        );
    }

    private function parseRequestData($input, $contentType)
    {
        $contentTypeParts = preg_split('/\s*[;,]\s*/', $contentType);
        $parser           = $this->returnParser($contentTypeParts[0]);

        return $parser($input);
    }

    private function returnParser($contentType)
    {
        if ($contentType === 'application/x-www-form-urlencoded') {
            return function ($input) {
                parse_str($input, $data);

                return $data;
            };
        } elseif ($contentType === 'application/json') {
            return function ($input) {
                $jsonDecoder = new Json();
                try {
                    return $jsonDecoder->decode($input, Json::TYPE_ARRAY);
                } catch (\Exception $e) {
                    return new ApiProblem(400, 'Data Parsing Error.');
                }
            };
        } elseif ($contentType === 'multipart/form-data') {
            return function ($input) {
                return $input;
            };
        }

        return function ($input) {
            return $input;
        };
    }

    /**
     * @param  InputFilterInterface $inputFilter
     * @return array
     */
    protected function extractErrorMessages(InputFilterInterface $inputFilter)
    {
        $errorMessages = $inputFilter->getMessages();
        array_walk($errorMessages, function (&$value, $key) use ($inputFilter) {
            if ($inputFilter->has($key) && $inputFilter->get($key) instanceof InputFilterInterface) {
                $value = $this->extractErrorMessages($inputFilter->get($key));
            } else {
                $value = array_values($value);
            }
        });

        return $errorMessages;
    }
}
