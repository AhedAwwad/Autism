@extends('layouts.app')
@section('css')
<link href="../form/css/styleC.css" rel='stylesheet' type='text/css' />
@endsection
@section('content')

<div>
<div class="login-section-agileits">
        <h3 class="form-head"></h3>
        <form action="login1" method="POST">
            {{ csrf_field() }}
         
            <div class="w3ls-icon">
                <span class="fa fa-user" aria-hidden="true" style="float: right;"></span>
                <input type="text" class="lock" name="sp_user_name"  placeholder="اسم المستخدم" value="{{ old('user_name_sp') }}" required autofocus />
            </div>
            <div class="w3ls-icon">
                <span class="fa fa-lock" aria-hidden="true" style="float: right;"></span>
                <input type="password" class="lock" id="password1" name="password" placeholder="كلمة المرور" required="" />
            </div>
            <input type="submit" value="تسجيل الدخول">
        </form>
    </div>        
</div>




@endsection
