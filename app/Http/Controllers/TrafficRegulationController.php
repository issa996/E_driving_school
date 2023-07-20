<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Traffic_Regulation;

class TrafficRegulationController extends Controller
{
    public function add_regulation(Request $request){
        $validated = $request->validate([
            'title' => ['required', 'string'],
            'regulation' => ['required','string']
        ]);
        //return $request;
        $regulation =  Traffic_Regulation::create($validated);
        $regulation->save();
        return response()->json([
            'meesage' => 'the Regulation has been added',
            'status code ' => 200
        ]);
    }
    public function show_regulations(){
        $regulations = Traffic_Regulation::all();
        return response()->json(['regulations' =>$regulations]);
}
public function update_regulation($regulationid,Request $request){
    $data = $request->all();
    Traffic_Regulation::find($regulationid)->update($data);
    return response()->json([
        'message' => ' the regulation has been updated',
        'status code' => 200
    ]);
}
public function delete_regulation($regulationid){
    Traffic_Regulation::find($regulationid)->delete();
    return response()->json([
        'message' => 'the regulation has been deleted',
        'status code' => 200

    ]);
}
}
