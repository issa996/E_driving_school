<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driving_school;
use App\Models\Appointment_test;
use App\Models\Driving_student;

class AppointmentController extends Controller
{
    public function add_appointment(Request $request){
        $data = $request->all();
        $school = Driving_school::find($request->driving_school_id);
        //return $school;
        $appointment = Appointment_test::create([
            'time' => $data['time'],
            'date' => $data['date'],
            'driving_school_id' => $data['driving_school_id']
        ]);
        $school->appointments()->save($appointment);
        /*Appointment_test::create([
            'time' => $data['time'],
            'date' => $data['date'],
            'driving_school_id' => $data['school_id']
        ]);*/
        return response()->json([
            'message' => 'the appointment has been added'

        ]);



    }
    public function update_appointment(Request $request,$appointmentid)
    {
        $appointment = Appointment_test::find($appointmentid);
        //return $appointment;
        $data = $request->all();
        $appointment->update([
            'time' => $data['time'],
            'date' => $data['date']
        ]);
        return response()->json([
            'message' => 'the appointment has been updated'
        ]);

    }
    public function delete_appointment($appointmentid){
        $appointment = Appointment_test::find($appointmentid);
        $appointment->delete();
        return response()->json([
            'message' => 'the appointment has been deleted'
        ]);

    }
    public function show_appointments($schoolid){
        $school = Driving_school::find($schoolid);
        return response()->json([ 'appointments' => $school->appointments]);

    }
}
