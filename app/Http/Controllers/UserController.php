<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use App\User;
use DB;
use Hash;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = User::where('id','!=',Auth::id())->orderBy('id','DESC')->paginate(5);
        return view('user.users',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    public function approve($id){
        $user = User::findOrFail($id);
        $user->update(array('status' => '1'));
        return redirect()->route('pending')
                        ->with('success','User Approved successfully');
    }

    public function pending(Request $request){
        $data = User::where(['status'=> 0])->orderBy('id','DESC')->paginate(5);
        return view('user.pending_user',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
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
            'name'      => 'required|string|max:191',
            'email'     => 'required|email|max:191|unique:users',
            'password'  => 'required|string|min:8'
        ]);
        User::create([
            'name'=> $request['name'],
            'email'=> $request['email'],
            'role'=> $request['role'],
            'password'=> Hash::make($request['password']),
        ]);
        return redirect('users')->with('success','User Added successfully');; 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $user->experiences=json_decode($user->experiences);
        return view('user.show',compact('user'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('user.edit',compact('user'));
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
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'sometimes|same:confirm-password', 
        ]);
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
        $input['status']= ((isset($input['status']) && $input['status'] == 'on') || $input['status'] == 1)? 1:0;
        $user = User::findOrFail($id);
        $user->update($input);
        return redirect('users')->with('success','User updated successfully');
    }

    public function updateUserDetails(Request $request, $id)
    {
        $input = $request->all();
        $experience = array();
        foreach($input['company_name'] as $key => $company )
        {
            $experience[$key]['company_name'] =$company;
        }
        foreach($input['position'] as $key => $position )
        {
            $experience[$key]['position'] =$position;
        }
        foreach($input['exp_month'] as $key => $exp_month )
        {
            $experience[$key]['exp_month'] =$exp_month;
        }
        $input['experiences']=json_encode($experience);
        $user = User::findOrFail($id);
        $user->update($input);
       
        return redirect('users')->with('success','User updated successfully');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user =  User::findOrFail($id);
        $user->delete();
        return redirect('users')->with('message','User deleted successfully'); 
    }
}
