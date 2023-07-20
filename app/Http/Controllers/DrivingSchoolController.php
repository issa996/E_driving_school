<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Driving_school;
use App\Models\Driving_student;
use App\Models\Test;
use App\Models\Question;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DrivingSchoolController extends Controller
{
    public function register(Request $request){
        $validated = $request->all();
        $school = Driving_school::create([
            'name' => $validated['name'],
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'address' => $validated['address'],
            'longitude' => $validated['longitude'],
            'latitude' => $validated['latitude']
        ]);
        $school_token = $school->createToken('school_token', ['school'])->plainTextToken;
        return response()->json([
            'token' => $school_token,
            'message' => 'driving school has been registed',
            
            'status code' => 200

        ]);

    }
    public function login(Request $request){
        $validated = $request->all();
        $school = Driving_school::where('email',$validated['email'])->first();
        if($school && Hash::check($validated['password'], $school->password)){
            $school['pass'] = $validated['password'];
            $school_token = $school->createToken('school_token', ['school'])->plainTextToken;
            return response()->json([
                'school' => $school,
                'token' => $school_token,
                'message' => 'the school has been logged in',
                //'password' => $validated['password'],
                'status code' => 200
            
            ]);
        }
        else{
            return response()->json([
                'message' => 'email or password is not correct',
                'status code' => 401

            ]);

        }


    }
    public function logout(Request $request){
        $request->user()->currentAccessToken('school')->delete();
        return response()->json([
            'message' => $request->user()->name.'school has been logged out'
        ]);

    }
    public function reset_password(Request $request){
        $validated = $request->all();
        $school = Driving_school::find($validated['school_id']);
        if(!Hash::check($validated['old_password'],$school['password'])){
           return response()->json([
            'message' => 'old password is not correct'
           ]);
        }
        else{
            Driving_school::find($school->id)->update([
                'password' => bcrypt($validated['new_password'])
            ]);
            return response()->json([
                'message' => 'password has been changed',
                'status code' => 200
            ]);
        }


    }
    public function information_update(Request $request){
        $validated = $request->all();
        $school = Driving_school::find($validated['school_id']);
       /* Validator::make($validated, [
            'email' => [
                'required',
                Rule::unique('driving_schools')->ignore($school->id),
            ],
        ]);*/
        Driving_school::find($school->id)->update([
            'name' => $validated['name'],
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'],
            'address' => $validated['address']
        ]);
        return response()->json([
            'message' => 'school information has been updated',
            'status code' => 200
            ]);

    }
    public function show_tests($schoolid){
        
        $tests = Test::where('driving_school_id',$schoolid)->get();
        return response()->json([
            'tests' => $tests

        ]);
        
        

    }
    public function show_test($testid){
      $questions = Question::where('test_id',$testid)->with('answers')->get();
    return response()->json(['questions' => $questions]);


    }
    public function index(){
        $schools = Driving_school::all();
        return response()->json([
            'schools' => $schools
        ]);
    }
    public function show_students($schoolid){
        $students = Driving_student::where('driving_school_id',$schoolid)->get();
        return response()->json(['students'=>  $students]);

    }
    public function show_appointments($school_id){
        $appointments = Driving_school::find($school_id)->appointments;
        return response()->json([
            'appointments' => $appointments
        ]);
    }
    public function show_evaluations($schoolid){
        $evaluations = Driving_school::find($schoolid)->evaluations;
        return response()->json([
            'evaluations'  => $evaluations
        ]);


    }
}
