<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use DataTables;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $students = Student::get();
        if($request->ajax()){
            $allData = DataTables::of($students)
                ->addIndexColumn()
                ->addColumn('action',function($row){
                $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . 
                $row->id . '" data-original-title="Edit" 
                class="edit btn btn-primary btn-sm m-1 editStudent">Edit</a>';

                $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . 
                $row->id . '" data-original-title="Delete" 
                class="delete btn btn-danger btn-sm m-1 deleteStudent">Delete</a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make (true);
            return $allData;
        }
        return view ('students', compact('students'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Student::updateOrCreate(
            ['id' => $request->student_id], // Change this line
            [
                'name' => $request->name,
                'email' => $request->email,
            ]
        );
        return response()->json(['success' => 'Student Added Successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $student = Student::find($id);
        return response()->json($student);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Student::find($id)->delete();
        return response()->json(['success'=>'Student Deleted Successfully']);
    }
}
