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
use App\Event\ConsumeRabbitMqMessage;

class ConsumeCommand extends Command
{
    use DispatchesJobs;
    use SendGrid;

    protected $signature = 'rabbitmq:consume';

    protected $description = 'Runs a AMQP consumer that defers work to the Laravel queue worker';

    public function handle(Amqp $consumer, LoggerInterface $logger)
    {
        $logger->info('Listening for messages...');
        $messageBody = [];
        Amqp::consume('ezKartOtpVerification', function ($message, $resolver) {

            $messageBody = json_decode($message->body);
            
            var_dump($messageBody);
            event (new ConsumeRabbitMqMessage($messageBody));

            $resolver->acknowledge($message);
        });
        return true;
    }
}