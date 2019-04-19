<?php

use App\Airport;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AirportTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $airport = new Airport();
        $airport->name = 'Murtala Muhammed Airport';
        $airport->description = 'Murtala Muhammed Airport ';
        $airport->code = 'LOS';
        $airport->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $airport->updated_at = Carbon::now()->format('Y-m-d H:i:s');
        $airport->save();   
        
        $airport = new Airport();
        $airport->name = 'Nnamdi Azikiwe International Airport';
        $airport->description = 'Nnamdi Azikiwe International Airport ';
        $airport->code = 'ABV';
        $airport->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $airport->updated_at = Carbon::now()->format('Y-m-d H:i:s');
        $airport->save();  
         
        $airport = new Airport();
        $airport->name = 'Port Harcourt Airport';
        $airport->description = 'Port Harcourt Airport';
        $airport->code = 'PHC';
        $airport->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $airport->updated_at = Carbon::now()->format('Y-m-d H:i:s');
        $airport->save();  
    }
}
