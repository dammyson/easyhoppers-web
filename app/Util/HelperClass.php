<?php

namespace App\Util;

class HelperClass
{
    public function sendEmail($to_email, $to_name, $from_email, $from_name, $subject, $template, $data ){


        \Mail::send($template, $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)->subject($subject);
            $message->from($from_email, $from_name);
        });
    }

}