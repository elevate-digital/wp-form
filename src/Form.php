<?php

namespace Elevatedigital\WpForm;

use Elevatedigital\WpForm\Inputs\Input;

class Form
{

    public $name = '';
    public $fields = [];
    public $arguments = [];

    public function __construct(string $name, array $fields, array $arguments)
    {
        $this->name      = $name;
        $this->fields    = $fields;
        $this->arguments = $arguments;
    }

    public static function register($name, $fields, $arguments)
    {
        FormFactory::instance()->register(new Form($name, $fields, $arguments));
    }

    public static function findOrFail($name)
    {
        return FormFactory::instance()->getForm($name);
    }

    public static function open(string $name = 'contact_form')
    {
        $url = esc_url(admin_url('admin-post.php'));

        $html = "<form action=\"{$url}\" method=\"POST\">";
        $html .= wp_nonce_field();
        $html .= "<input type=\"hidden\" name=\"action\" value=\"elevate_{$name}\">";

        return $html;
    }

    public static function close()
    {
        return "</form>";
    }

    public static function input($type, $props)
    {
        return Input::create($type, $props);
    }

    public static function getErrors($name)
    {
        if ( ! $errors = $_GET['errors'] ?? false) {
            return false;
        }

        $errors = $_GET['errors'];

        if (isset($errors[ $name ])) {
            return $errors[ $name ];
        }
    }

    public function getRedirect()
    {
        return $this->arguments['redirect'];
    }
}
