<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Driving_student;

class AdminController extends Controller
{
    public function login (Request $request){
        //$validated = $request->validated();
        $validated = $request->all();
        $admin = Admin::where('username',$validated['username'])->first();
        if($admin && $admin->password==$validated['password'] ){
            $student_token = $admin->createToken('student_token',['admin'])->plainTextToken;
        return response()->json([
            'admin_token' => $student_token,
            'message' => 'admin has been logged in',
            'status code' => 200
           
            ]);
        }
        else{
            return response()->json([
                'message' => ' username or password is not correct',
                'status code' => 401 
        ]);
        }

    }
    public function register(){
        $username = 'admin';
        $password = 'admin1234';
       $admin = Admin::create([
        'username' => 'admin',
        'password' => bcrypt($password)

        ]);
        return $admin;

    }
    public function verify_student($studentid){
        $student = Driving_student::find($studentid);
        
        $student->update(['status' => 1]);

        return response()->json([
            'message' => 'you are verifyed'
        ]);

    }
    public function block_student($studentid){
        Driving_student::find($studentid)->update(['status' => 2]);
        return response()->json([
            'message' => 'you are bloked'
        ]);
    }

}
