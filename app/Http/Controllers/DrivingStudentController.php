<?php

namespace App\Http\Controllers;

use App\Models\Driving_school;
use App\Models\Driving_student;
use App\Models\Appointment_test;
use App\Models\Evaluation;
use App\Models\Test;
use App\Models\Question;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class DrivingStudentController extends Controller
{
    public function register(Request $request){
        $validated = $request->all();
        $student = Driving_student::create([
            'full_name' => $validated['full_name'],
            'mobile_number' =>$validated['mobile_number'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'student_address' => $validated['student_address'],
            'nationality_number' => $validated['nationality_number'],
            'born_date' => $validated['born_date'],
            'gender' => $validated['gender']
        ]);
        $student_token = $student->createToken('student_token',['student'])->plainTextToken;
        return response()->json([
            'student_token' => $student_token,
            'message' => 'student has been registed',
            'status code' => 200
        ]);

        

    }
    public function login(Request $request){
        $validated = $request->all();
        $student = Driving_student::where('email',$validated['email'])->first();
        if($student && Hash::check($validated['password'],$student->password)){
            $student_token = $student->createToken('student_token',['student'])->plainTextToken;
        return response()->json([
            'student_token' => $student_token,
            'message' => 'student has been logged in',
            'password' => $validated['password'],
            'status code' => 200
           
            ]);
        }
        else{
            return response()->json([
                'message' => ' email or password is not correct',
                'status code' => 401 
        ]);
        }

    }
    public function logout(Request $request){
        $request->user()->currentAccessToken('student')->delete();
        return response()->json([
            'message' => 'student has been logged out'
        ]);

    }
    public function reset_password(Request $request){
        $validated = $request->all();
        $user = Driving_student::find($request->student_id);
        
        if(!Hash::check($validated['old_password'],$user['password'])){
            return response()->json([
                'message' => 'the old password is not correct'
            ]);

        }
        else{
            Driving_student::where('id',$user->id)->update([
                'password' => bcrypt($validated['new_password'])
            ]);
            return response()->json([
                'message' => 'password has been changed',
                'status code' => 200
            ]);
        }
    }
    public function information_update(Driving_student $student,Request $request){
        $validated = $request->all();
        /*Validator::make($validated, [
            'email' => [
                Rule::unique('driving_students')->ignore($student->id),
            ],
        ]);*/
        $student = Driving_student::find($validated['student_id']);
        $student->update([
            'full_name' => $validated['full_name'],
            'mobile_number' =>$validated['mobile_number'],
            'email' => $validated['email'],
            'student_address' => $validated['student_address']

        ]);
        return response()->json([
            'message' => 'the driving student informations has been updated',
            'status code' => 200 


        ]);
    }
    public function registerwithschool( $school_id,Request $request){
        $school = Driving_school::find($school_id);
        $student = Driving_student::find($request->student_id);
        if($student->is_registed_with_school != 0){
            $message = 'you  already have been registed ';
        }
        else{
        $student->update(['is_registed_with_school' => 1]);
        $school->driving_students()->save($student);
        $student->tests()->attach($school->tests);
        $message = 'you are registed in '. $school->name. 'Congrats';}
        return response()->json([
            'message' => $message,
            'status code' => 200 


        ]);


    }
    public function show_tests($studentid){
            $t = array();
            $tests = Driving_student::find($studentid)->tests;
            //$tests->where("pivot['test_id']",1)->first();
            foreach($tests as $test){
                if($test->pivot->is_passed == 0){
                    array_push($t,$test);
                }
            }
               
                return response()->json([
                    'tests' => $t
                ]);
            
            

            if(empty($tests)){
                return response()->json([
                    'message' => 'there is not any tests until now'
                ]);
            }
            /*return response()->json([
                'tests' => $tests

            ]);*/
        
        

    }
    public function show_test($testid){
        $questions = Question::where('test_id',$testid)->with('answers')->get();
        return response()->json([
            'questions' => $questions
        ]);
       
        


    }
    /*public function apply_testt($testid,Request $request){
        //$request = $request->all();
        $student = Driving_student::find($request->student_id)->tests->find($testid)->update('pivot->is_passed',1);


    }*/
    public function apply_test(Request $request){
        if($request->is_passed == 0){
            return response()->json([
                'message' => 'you do not pass the test'


            ]);
        }
        if($request->is_passed == 1){
        $student =Driving_student::find($request->student_id);//->find($request->test_id)->update(['pivot->is_passed',1]);
        $test = $student->tests;
        $test->find($request->test_id)->pivot->update(['is_passed' =>1]);
        $student->update([
            'is_passed' => 1
        ]);
        return response()->json([
            'message' => ' you pass the test'

        ]);
    }}

    public function show_schools(){
        $schools = Driving_school::all()->gat();
        return $schools;

    }
    public function booking_with_appointment($appointmentid,Request $request){
        $request = $request->all();
        $student = Driving_student::find($request['student_id']);//->update(['appointment_test_id' => $appointmentid]);
        $appointment = Appointment_test::find($appointmentid);
        if($student->is_passed == 0){
            return response()->json([
                'message' => 'you are not passed all tests'
            ]);
        }
        else{
        //$appointment->students()->save($student);
        //return $student;
        $student->update([
            'appointment_test_id' => $appointment->id

        ]);
        $appointment->update([
            'driving_student_id' => $student->id

        ]);
        return response()->json([
            'message' => 'you are booking with appointment test'
        ]);
    }



    }
    public function unbooking_with_appointment($student_id,Request $request){
        $student = Driving_student::find($student_id);
         Appointment_test::find($request->appointment_id)->update([
            'driving_student_id' => null

        ]);
        //return $student;
        $student->update([
            'appointment_test_id' => null

        ]);
        /*$appointment->update([
            'driving_student_id' => null

        ]);*/
        return response()->json([
            'message' => 'you are unbooking with appointment test'
        ]);


    }
    public function show_appointments($student_id){
        $student = Driving_student::find($student_id);
        $appointments = Driving_school::find($student->driving_school_id)->appointments->where('driving_student_id', null);
       // $appointment = Driving_student::find($student_id)->appointment_test;
        return response()->json([
            'appointments' => $appointments
        ]);


    }
    public function show_appointment($student_id){
        $appointment = Appointment_test::where('driving_student_id',$student_id)->get();
        return response()->json([
            'appointment' => $appointment
        ]);
    }
    public function evaluate(Request $request){
        //$student = Driving_student::find($studentid);
        $data = $request->all();
        $evaluation = Evaluation::create([
            'value' => $data['value'],
            'driving_student_id' => $data['driving_student_id'],
            'driving_school_id' => $data['driving_school_id']

        ]);
        Driving_school::find($data['driving_school_id'])->evaluations()->save($evaluation);
        return response()->json([
            'message' => 'the school has been evaluated'
        ]);

    }
    /*public function check_if_student_passed_all_tests($student_id){
        $tests = Driving_student::find($student_id)->tests;
        //return $tests->pi;
        $b = true;
        /*foreach($tests as $test){
            if($test->pivot->is_passed ==1){
                $b = false;
                break;
            }
        }
        return $b;*/
        /*while($tests->pivot->is_passed){

        }
        

    }*/

}