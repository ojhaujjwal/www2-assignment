<?php

namespace App\InputFilter;

use Zend\InputFilter\InputFilter;

class TvShowInputFilter extends InputFilter
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
            'name' => 'title',
            'required' => true,
            'filters' => [

            ]
        ]);

        $this->add([
            'name' => 'description',
            'required' => false,
        ]);
    }
}