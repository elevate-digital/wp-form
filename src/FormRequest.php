<?php

namespace Elevatedigital\WpForm;

class FormRequest
{

    public $request = [];
    public $errors = [];
    protected $form;

    public function handle()
    {
        $this->request = $_POST;

        $this->checkPostLimitExceeded();

        wp_verify_nonce($_POST['_wpnonce'], $_POST['action']);

        $this->form = Form::findOrFail($this->getNameFromRequest());

        $values = $this->validateFields($this->form->fields);

        if ($this->hasErrors()) {
            $url = esc_url(add_query_arg('errors', $this->errors, $_POST['_wp_http_referer']));
            wp_redirect($url);
            die();
        }

        $this->createFormSubmission($values);

         $email = new Email($this->form, $values);
         $email->send();

        wp_redirect($this->form->getRedirect());
        exit;
    }

    protected function hasErrors()
    {
        return count($this->errors);
    }

    protected function getNameFromRequest()
    {
        return str_replace('elevate_', '', sanitize_text_field($_POST['action']));
    }

    public function validateFields(array $fields)
    {
        $validatedValues = [];

        foreach ($fields as $key => $field) {
            $valueFromRequest = $this->request[ $field['name'] ];
            $sanitizeMethod   = $this->get_field_sanitize_type($field['type']);

            $this->checkRequired($field);

            $validatedValues[ $field['name'] ] = call_user_func_array($sanitizeMethod, [$valueFromRequest]);
        }

        return $validatedValues;
    }

    private function checkRequired($field)
    {
        if ( ! $field['required']) {
            return false;
        }

        if ( ! $this->request[ $field['name'] ]) {
            if ( ! isset($this->errors[ $field['name'] ])) {
                $this->errors[ $field['name'] ] = [];
            }

            $this->errors[ $field['name'] ][] = "The {$field['name']} field is required";
        }
    }

    private function get_field_sanitize_type(string $type)
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
        $meta['form'] = $this->form->name;

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
