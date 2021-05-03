<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;
use App\lecture;
use DB;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Course::orderBy('id','DESC')->paginate(5);
         return view('courses.courses',compact('data'))->with('i', ($request->input('page', 1) - 1) * 5);;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('courses.create');
    }


    public function fileUploadPost(Request $request)
    {
        $request->validate([
            'file' => 'required',
		]);
        $fileName = time().'.'.request()->file->getClientOriginalExtension();
        request()->file->move(public_path('files'), $fileName);        
        return response()->json(['success'=>'You have successfully upload file.','filename'=> $fileName]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {           
        $this->validate($request,[
            'name'      => 'required|string|min:4|max:191',
            'description'=> 'required|string|min:8|max:191',
            'type'      => 'required|string|min:3|max:50',
            'subject'   => 'required|string|min:4|max:50',
        ]);
        $course=Course::create([
            'name'=> $request['name'],
            'description'=> $request['description'],
            'type'=> $request['type'],
            'subject'=> $request['subject'],
            'status' => $request['status'],
            'price'  => $request['price']
        ]);
        if(isset($request['video_title']) && isset($request['video_path']) && (count($request['video_title']) == count($request['video_path'])))
        {
            foreach($request['video_title'] as $key => $video_title)
            {
                Lecture::create([
                    'video_path'=> $request['video_path'][$key],
                    'video_title'=> $request['video_title'][$key],
                    'course_id'=>  $course->id,
                ]);
            }
        }
        return redirect('courses')->with('success','Course Created Successfully'); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $course = Course::find($id);
         $lectures = Lecture::all()->where('course_id',$id);
         $course->lectures = $lectures;
        //  dd($course);
         return view('courses.edit',compact('course'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name'      => 'required|string|min:4|max:191',
            'description'=> 'required|string|min:8|max:191',
            'type'      => 'required|string|min:3|max:50',
            'subject'   => 'required|string|min:4|max:50',
        ]);
        $course = Course::findOrFail($id);
        $course->update($request->all());
        DB::table('lectures')->where('course_id', $id)->delete();
        if(isset($request['video_title']) && isset($request['video_path']) && (count($request['video_title']) == count($request['video_path'])))
        {
            foreach($request['video_title'] as $key => $video_title)
            {
                Lecture::create([
                    'video_path'=> $request['video_path'][$key],
                    'video_title'=> $request['video_title'][$key],
                    'course_id'=>  $course->id,
                ]);
            }
        }
        return redirect('courses')->with('success','Course updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course =  Course::findOrFail($id);
        $course->delete();
        return redirect('courses')->with('message','Course deleted successfully'); 
    }
}
