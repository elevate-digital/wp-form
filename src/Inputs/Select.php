<?php

namespace Elevatedigital\Form\Inputs;

class Select extends Input
{

    protected $type = 'select';

    public function __construct($props)
    {
        Parent::__construct($props);
        $this->setAttribute('options', $props['options']);
    }

    protected function getAttributesToReplace()
    {
        return array_merge([
            'placeholder' => $this->getAttribute('placeholder'),
            'options' => $this->getOptions(),
        ], parent::getAttributesToReplace());
    }

    protected function getOptions()
    {
        $options = $this->getAttribute('options');

        $optionsHtml = '';

        foreach ($options as $value => $label) {
            $optionsHtml .= "<option value=\"{$value}\">{$label}</option>";
        }

        return $optionsHtml;
    }
}
