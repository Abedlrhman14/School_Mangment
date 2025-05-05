<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(){
        $grdaes=Grade::all();
        return response()->json($grdaes);
    }
    public function store(Request $request){
        $request->validate([
            'name'=>'required|string|max:255',
        ]);
        $grade=Grade::create([
            'name'=>$request->name,
        ]);
        return response()->json($grade,201);
    }
    public function destroy($id){
        $grade = Grade::find($id);
        if(! $grade){
            return response()->json(['messege'=>'Grade Not Found',404]);
        }
        $grade->delete();
        return response()->json(['message'=>'Grade deleted successfully']);
    }
    public function search(Request $request){
        $query = $request->query('q'); //search by name
        if(!$query){
            return response()->json(Grade::all(),200);
        }
        $grades =Grade::where('name','LIKE',"%{$query}%")->get();
        return response()->json($grades,200);
    }
}
