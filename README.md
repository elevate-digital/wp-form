Include the functions wherever you need them:

`use Elevatedigital\WpForm\Form;`

Register a form in your theme's `functions.php`.
The `Form::register()` functions takes a name as it's first parameter and and array of fields as it's second.
A optional third argument is used to set other data like the path to redirect to after a succesfull submission.

### Example form registration
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
        'redirect' => '/thankyou',
    ];

    Form::register('contact', $fields, $args);
    Form::register('form2', ['name' => 'form2'], $args);
});
```

### Validation
Validation is based on wether the field is required. If not the error messages are returned via GET variables.
Like so:
`https://example.com/contact/?errors[name][0]=The name field is required`

The errors can be retrieved via a method.
```
<?php if ( $mail_errors = Form::getErrors('email') ) : ?>
   <?php foreach ( $mail_errors as $error ) : ?>
     <p><?= $error ?></p>
   <?php endforeach; ?>
<?php endif; ?>
```
