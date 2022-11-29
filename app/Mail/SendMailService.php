<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Sichikawa\LaravelSendgridDriver\SendGrid;

class SendMailService extends Mailable
{
    public $details;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }
    use SendGrid;
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template_id='d-498370249eb94a5488929e0b55a29c7a';
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom('arnavb@mindfiresolutions.com'); $email->setSubject("Test");
        $email->addTo($this->details->email);
        $email->addDynamicTemplateDatas( [ "verification_link" => env("ACCOUNT_APP_URL")."verifyEmail/".$this->details->user."/".$this->details->otp] );
        $email->setTemplateId($template_id);
        $sendgrid=new \SendGrid(env("MAIL_PASSWORD"));
        try {
            $response=$sendgrid->send($email);
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print_r('mail Send');
        } catch (Exception $e) {
            echo 'Caught exception';
            $e->getMessage() . "\n";
        }
        return $this->subject("Test")->html("Test");;
    }
}
