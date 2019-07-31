<?php

namespace App\Util;

class HelperClass
{
   
    public static function sendEmail($to_email, $to_name, $from_email, $from_name, $subject, $template, $data ){
     
        \Mail::send($template, $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)->subject("EasyHopper Information");
            $message->from("hello@easyhopper.com", "EasyHopper Mailer");
        });
    }

}