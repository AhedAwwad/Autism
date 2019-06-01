@extends('layouts.app')
@section('css')
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="../form/css/style.css" rel='stylesheet' type='text/css' />
<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="../a/astyle.css"  />
<script type="text/javascript" src="../a/ajs.js"></script>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

@endsection

@section('content')
<br><br><br>
<div class="container">
<div class="stepwizard">
    <div class="stepwizard-row setup-panel">
        <div class="stepwizard-step">
            <a href="#step-1" style="background-color:#1dc8d9;border-color: #1dc8d9;" type="button" class="btn btn-primary btn-circle">1</a>
            <p></p>
        </div>
      
    </div>
</div>

  @foreach($specialist as $spec)
<form   action="/saveedit" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
  <input type="hidden" value="{{$spec->sp_id}}" name="id" placeholder="" required="">
    <div class="row setup-content" id="step-1">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3 style="text-align: center;">تعديل العلومات الشخصية</h3>
                <div class="form-group">
                    <label class="control-label" style="float: right;">الاسم</label>
                    <input style="direction:RTL;background-color: #e9ecef; " maxlength="100" type="text" required autofocus class="form-control" placeholder="الاسم" name="name" value="{{$spec->sp_name}}"  />
                </div>
                <div class="form-group">
                    <label class="control-label" style="float: right;">اسم المستخدم</label>
                    <input style="direction:RTL;background-color: #e9ecef;" maxlength="100" type="text" required autofocus class="form-control" placeholder="اسم المستخدم" name="sp_user_name"  value="{{$spec->sp_user_name}}" />
                </div>

                <div class="form-group">
                    <label class="control-label" style="float: right;">كلمة المرور الجديدة </label>
                    <input style="direction:RTL;background-color: #e9ecef;" maxlength="100" type="password" required="required" class="form-control" id="password1" name="password" placeholder="كلمة المرور"
                     value="" />
                </div>

                <div class="form-group">
                    <label class="control-label" style="float: right;">تأكيد كلمة المرور</label>
                    <input style="direction:RTL;background-color: #e9ecef;" maxlength="100" type="password" required="required" class="form-control" id="password2" name="confirmpassword" placeholder="تأكيد كلمة المرور"   value="" />  
                </div>
                
                <button style="background-color:#3ae374;border-color: #3ae374;" class="btn btn-success btn-lg pull-right" type="submit">حفظ التعديلات</button>
            </div>
        </div>
    </div>
  
</form>
</div>
@endforeach


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