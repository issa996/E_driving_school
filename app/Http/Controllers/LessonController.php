<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;

class LessonController extends Controller
{
    public function add_lesson(Request $request){
      /*  $validated = $request->validate([
            'title' => ['string','required'],
            'type' => ['string','required'],
            'content' => ['string','required'],
            'attachment' => ['string','required']

        ]);*/
       
        $lesson = Lesson::create([
            'title' => $request->title,
            'type' => $request->type,
            'content' => $request->content,
            'attachment' =>$request->attachment 
        ]);
        $lesson->save();
        return response()->json([
            'meesage' => 'the lesson has been added',
            'status code ' => 200
        ]);


    }
    public function show_lessons(){
        $lessons = Lesson::all();
        return response()->json(['lessons'=>$lessons]);

    }
    public function update_lesson($lessonid,Request $request){
        $data = $request->all();
        Lesson::find($lessonid)->update($data);
        return response()->json([
            'message' => ' the lesson has been updated',
            'status code' => 200
        ]);
    }
    public function delete_lesson($lessonid){
        Lesson::find($lessonid)->delete();
        return response()->json([
            'message' => 'the lesson has been deleted',
            'status code' => 200

        ]);
    }
}
