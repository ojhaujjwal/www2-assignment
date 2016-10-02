<?php

namespace App\InputFilter;

use Zend\InputFilter\InputFilter;

class ReviewInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add([
            'name' => 'id',
            'required' => false,
            'filters' => [
                ['name' => 'Int']
            ]
        ]);

        $this->add([
            'name' => 'review',
            'required' => true,
        ]);

        $this->add([
            'name' => 'reviewerName',
            'required' => true,
        ]);

        $this->add([
            'name' => 'rating',
            'required' => true,
            'validators' => [
                ['name' => 'Int'],
                [
                    'name' => 'LessThan',
                    'options' => [
                        'max' => 5,
                        'inclusive' => true
                    ]
                ]
            ]
        ]);
    }
}
