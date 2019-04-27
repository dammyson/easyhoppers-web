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
        $client->send(json_encode(['token' => encrypt(\Auth::id())]));
        $client->send(json_encode(['schedule' => $event->getSchedule()]));
    }
}
