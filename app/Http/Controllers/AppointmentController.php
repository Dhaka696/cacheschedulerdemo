<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
class AppointmentController extends Controller
{ 
 
    
    public function getSchedule(Request $request)
    {	
        $value = Cache::get($request->schedule);
        $response = ['appointments' => $value];
        return response($response, 200);
    }

    public function getAppointment(Request $request)
    {	
        $value = Cache::get($request->schedule);
        $value = $value[$request->id];
        $response = ['appointments' => $value];
        return response($response, 200);
       
    }
 

    public function createAppointment(Request $request)
    {  
        if ($request->start_time && $request->end_time && $request->schedule){

            if ($request->start_time >= $request->end_time){
                $response = 'End Time must be greater than Start Time';
    			return response($response, 422);

            }
            if (Cache::has($request->schedule)) {
                $schedulearray = array();
                $value = Cache::get($request->schedule);
             
                if(array_key_exists('id', $value)){
                    $id = 1;
                    $schedulearray[] = $value; 

                    if (($request->start_time >= $value['start_time'] && $request->start_time <= $value['end_time']) || ($request->end_time >= $value['start_time'] && $request->end_time <= $value['end_time'])){
                        $response = 'Cannot book within other appointments.';
                        return response($response, 422);
        
                    }



                } else{
                    $id = count($value);
                    foreach ($value as $key=>$array){
                        $schedulearray[] = $array; 


                        if (($request->start_time >= $array['start_time'] && $request->start_time <= $array['end_time']) || ($request->end_time >= $array['start_time'] && $request->end_time <= $array['end_time'])){
                            $response = 'Cannot book within other appointments.';
                            return response($response, 422);
            
                        }


                    }
                } 
                $value2 = [
                    'id' => $id,
                    'start_time'      => $request->start_time,
                    'end_time'    => $request->end_time
                ]; 
                $schedulearray[] = $value2;  
                usort($schedulearray, function($a, $b) {
                    return $a['start_time'] - $b['start_time'];
                });
                Cache::forget($request->schedule);
                Cache::add($request->schedule, $schedulearray);
            } else{
                Cache::add($request->schedule, [
                    'id' => 0,
                    'start_time'      => $request->start_time,
                    'end_time'    => $request->end_time
                ]);
                
            }

         
            $response = 'Schedule Added';
            return response($response, 200);
        } else{
            $response = 'Requires start_time, end_time, and schedule';
    			return response($response, 422);

        }
    }


    public function deleteSchedule(Request $request)
    {     
        Cache::forget($request->schedule);
    }

    public function deleteAppointment(Request $request)
    {     
        
        
        if (Cache::has($request->schedule)) {
            $schedulearray = array();
            $value = Cache::get($request->schedule);
         
            if(array_key_exists('id', $value)){ 
                if ($value['id'] != $request->id){
                    $schedulearray[] = $value; 
                }
 
            } else{ 
                foreach ($value as $key=>$array){
                    if ($array['id'] != $request->id){
                        $schedulearray[] = $array; 
                    }
                }
            }   
            usort($schedulearray, function($a, $b) {
                return $a['start_time'] - $b['start_time'];
            });
            Cache::forget($request->schedule);
            Cache::add($request->schedule, $schedulearray);
            $response = 'Appointment Deleted';
            return response($response, 422);
        } else{
            $response = 'Schedule does not exist';
            return response($response, 422);
            
        }


    }


     

   
}
