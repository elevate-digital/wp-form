<?php

namespace Elevatedigital\WpForm\Inputs;

class Input
{

    /**
     * @var string
     */
    protected $name = '';
    /**
     * @var string
     */
    protected $value = '';
    /**
     * @var string
     */
    protected $class = '';
    /**
     * @var string
     */
    protected $placeholder = '';

    /**
     * Input constructor.
     *
     * @param array $props
     */
    public function __construct(array $props)
    {
        $this->setAttribute('name', $props['name']);
        $this->setAttribute('value', $props['value'] ?? false);
        $this->setAttribute('class', $props['class'] ?? false);
        $this->setAttribute('placeholder', $props['placeholder'] ?? false);
    }

    /**
     * @param string $type
     * @param array $props
     *
     * @return mixed
     */
    public static function create(string $type, array $props)
    {
        $class = __NAMESPACE__ . '\\' . ucfirst($type);

        return new $class($props);
    }

    /**
     * @param $attribute
     * @param $value
     */
    protected function setAttribute($attribute, $value)
    {
        $this->$attribute = $value;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        $templateFromTheme = locate_template(["elevate/form/inputs/{$this->type}.php"]);
        $templatePath      = ($templateFromTheme) ? $templateFromTheme : __DIR__ . "/templates/{$this->type}.php";
        $html              = file_get_contents($templatePath);

        return $html;
    }

    /**
     * @param $attribute
     *
     * @return mixed
     */
    protected function getAttribute($attribute)
    {
        return $this->$attribute;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $html = $this->getTemplate();

        foreach ($this->getAttributesToReplace() as $attributeName => $variable) {
            $html = str_replace('{' . $attributeName . '}', $variable, $html);
        }

        return $html;
    }

    protected function getAttributesToReplace()
    {
        return [
            'name'  => $this->getAttribute('name'),
            'value' => $this->getAttribute('value'),
            'class' => $this->getAttribute('class'),
        ];
    }
}
