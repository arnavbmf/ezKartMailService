<?php

namespace App\Listeners;

use App\Event\ConsumeRabbitMqMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Sichikawa\LaravelSendgridDriver\SendGrid;
use App\Models\MailLogs;

class SendEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Event\ConsumeRabbitMqMessage  $event
     * @return void
     */
    public function handle(ConsumeRabbitMqMessage $event)
    { 
        $messageBody = $event->details;
        $t=time();
        $time = date("Y-m-d h:i:s a",$t);
        $template_id='d-498370249eb94a5488929e0b55a29c7a';
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom('arnavb@mindfiresolutions.com'); $email->setSubject("Test");
        $email->addTo($messageBody->email);
        $email->addDynamicTemplateDatas( [ "verification_link" => env("ACCOUNT_APP_URL")."verifyEmail/".$messageBody->user."/".$messageBody->otp] );
        $email->setTemplateId($template_id);
        $sendgrid=new \SendGrid(env("MAIL_PASSWORD"));
        try {
            $response=$sendgrid->send($email);
            if($response->statusCode() == 202) {
                $mailLog = new MailLogs();
                $mailLog->user_id = $messageBody->user;
                $mailLog->to_emailId = $messageBody->email;
                $mailLog->from_emailId = env("MAIL_FROM_ADDRESS");
                $mailLog->subject = "qekdqf";
                $mailLog->mail_body = "";
                $mailLog->created_at = $time;
                $mailLog->updated_at = $time;
                $mailLog->save();
                print $response->statusCode() . "\n";
                print_r('Mail Sent Successfully');
            }  
            else {
                print_r('Mail Sending Failed');
            }          
            
        } catch (Exception $e) {
            echo 'Caught exception';
            $e->getMessage() . "\n";
        }
    }
}
