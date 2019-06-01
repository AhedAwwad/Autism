<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use DB;
use App\child;
use App\specialist;
use App\stage_child;
class controlpage extends Controller
{   
    //---------- Home Page ----------  
    public function index()
    {
        $child = DB::select('select * from childs');
        $count_child = count($child);
        //echo $count_child;
        $message = DB::select('select * from messages');
        $count_message = count($message);
    	return view('index',compact('child' ,'count_message','count_child'));
    }   

    //---------- Display Register Page ----------  
    public function register()
    {
    	return view('lo.aregister');
    }

    //---------- Create Account For Specialist  ----------  
       public function registerstore(Request $request)
    {
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

    //---------- Display Login Page ----------  
    public function login()
    {
    	return view('lo.login');
    }

    //---------- Login Specialist  ----------  
    public function loginstore()
    {
        if(!auth()->attempt(request(['sp_user_name','password'])))
        {
            return back()->withErrors([
                'message' => 'user or password not correct!!']);
        }
        return redirect('/index');
    }

    //---------- Logout ----------
    public function logout()
    {
        auth()->logout();
        return redirect('/index');
    }

    //---------- display Autism child Page ----------
    public function display($sp_id)
    {
        $child=  DB::select('select distinct *  from childs  
                             where childs.sp_id = '.$sp_id);
        return view('AutismChildren',compact('sp_id','child'));
    }

    //---------- Display Modify Account Specialist Information Page ----------
    public function edit($sp_id)
    {  
        $specialist = DB::select('select * from specialists 
                                  where specialists.sp_id = '.$sp_id);
        return view('edit',compact('sp_id','specialist'));
    }

    //---------- Modify Account Specialist Information ----------
    public function saveedit(Request $request)
    {
        DB::update('update specialists set specialists.sp_name ="'.$request->name.'" 
                    where specialists.sp_id = '.$request->id);
        DB::update('update specialists set specialists.sp_user_name ="'.$request->sp_user_name. '"
                    where specialists.sp_id = '.$request->id);
        DB::update('update specialists set specialists.password ="'.bcrypt($request->password). '" 
                    where specialists.sp_id = '.$request->id);
        DB::update('update specialists set specialists.c_password ="'.bcrypt($request->confirmpassword).'" 
                    where specialists.sp_id = '.$request->id);
        return back();
    }

    //---------- Display Stages For Child  ----------
    public function stage($ch_id)
    {
        $stageinfo = DB::select('select * from stage_children,stages,childs 
                                 where childs.ch_id=stage_children.ch_id 
                                 and stage_children.st_id = stages.st_id 
                                 and childs.ch_id = '.$ch_id);
        return view ('stage',compact('stageinfo'));   
    }

    //---------- Display Modify Child Stage Page ----------
    public function updatestage($ch_id,$st_id)
    {   
        $stageinfo = DB::select('select * from stage_children,stages,childs 
                                 where childs.ch_id=stage_children.ch_id 
                                 and stage_children.st_id = stages.st_id
                                 and childs.ch_id = '.$ch_id.' 
                                 and stages.st_id = '.$st_id);

        $stage = DB::select('select * from stages');        
        return view ('updatestage',compact('stageinfo','stage'));
    }
        
    //---------- Modify child Stage  ----------`  
    public function SaveUpdateSt(Request $request)
    {
        DB::update('update stage_children,stages set stage_children.st_id = '.$request->sel1.' 
                    where stage_children.st_id = stages.st_id 
                    and  stage_children.st_id = '.$request->st_id.' 
                    and stage_children.ch_id = '.$request->ch_id.' 
                    and stage_children.date = "'.$request->date.'"' );

        $stageinfo = DB::select('select * from stage_children,stages,childs 
                                 where childs.ch_id=stage_children.ch_id 
                                 and stage_children.st_id = stages.st_id  
                                 and childs.ch_id = '.$request->ch_id);
        return view ('stage',compact('stageinfo'));
           
    }

    //---------- Display Child Stage Page For Add Note  ----------
    public function noteSt($ch_id)
    {
       $child    = DB::select('select * from stage_children,stages,childs 
                               where childs.ch_id=stage_children.ch_id 
                               and stage_children.st_id = stages.st_id 
                               and childs.ch_id = '.$ch_id);

        $stage    = DB::select('select * from stages ');
        return view('noteSt',compact('ch_id','stage','child'));
    }

    //----------  Add Note For Child Stage  ----------
    public function addnoteSt($ch_id,$st_id)
    {
        $note = DB::select('select * from stage_children,stages,childs 
                            where childs.ch_id=stage_children.ch_id 
                            and stage_children.st_id = stages.st_id 
                            and childs.ch_id = '.$ch_id.' 
                            and stages.st_id = '.$st_id );
        return view ('addnoteSt',compact('note'));
    }

    //----------  Save Note Added For Child Stage  ----------
    public function savenotee(Request $request)
    {
        DB::update('update stage_children,stages set stage_children.notes = "'.$request->note.'"  
                    where stage_children.st_id = stages.st_id 
                    and  stage_children.st_id = '.$request->st_id.' 
                    and stage_children.ch_id = '.$request->ch_id);

        $child    = DB::select('select * from stage_children,stages,childs 
                                where childs.ch_id=stage_children.ch_id 
                                and stage_children.st_id = stages.st_id 
                                and childs.ch_id = '.$request->ch_id);
        return view('noteSt',compact('child'));
    }


    //----------  Display Child Details  Page  ----------
    public function displaychild($ch_id)
    {
        $child = DB::select('select * from childs 
                             where childs.ch_id = '.$ch_id);

        $stage=  DB::select('select distinct *  from childs,stage_children,stages  
                             where childs.ch_id=stage_children.ch_id 
                             and stage_children.st_id = stages.st_id  
                             and childs.ch_id = '.$ch_id);
        return view ('displaychild',compact('ch_id','child','stage'));
    }

    //---------- Return To  Display Autism Child  Page  ----------
    public function homechild(Request $request)
    {   
        $child = DB::select('select distinct *  from childs 
                             where sp_id = '.$request->sp_id);
        return view('AutismChildren',compact('child'));
    }

    //---------- Delete Child ----------
    public function delete($ch_id)
    {
       DB::delete('delete  from childs 
                   where childs.ch_id = '.$ch_id);
       return back();
    }

    //---------- display chat Page ----------
    public function chat()
    { /*
    SELECT DISTINCT specialists.sp_id as id,specialists.sp_name as name,childs.ch_id as id, childs.ch_name as name FROM childs,specialists where (specialists.sp_id =20 and specialists.sp_id=childs.sp_id) OR (specialists.sp_id!=20 )*/
    /*
    SELECT DISTINCT specialists.sp_id as id,specialists.sp_name as name,childs.ch_id as id, childs.ch_name as name FROM specialists LEFT JOIN childs ON childs.sp_id=specialists.sp_id WHERE (specialists.sp_id!=20) or (specialists.sp_id =20)
    */
        return view('chat');
    }
}
    