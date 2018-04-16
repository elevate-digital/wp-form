```
add_action('init', function () {
    $fields = [
        [
            'name'     => 'name',
            'type'     => 'text',
            'required' => true,
        ],
        [
            'name'     => 'email',
            'type'     => 'text',
            'required' => true,
        ],
        [
            'name'     => 'message',
            'type'     => 'textarea',
            'required' => true,
        ],
    ];

    $args = [
        'redirect' => '/bedankt',
    ];

    Form::register('contact', $fields, $args);
    Form::register('form2', ['name' => 'form2'], $args);
});
```
