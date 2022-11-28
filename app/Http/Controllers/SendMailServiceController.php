<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailService;
use App\Models\MailLogs;
use Bschmitt\Amqp\Facades\Amqp;


class SendMailServiceController extends Controller
{
    
    public function sendMail(Request $request) {


        try {
            $details = [
                'title' => $request['title'],
                'body' => $request['body']
            ];

            $t=time();
            $time = date("Y-m-d h:i:s a",$t);
            Mail::to($request['to'])->send(new SendMailService($details));
            $mailLog = new MailLogs();
            $mailLog->user_id = $request['user_id'];
            $mailLog->to_emailId = $request['to'];
            $mailLog->from_emailId = env("MAIL_USERNAME");
            $mailLog->subject = $request['title'];
            $mailLog->mail_body = $request['body'];
            $mailLog->created_at = $time;
            $mailLog->updated_at = $time;
            $mailLog->save();
            $response = array(
                "message" => "Mail sent successfully",
                "status" => 200,
            );
            return response()->json($response);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
