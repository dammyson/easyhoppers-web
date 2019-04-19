<?php

use App\Airline;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AirlineTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $airline = new Airline();
        $airline->name = 'Air Peace';
        $airline->description = 'Air Peace ';
        $airline->code = 'APK';
        $airline->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $airline->updated_at = Carbon::now()->format('Y-m-d H:i:s');
        $airline->save();   
        
        $airline = new Airline();
        $airline->name = 'Arik Air';
        $airline->description = 'Arik Air';
        $airline->code = 'ARA';
        $airline->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $airline->updated_at = Carbon::now()->format('Y-m-d H:i:s');
        $airline->save();  
         
        $airline = new Airline();
        $airline->name = 'Dana Air';
        $airline->description = 'Dana Air';
        $airline->code = 'DAN';
        $airline->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $airline->updated_at = Carbon::now()->format('Y-m-d H:i:s');
        $airline->save();  
    }
}
