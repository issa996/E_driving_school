<?php

namespace App\Http\Controllers;

use App\Models\License_Requirements;
use Illuminate\Http\Request;

class LicenseRequirementsController extends Controller
{
    public function add_requirement(Request $request){
        $validated = $request->validate([
            'title' => ['required', 'string'],
            'requirement' => ['required','string']
        ]);
        //return $request;
        $requirement =  License_Requirements::create($validated);
        $requirement->save();
        return response()->json([
            'meesage' => 'the requirement has been added',
            'status code ' => 200
        ]);



    }
    public function show_requirements(){
        $requirements = License_Requirements::all();

        return response()->json(['requirements'=>$requirements]);
}
public function update_requirement($requirementid,Request $request){
    $data = $request->all();
    License_Requirements::find($requirementid)->update($data);
    return response()->json([
        'message' => ' the licence requirement has been updated',
        'status code' => 200
    ]);
}
    public function delete_requirement($requirementid){
    License_Requirements::find($requirementid)->delete();
    return response()->json([
        'message' => 'the license requirement has been deleted',
        'status code' => 200

        ]);
    }

}
