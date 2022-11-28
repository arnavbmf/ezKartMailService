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

class ConsumeCommand extends Command
{
    use DispatchesJobs;

    protected $signature = 'rabbitmq:consume';

    protected $description = 'Runs a AMQP consumer that defers work to the Laravel queue worker';

    public function handle(Amqp $consumer, LoggerInterface $logger)
    {
        $logger->info('Listening for messages...');

        Amqp::consume('ezKartOtpVerification', function ($message, $resolver) {

            var_dump($message->body);

            $resolver->acknowledge($message);

        });

        $logger->info('Consumer exited.');

        return true;
    }

//    private function validateMessage(array $payload)
//    {
//        if (!is_string($payload['filepath'] ?? null)) {
//            throw new InvalidAMQPMessageException('The [filepath] property must be a string.');
//        }
//    }
}