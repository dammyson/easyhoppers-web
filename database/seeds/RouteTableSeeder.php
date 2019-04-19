<?php

use App\Route;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RouteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $route = new Route();
        $route->departure_port = 'LOS';
        $route->arrival_port = 'ABV';
        $route->code = 'LOS-ABV';
        $route->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $route->updated_at = Carbon::now()->format('Y-m-d H:i:s');
        $route->save();   
        
        $route = new Route();
        $route->departure_port = 'ABV';
        $route->arrival_port = 'LOS';
        $route->code = 'ABV-LOS';
        $route->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $route->updated_at = Carbon::now()->format('Y-m-d H:i:s');
        $route->save();
         
        $route = new Route();
        $route->departure_port = 'LOS';
        $route->arrival_port = 'PHC';
        $route->code = 'LOS-PHC';
        $route->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $route->updated_at = Carbon::now()->format('Y-m-d H:i:s');
        $route->save(); 
        
        $route = new Route();
        $route->departure_port = 'ABV';
        $route->arrival_port = 'PHC';
        $route->code = 'ABV-PHC';
        $route->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $route->updated_at = Carbon::now()->format('Y-m-d H:i:s');
        $route->save(); 

        $route = new Route();
        $route->departure_port = 'PHC';
        $route->arrival_port = 'ABV';
        $route->code = 'PHC-ABV';
        $route->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $route->updated_at = Carbon::now()->format('Y-m-d H:i:s');
        $route->save(); 

        $route = new Route();
        $route->departure_port = 'PHC';
        $route->arrival_port = 'LOS';
        $route->code = 'PHC-LOS';
        $route->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $route->updated_at = Carbon::now()->format('Y-m-d H:i:s');
        $route->save(); 
    }
}
