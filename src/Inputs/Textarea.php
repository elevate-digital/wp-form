<?php

namespace Elevatedigital\Form\Inputs;

class Textarea extends Input
{

    protected $type = 'textarea';

    public function __construct($props)
    {
        Parent::__construct($props);
    }

    protected function getAttributesToReplace()
    {
        return array_merge([
            'placeholder' => $this->getAttribute('placeholder'),
        ], parent::getAttributesToReplace());
    }
}
