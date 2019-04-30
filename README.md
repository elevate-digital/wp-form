### Installation
Add the repo to your `composer.json` file.
```php
"repositories": [
  {
    "url": "https://github.com/elevate-digital/wp-form.git",
    "type": "git"
  }
],
```
test
Then add the repo to your `require` section
```php
  "elevatedigital/wp-form": "~1.0.0-beta"
```
Include the functions wherever you need them:

`use Elevatedigital\WpForm\Form;`

Register a form in your theme's `functions.php`.
The `Form::register()` functions takes a name as it's first parameter and and array of fields as it's second.
A optional third argument is used to set other data like the path to redirect to after a succesfull submission.

### Example form registration
```php
add_action('init', function () {
    $fields = [
        [
            'name'     => 'name',
            'type'     => 'text',
            'validation' => 
              [
                'required' => 'Custom error response message'
              ],
        ],
        [
            'name'     => 'email',
            'type'     => 'text',
            'validation' => 
            [
              'required',
            ],
        ],
        [
            'name'     => 'message',
            'type'     => 'textarea',
            'validation' => 
            [
              'required',
            ],
        ],
    ];

    $args = [
        'redirect' => '/thankyou',
    ];

    Form::register('contact', $fields, $args);
    Form::register('form2', ['name' => 'form2'], $args);
});
```

### Display the form
This package does not provide a way to display the entire form. That way the developer is free to implement a form however he/she wishes.
However there are a few helpers to make things easier.

Below is a code example of how to display a form
```php
<?php echo  Elevatedigital\WpForm\Form::open('contact'); ?>

    <input class="form-control" type="text" name="name" placeholder="Naam">
    <input class="form-control" type="email" name="email" placeholder="E-mailadres">
    <textarea name="message" placeholder="Bericht"></textarea>
    <button type="submit" class="btn btn-primary">Verstuur</button>

<?php echo Elevatedigital\WpForm\Form::close(); ?>
```

### Validation
Validation is based on wether the field is required. If not the error messages are returned via GET variables.
Like so:
`https://example.com/contact/?errors[name][0]=The name field is required`

The errors can be retrieved via a method.
```php
<?php if ( $mail_errors = Form::getErrors('email') ) : ?>
   <?php foreach ( $mail_errors as $error ) : ?>
     <p><?= $error ?></p>
   <?php endforeach; ?>
<?php endif; ?>
```
