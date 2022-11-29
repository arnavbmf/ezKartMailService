<?php

//declare(strict_types=1);

namespace App\Console\Commands;

use App\Exceptions\InvalidAMQPMessageException;
use App\Jobs\IngestDataJob;
use Bschmitt\Amqp\Consumer;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use Bschmitt\Amqp\Facades\Amqp;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailService;
use App\Models\MailLogs;
use Sichikawa\LaravelSendgridDriver\SendGrid;

class ConsumeCommand extends Command
{
    use DispatchesJobs;
    use SendGrid;

    protected $signature = 'rabbitmq:consume';

    protected $description = 'Runs a AMQP consumer that defers work to the Laravel queue worker';

    public function handle(Amqp $consumer, LoggerInterface $logger)
    {
        // $logger->info('Listening for messages...');
        $messageBody = [];
        Amqp::consume('ezKartOtpVerification', function ($message, $resolver) {

            $messageBody = json_decode($message->body);
            var_dump($messageBody);
           
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
                print $response->statusCode() . "\n";
                print_r($response->headers());
                print_r('mail Send');
            } catch (Exception $e) {
                echo 'Caught exception';
                $e->getMessage() . "\n";
            }
            $mailLog = new MailLogs();
            $mailLog->user_id = $messageBody->user;
            $mailLog->to_emailId = $messageBody->email;
            $mailLog->from_emailId = env("MAIL_FROM_ADDRESS");
            $mailLog->subject = "qekdqf";
            $mailLog->mail_body = "qkhfk";
            $mailLog->created_at = $time;
            $mailLog->updated_at = $time;
            $mailLog->save();
        });

        // $logger->info('Consumer exited.');

        $resolver->acknowledge($message);
        return true;
    }
}