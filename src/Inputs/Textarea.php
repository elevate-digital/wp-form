<?php

namespace Elevatedigital\WpForm\Inputs;

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
