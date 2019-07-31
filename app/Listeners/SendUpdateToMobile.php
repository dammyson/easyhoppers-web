<?php

namespace App\Listeners;

use App\Events\ScheduleChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use WebSocket\Client;

class SendUpdateToMobile
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
     * @param  ScheduleChanged  $event
     * @return void
     */
    public function handle(ScheduleChanged $event)
    {
        //
        $client = new Client('ws://'.env('WS'));
        // connect
        //$client->send(json_encode(['token' => encrypt(\Auth::id())]));
        //$client->send(json_encode(['schedule' => $event->getSchedule()]));

        $schedule = $event->getSchedule();
        if($schedule != null){

            //$schedule_id = $schedule->schedule;
            $users = \DB::select("select id, phone, email, mobile_token, subscription from eazyhopper_db.users  where subscription is not null or subscription != '';");
            if($users){
                foreach ($users as $key => $user) {
                    $str_arr = explode (",", $user->subscription);
                    if($str_arr){
                        $sch_id = $schedule->schedule['id'];
                        if(in_array( $sch_id, $str_arr,TRUE)) {
                            if($schedule->type != 0){
                                $title = self::get_status($schedule->type);
                                self::send_notification($title, $schedule->schedule['schedule_name'] +" "+$title, $user->mobile_token);
                            }
                           
                            #call the function to send the push notification
                        }
                    }
                   
                }
            }
        }
    }

    private function send_notification($title, $message, $mobile_token){


        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\"notification\": {\"title\": $title,\"body\" : $message},\"registration_ids\": [$mobile_token]}",
        CURLOPT_HTTPHEADER => array(
            "authorization: key=AAAAj28AfqA:APA91bGhY6xKxzehRtJuOV0J1mfo04eZEiyF4GbxxcsAY2Guy6Gs_u7WxqJ3NUn22tbKUk8dzdTkDiATv7Bxi4ILzb8NM8_aW8ktY1JMrbWEYnqYf4G60Oe5hHTwiBu6ZBlNqvu_ZbBs",
            "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
        } else {
            return true;
        }
    }


    private function get_status($status){
        if($status == 0)
            return "Scheduled";
      elseif($status == 1)
        return "Flight is on Ground";
      elseif($status == 2)
        return "Flight is Air Borne";
      elseif($status == 3)
        return "Flight Delayed";
      elseif($status == 4)
        return "Flight is Taxiing";
      elseif($status == 5)
        return "Flight is Boarding";
      elseif($status == 6)
        return "Flight Cancelled";
      elseif($status == 7)
        return "Flight has been rescheduled";
    }
}
