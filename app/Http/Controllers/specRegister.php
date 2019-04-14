<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\specialist;

class specRegister extends Controller
{
    public function create()
    {
    	return view('register');
    }

    public function store(Request $request)
    {
    	$specialist = new specialist;
    	$specialist->sp_name  = $request->name;
    	$specialist->password = $request->password;
    	$specialist->center   = $request->center;
    	$specialist->email    = $request->email;
    	$specialist->c_password= $request->confirmpassword;  
    }
}
