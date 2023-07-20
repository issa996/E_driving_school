<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddQuestionRequest;
use Illuminate\Support\Facades\File;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Support\Facades\Validator;
use App\Models\Test;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\Foreach_;

class QuestionController extends Controller
{
    public function add_question(Request $request){
        $validated =  $request->all();
        //return $validated;
        if(isset($validated['image'])){
            $relativePath = $this->saveImage($validated['image']);
            $validated['image'] = $relativePath;

        }
        $question = new Question($validated);
        $question = Test::find($validated['test_id'])->questions()->save($question);
        foreach ($validated['answers'] as $answer) {
            $this->add_answer($answer,$question->id);
        }
        return response()->json([
            'message' => 'the question has been added',
            'status' => 201

        ]);

        
        
       /* foreach($validated['answer'] as $key =>$value) { 
            if(is_array($value)){
                foreach($value as $k =>$v) { 
                    return $k . ' => '. $v ."\n";}
                    
            }
            else{
                return $key .' => '.$value;
            }
             return $key . ' => '. $value ."\n";}*/
              /*  foreach($value as $k => $v)
                    {  
                        if(empty($value['image']) ){
                            return $value['image'];
                           
                    

                        }
                        else{
                            return 'fijdiof';
                        }

                    

                    }
            }*/
        
  
    
        /*$question = new Question;
        $answer = new Answer();
        if($request->hasFile('image')){
        $image_name = time().rand(1,10000).$request->image->extension();
        $image_path = 'uploads/'.auth()->user()->name;
        $question->image = $image_path;
           $request->image->move(public_path($image_path),$image_name);
    }
    $question->body = $validated['body'];
    $question->test_id = $validated['test_id'];
    if($request->hasfile('answer[0][image]'))
    $question->save();
    foreach($validated['answer'] as list($a,$b,$c)){
    $question->answers()->save($answer);}
    return response()->json([
        'message' => 'question has been added',
        'status code ' => 200
    ]);*/
    }
    public function add_answer($data,$questionId){
       
        if(isset($data['image'])){
            $relativePath = $this->saveImage($data['image']);
            $data['image'] = $relativePath;
        }
        $answer = new Answer($data);
        Question::find($questionId)->answers()->save($answer);
        



    }
    public function update_question($questionid,Request $request){
        $data = $request->all();
        $question = Question::find($questionid);
       // return $question;
        if(isset($question->image)){
            if(File::exists($question->image)) {
                File::delete($question->image);
            }
            $question->update(['image' => null]);
        }
        if(isset($request->image)){
            $relativePath = $this->saveImage($data['image']);
            $data['image'] = $relativePath;
            //return $data['image'];
        }
        $question->update($data);
        //return $data['answers'];
        foreach ($data['answers'] as $answer) {
            $this->update_answer($answer,);
        }
        return response()->json([
            'message' => 'the question has been updated',
            'status code' => 200

        ]);


    }
    public function update_answer($data){
        //return $data['id'];
        $answer = Answer::find($data['id']);
        //return $data['id'];
        if(isset($answer['image'])){
            if(File::exists($answer->image)) {
                File::delete($answer->image);
            }
            $answer->update(['image' => null]);
            if(isset($data['image'])){
                $relativePath = $this->saveImage($data['image']);
                $data['image'] = $relativePath;
            }
            $answer->update($data);

        }
        
    }
    public function delete_question($questionid){
        $question = Question::find($questionid);
        if(isset($question->image)){
            if(File::exists($question->image)){
                File::delete($question->image);
            }
        }
        //return $question->answers;
        foreach($question->answers as $answer){
            if(isset($answer->image)){
                if(File::exists($answer->image)){
                    File::delete($answer->image);

                }
            }

        }
        $question->delete();
        return response()->json([
            'message' => 'the question has been deleted',
            'status code' => 201

        ]);
            
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
