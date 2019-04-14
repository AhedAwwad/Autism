<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use DB;
use App\child;
 
use App\specialist;

class controlpage extends Controller
{
    public function index()
    {
    	return view('layouts.app');
    }
    public function register()
    {
    	return view('lo.aregister');
    }
       public function registerstore(Request $request)
    {
       //create_user
        $specialist = new specialist;
        $specialist->sp_name = $request->name;
        $specialist->sp_user_name   = $request->sp_user_name;
        $specialist->password = bcrypt($request->password);
        $specialist->c_password = bcrypt($request->confirmpassword);
        $specialist->save();
       //login
        auth()->login($specialist);
      //redirect
        return redirect('/index');
    }
    public function login()
    {
    	return view('lo.login');
    }
    public function loginstore()
    {
        if(!auth()->attempt(request(['sp_user_name','password'])))
        {
            return back()->withErrors([
                'message' => 'user or password not correct!!']);
        }
        return redirect('/index');
    }
    public function logout()
    {
            auth()->logout();
            return redirect('/index');
    }
    public function display($sp_id)
    {   $child = DB::select('select distinct *  from childs where sp_id = '.$sp_id);
        return view('AutismChildren',compact('child'));
    }
    public function chat()
    {
        return view('chat');
    }

    public function xx()
    {
        return view('xx');
    }
    public function delete($ch_id)
    {
       DB::delete('delete  from childs where childs.ch_id = '.$ch_id);
       return back();
    }
}
