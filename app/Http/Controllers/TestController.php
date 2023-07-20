<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;
use App\Http\Requests\AddTestRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreTestRequest;
use App\Http\Requests\UpdateTestResquest;
use App\Models\Driving_student;
use App\Models\Question;
use App\Models\Answer;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tests = Test::findAll();
        return $tests;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data =$request->all();
        $test = Test::create([
            'name' => $data['name'],
            'driving_school_id' => $data['school_id']
        ]);
        $students = Driving_student::where('driving_school_id',$data['school_id'])->get();
        //return $students;
        foreach($students as $student){
            //return $students;
            $test->students()->attach($student);

        }
        //$test->students()->attach($student);
       
        return response()->json([
            'message' => 'the test has been added',
            'status code' => 201
            
          
           

        ]);

       
           
            

        }

    /**
     * Display the specified resource.
     */
    public function show(Test $test)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Test $test)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$testid)
    {
        $validated = $request->all();
        $test = Test::find($testid);
        
        $test->update([
            'name' => $validated['name']
        ]);
        return response()->json([
            'message' => 'the test has been updated',
            'status code' => 200
            
          
           

        ]);
        

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($testid)
    {
        $test = Test::find($testid);
        //$test->students()->detach();
        
           $test->delete();
            return response()->json([
                'message' => 'test has been deleted'

            ]);
          
        
        
    }
    public function createQuestion($data,$test_id){
        
        $validator = Validator::make($data,[
            'body' => ['required','string'],
            'image' => ['nullable','mimes:png,jpeg,gif'],
            'answers' => [ 'array','size:4']
             
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
            }
        $validator = $validator->validated();
        
        
        if(isset($validator['image']))
        {
            $relativePath = $this->saveImage($data['image']);
            $validator['image'] = $relativePath;
        }
       /* $question = Question::create([
            'body' => $validator['body'],
           'image' => $validator['image'],
            'test_id' => $test_id
            
        ]);*/
        $question = new Question($validator);
        Test::find($test_id)->questions()->save($question);
        $questionId = $question->id; 
        //return $questionId; 
        foreach($validator['answers'] as $answer){
            $this->createAnswer($answer,$questionId);
        
            
    }}
           
           /* //if(!isset($data['image'])){
           // foreach($data as $q)
           foreach($data as $q)
                 if(isset($q['image'])){
                    echo 'hdfuiosghgdhfio';
                 }
            //}*/
        
        //}
       

            
    
    public function createAnswer($data,$questionId){
        $validator = Validator::make($data,[
           
            'image' => ['nullable','mimes:png,jpeg'],
            'is_true' => ['required','boolean'],
            'answer' => ['nullable','string'],

        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
            }
        $validator = $validator->validated();
        
        
        if(isset($validator['image'])){
            $relativePath = $this->saveImage($data['image']);
            $validator['image'] = $relativePath;
        }
        $answer = new Answer($validator);
        Question::find($questionId)->answers()->save($answer);
       


    }
    public function updateQuestion($data){
        return $data;
        $validator = Validator::make($data,[
            'id' => ['reqiured'],
            'body' => ['required','string'],
            'image' => ['nullable','mimes:png,jpeg,gif'],
           // 'answers' => [ 'array','size:4']
             
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
            }
            $validator = $validator->validated();
            $question = Question::find($data['id'])->first();
            if($question->image){
                $absolutePath = public_path($question->image);
                File::delete($absolutePath);
            }
            if(isset($validator['image'])){
                $relativePath = $this->saveImage($validator['image']);
                $validator['image'] = $relativePath;
            }
            $question->update($validator);

            

            


    }
    public function saveImage($image){
       
        $imageName= date('YmdHi').$image->getClientOriginalName();
        $dir = 'images/';
        $absolutePath = public_path($dir);
        $relativePath = $dir.$imageName;
        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0755, true);
        }
        file_put_contents($relativePath, $image);

        return $relativePath;
       
    }
}
