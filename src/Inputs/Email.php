<?php

namespace Elevatedigital\WpForm\Inputs;

class Email extends Input
{
    protected $type = 'email';

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
