<?php

namespace Elevatedigital\Form\Inputs;

class Text extends Input
{

    protected $type = 'text';

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
