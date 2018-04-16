<?php

namespace Elevatedigital\WpForm;


class FormRequest
{

    public $request;
    protected $form;

    public function handle()
    {
        $this->request = $_POST;

        $this->checkPostLimitExceeded();

        wp_verify_nonce($_POST['_wpnonce'], $_POST['action']);

        $this->form = Form::findOrFail($this->getNameFromRequest());

        // Todo: find a way to redirect back with invalid form values
        // Todo: check required form values
        $sanitizedValues = $this->validateFields($this->form->fields);

        $this->createFormSubmission($sanitizedValues);

        wp_redirect($this->form->getRedirect());
        exit;
    }

    protected function getNameFromRequest()
    {
        return str_replace('elevate_', '', sanitize_text_field($_POST['action']));
    }

    public function validateFields(array $fields)
    {
        $values = array_map(function ($field) {
            $sanitizeMethod = $this->get_field_sanitize_type($field['type']);

            return call_user_func_array($sanitizeMethod, [$this->request[ $field['name'] ]]);
        }, $fields);

        $keys = array_map(function ($field) {
            return $field['name'];
        }, $fields);

        $values = array_combine($keys, $values);

        return $values;
    }

    function get_field_sanitize_type(string $type)
    {
        switch ($type) {
            case 'text':
                return 'sanitize_text_field';
            case 'email':
                return 'sanitize_email';
            case 'textarea':
                return 'sanitize_textarea_field';
            case 'number':
                return 'absint';
            default:
                return 'sanitize_text_field';
        }
    }

    protected function createFormSubmission(array $meta)
    {
        $post = [
            'post_title'  => ucfirst($this->form->name),
            'post_status' => 'publish',
            'post_type'   => 'form_submit',
            'meta_input'  => $meta,
        ];

        wp_insert_post($post);
    }

    public function checkPostLimitExceeded(): void
    {
        $ip            = $_SERVER['REMOTE_ADDR'];
        $transientKey  = "elevate_form_request_{$ip}";
        $lastSubmitted = get_transient($transientKey);

        if ($lastSubmitted) {
            die('Post rate exceeded. Please wait 3 minutes before posting again');
        } else {
            set_transient($transientKey, time(), 60 * 3); // 3 minutes
        }
    }
}
