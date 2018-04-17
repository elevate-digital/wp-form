<?php
/**
 * Created by PhpStorm.
 * User: ianscheele
 * Date: 17/04/2018
 * Time: 08:36
 */

namespace Elevatedigital\WpForm;


class Email
{

    protected $form;
    protected $values;

    public function __construct(Form $form, array $values)
    {
        $this->form   = $form;
        $this->values = $values;
    }

    public function send()
    {
        $to = $this->form->arguments['mail_to'];

        $headers = [
            'From: ' . get_bloginfo('admin_email'),
        ];

        $subject = sprintf('%s aanvraag', ucfirst($this->form->name));

        $message = 'Hello' . "\r\n\r\n";
        $message .= 'We have received a new form submission on ' . site_url() . ".\r\n\r\n";
        $message .= 'Please login to view this form submission.';

        $sent = wp_mail($to, $subject, $message, $headers);

        if (false === $sent) {
            error_log('Email failed to send for form ' . $this->form->name);
        }
    }
}
