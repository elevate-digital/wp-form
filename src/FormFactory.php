<?php

namespace Elevatedigital\WpForm;

class FormFactory
{

    private $textDomain = 'elevatedigital-form';
    private $forms = [];

    private function __construct()
    {
        $this->registerPostType();
        add_action('add_meta_boxes_form_submit', [$this, 'initMetaboxes']);
    }

    private function registerPostType()
    {
        $labels = [
            'name'               => _x('Form submits', 'post type general name', $this->textDomain),
            'singular_name'      => _x('Form submit', 'post type singular name', $this->textDomain),
            'menu_name'          => _x('Form submits', 'admin menu', $this->textDomain),
            'name_admin_bar'     => _x('Form submit', 'add new on admin bar', $this->textDomain),
            'add_new'            => _x('Add New', 'submit', $this->textDomain),
            'add_new_item'       => __('Add New Form submit', $this->textDomain),
            'new_item'           => __('New Submit', $this->textDomain),
            'edit_item'          => __('Edit Submit', $this->textDomain),
            'view_item'          => __('View Submit', $this->textDomain),
            'all_items'          => __('All Form submits', $this->textDomain),
            'search_items'       => __('Search Form submits', $this->textDomain),
            'parent_item_colon'  => __('Parent Form submits:', $this->textDomain),
            'not_found'          => __('No submits found.', $this->textDomain),
            'not_found_in_trash' => __('No submits found in Trash.', $this->textDomain),
        ];

        $args = [
            'labels'             => $labels,
            'description'        => __('Form submits.', $this->textDomain),
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'capability_type'    => 'page',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => ['title'],
        ];

        register_post_type('form_submit', $args);
    }

    public static function instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new self();
        }

        return $inst;
    }

    public function initMetaboxes()
    {
        add_meta_box(
            'elevate_form_metabox',
            'Form values',
            [$this, 'metaboxHTML'],
            'form_submit',
            'normal',
            'high'
        );
    }

    public function metaboxHTML($formSubmit, $metabox)
    {
        $form = Form::findOrFail(get_post_meta($formSubmit->ID, 'form', true));

        foreach ($form->fields as $field) {
            $name = ucfirst($field['name']);
            $value = get_post_meta($formSubmit->ID, $field['name'], true);
            echo "<div class='elevate-form-meta'><p><strong>{$name}: </strong>{$value}</p></div>";
        }
    }

    public function register(Form $form)
    {
        $this->forms[] = $form;

        $formName = "elevate_{$form->name}";

        $formRequest = new FormRequest();

        add_action("admin_post_{$formName}", [$formRequest, 'handle']);
        add_action("admin_post_nopriv_{$formName}", [$formRequest, 'handle']);
    }

    public function getForm(string $name)
    {
        return array_first(array_filter($this->forms, function ($form) use ($name) {
            return $form->name === $name;
        }));
    }

    public function getForms()
    {
        return $this->forms;
    }
}
