@extends('layouts.app')
@section('css')
<link href="../form/css/styleC.css" rel='stylesheet' type='text/css' />
@endsection
@section('content')

<div class="login-section-agileits">
        <h3 class="form-head"></h3>
        <form action="/register1" method="POST">
            {{ csrf_field() }}
            <div class="w3ls-icon">
                <span class="fa fa-user" aria-hidden="true" style="float: right;"></span>
                <input type="text" class="lock" name="name" placeholder="الاسم" value="{{ old('name') }}" required autofocus>
            </div>
            <div class="w3ls-icon">
                <span class="fa fa-user" aria-hidden="true" style="float: right;"></span>
                <input style="background-color: #eb5273; " type="text" class="email"  name="sp_user_name"  placeholder="اسم المستخدم" value="{{ old('user_name_sp') }}" required>
            </div>
            <div class="w3ls-icon">
                <span class="fa fa-lock" aria-hidden="true" style="float: right;"></span>
                <input type="password" class="lock" id="password1" name="password" placeholder="كلمة المرور" required="" />
            </div>
            <div class="w3ls-icon">
                <span class="fa fa-lock" aria-hidden="true" style="float: right;"></span>
                <input type="password" class="lock" id="password2" name="confirmpassword" placeholder="تأكيد كلمة المرور" required="" />
            </div>
            <input type="submit" value="إنشاء الحساب">
        </form>
    </div>        


    <script type="text/javascript">
        window.onload = function () {
            document.getElementById("password1").onchange = validatePassword;
            document.getElementById("password2").onchange = validatePassword;
        }

        function validatePassword() {
            var pass2 = document.getElementById("password2").value;
            var pass1 = document.getElementById("password1").value;
            if (pass1 != pass2)
                document.getElementById("password2").setCustomValidity("Passwords do not Match");
            else
                document.getElementById("password2").setCustomValidity('');
            //empty string means no validation error
        }
    </script>
@endsection
