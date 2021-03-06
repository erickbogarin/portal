<?php

namespace portal\Http\Controllers;

use Illuminate\Http\Request;
use portal\Http\Requests;
use portal\User;
use Hash;
use Mail;
use portal\Jobs\SendWelcomeEmail;

class UserController extends Controller
{

	public function __construct()
	{
//		$this->middleware('auth');
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	$input = $request->all();
    	if($request->get('search')){
    		$users = User::where("name", "LIKE", "%{$request->get('search')}%")
    		->orderBy('name', 'asc')->paginate(5);      
    	}else{
    		$users = User::orderBy('name', 'asc')->paginate(5);
    	}
    	return response($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $user = User::create($input);

        //$this->dispatch(new SendWelcomeEmail($user));

        $user->fill([
            'password' => Hash::make($request->password)
        ])->save();

        return response($user);
    }

    public function show($id) {
        return  User::find($id);
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
        return response($user);        
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
        $input = $request->all();
        User::where("id",$id)->update($input);
        $user = User::find($id);
        return response($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return User::where('id',$id)->delete();
    }
}
