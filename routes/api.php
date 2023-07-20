<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DrivingStudentController;
use App\Http\Controllers\DrivingSchoolController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\LicenseRequirementsController;
use App\Http\Controllers\TrafficRegulationController;
use App\Http\Controllers\AppointmentController;
use App\Models\Driving_student;
use App\Models\License_Requirements;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Route::post('/school/register',[DrivingSchoolController::class,'register']);

//Route::post('hi',[QuestionController::class,'add_question']);
Route::group(['prefix' => 'student'],function(){
    Route::post('/register',[DrivingStudentController::class,'register']);
    Route::post('/login',[DrivingStudentController::class,'login']);
    Route::post('/logout',[DrivingStudentController::class,'logout']);
    Route::post('reset_password',[DrivingStudentController::class,'reset_password']);
    Route::post('update',[DrivingStudentController::class,'information_update']); 
    Route::post('registerwithschool/{school}',[DrivingStudentController::class,'registerwithschool']);
    Route::get('show_tests/{studentid}',[DrivingStudentController::class,'show_tests']);
    Route::get('show_test/{test_id}',[DrivingStudentController::class,'show_test']);
    Route::post('updatequestion/{questionid}',[Question::class,'updatequestion']);
    Route::post('apply/{$testid}',[DrivingStudentController::class,'apply_test']);
    Route::post('apply_test',[DrivingStudentController::class,'apply_test']);
    Route::post('booking_with_appointment/{appointmentid}',[DrivingStudentController::class,'booking_with_appointment']);
    Route::post('evaluate',[DrivingStudentController::class,'evaluate']);
    Route::get('show_appointments/{student_id}',[DrivingStudentController::class,'show_appointments']);
    Route::get('show_appointment/{student_id}',[DrivingStudentController::class,'show_appointment']);
    Route::post('unbooking_with_appointment/{student_id}',[DrivingStudentController::class,'unbooking_with_appointment']);
    Route::get('check/{student_id}',[DrivingStudentController::class,'check_if_student_passed_all_tests']);


});

Route::group(['prefix' => 'school'] ,function(){
    Route::post('/register',[DrivingSchoolController::class,'register']);
    Route::post('/login',[DrivingSchoolController::class,'login']);
    Route::post('/reset_password',[DrivingSchoolController::class,'reset_password']);
    Route::post('update',[DrivingSchoolController::class,'information_update']);
    Route::post('add_question',[QuestionController::class,'add_question']);
    Route::post('add_test',[TestController::class,'store']);
    Route::post('update_test/{testid}',[TestController::class,'update']);
    Route::post('delete_test/{testid}',[TestController::class,'destroy']);
   // Route::post('',[QuestionController::class,'add_question']);
    Route::post('update_question/{questionid}',[QuestionController::class,'update_question']);
    Route::post('delete_question/{questionId}',[QuestionController::class,'delete_question']);
    Route::get('show_tests/{schoolid}',[DrivingSchoolController::class,'show_tests']);
    Route::get('show_test/{test_id}',[DrivingSchoolController::class,'show_test']);
    Route::get('show_students/{schoolid}',[DrivingSchoolController::class,'show_students']);
    Route::post('add_appointment',[AppointmentController::class,'add_appointment']);
    Route::post('update_appointment/{appointmentid}',[AppointmentController::class,'update_appointment']);
    Route::post('delete_appointment/{appointmentid}',[AppointmentController::class,'delete_appointment']);
    Route::get('show_appointments/{studentid}',[AppointmentController::class,'show_appointments']);
    Route::get('show_evaluations/{schoolid}',[DrivingSchoolController::class,'show_evaluations']);
});

Route::group(['prefix' => 'admin'] ,function(){
    Route::post('/register',[AdminController::class,'register']);
    Route::post('/login',[AdminController::class,'login']);
    Route::post('/add_lesson',[LessonController::class,'add_lesson']);
    //Route::get('/show_lessons',[LessonController::class,'show_lessons']);
    Route::post('/update_lesson/{lessonid}',[LessonController::class,'update_lesson']);
    Route::post('/delete_lesson/{lessonid}',[LessonController::class,'delete_lesson']);

    Route::post('/add_requirement',[LicenseRequirementsController::class,'add_requirement']);
    Route::post('/update_requirement/{requirementid}',[LicenseRequirementsController::class,'update_requirement']);
    Route::post('/delete_requirement/{requirementid}',[LicenseRequirementsController::class,'delete_requirement']);
    Route::post('/add_regulation',[TrafficRegulationController::class,'add_regulation']);
    Route::post('/update_regulation/{regulationid}',[TrafficRegulationController::class,'update_regulation']);
    Route::post('/delete_regulation/{regulationid}',[TrafficRegulationController::class,'delete_regulation']);
    Route::post('verifystudent/{studentid}',[AdminController::class,'verify_student']);
    Route::post('blockstudent/{studentid}',[AdminController::class,'block_student']);

});   
/*Route::middleware([isverifyed::class])->group(function(){
    Route::get('/show_lessons',[LessonController::class,'show_lessons']);

});*/
Route::get('/show_lessons',[LessonController::class,'show_lessons']);
Route::get('/show_requirements',[LicenseRequirementsController::class,'show_requirements']);
Route::get('/show_regulations',[TrafficRegulationController::class,'show_regulations']);
Route::get('/show_schools',[DrivingSchoolController::class,'index']);



