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
@endsection
@section('content')
<br><br><br>
<div class="container">
<div class="stepwizard">
    <div class="stepwizard-row setup-panel">
        <div class="stepwizard-step">
            <a style="background-color:#1dc8d9;border-color: #1dc8d9;" href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
        </div>
        <div class="stepwizard-step">
            <a style="background-color:#1dc8d9;border-color: #1dc8d9;" href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
        </div>
        <div class="stepwizard-step">
            <a style="background-color:#1dc8d9;border-color: #1dc8d9;" href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>
        </div>
    </div>
</div>

  @foreach($child as $ch)
<form   action="../homechild" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
  <input type="hidden" value="{{$ch->ch_id}}" name="id" placeholder="" required="">
  <input type="hidden" value="{{$ch->sp_id}}" name="sp_id" placeholder="" required="">
    <div class="row setup-content" id="step-1">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3 style="text-align: center;">العلومات الشخصية</h3>
                <div class="form-group">
                    <label class="control-label">اسم الطفل</label>
                    <input readonly  maxlength="100" type="text" required="required" class="form-control" placeholder="أدخل اسم اطفل" name="ch_name" value="{{$ch->ch_name}}"  />
                </div>
                <div class="form-group">
                    <label class="control-label">اسم الأب</label>
                    <input readonly  maxlength="100" type="text" required="required" class="form-control" placeholder="أدخل اسم الأب" name="parent"  value="{{$ch->parent}}" />
                </div>

                <div class="form-group">
                    <label class="control-label">عمر الطفل</label>
                    <input readonly maxlength="100" type="text" required="required" class="form-control" placeholder="أدخل عمر الطفل"  name="age" value="{{$ch->age}}" />
                </div>

                <div class="form-group">
                    <label class="control-label">المركز التابع له الطفل</label>
                    <input readonly maxlength="100" type="text" required="required" class="form-control" placeholder="أدخل اسم المركز" name="center"  value="{{$ch->center}}" />
                </div>
                <button style="background-color:#1dc8d9;border-color: #1dc8d9;" class="btn btn-primary nextBtn btn-lg pull-right" type="button" >التالي</button>
            </div>
        </div>
    </div>
    <div class="row setup-content" id="step-2">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3 style="text-align: center;">معلومات المراحل</h3>
             
             
                @foreach($stage as $st)
                
                <input type="hidden" value="{{$st->st_ch_id}}" name="st_ch_id" placeholder="" required="">
                   
                    <div class="row" style="border: outset; padding: 5px;">
                    <div class="col-md-6">
                          <div class="form-group">
                            <label class="control-label">عدد المحاولات الناجحة</label>
                            <input readonly type="text"  class="form-control" placeholder="أدخل عدد المحاولات الناجحة" name="success_sub_st" value="{{$st->success_sub_st}}" />
                        </div>
                        <div class="form-group">
                            <label class="control-label">عدد المحاولات الفاشلة</label>
                            <input readonly type="text"  class="form-control" placeholder="أدخل عدد المحاولات الفاشلة" name="failed_sub_st" class="form-control" value="{{$st->failed_sub_st}}" />
                        </div>
                        <div class="form-group">
                            <label class="control-label">الملاحظات</label>
                             <input readonly type="text" class="form-control" placeholder="Your Phone Number *" name="notes" class="form-control" value="{{$st->notes}}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">المرحلة</label>
                            <input readonly type="text"  class="form-control" placeholder="أدخل عدد المحاولات الناجحة" name="success_sub_st" value="{{$st->st_name}}" />
                        </div>
                        <div class="form-group">
                            <label class="control-label">القسم</label>
                            <input readonly type="text"  class="form-control" placeholder="أدخل عدد المحاولات الناجحة" name="success_sub_st" value="{{$st->sub_st_name}}" />
                        </div>
                      
                        <div class="form-group">
                            <label class="control-label">عدد الصور ضمن المرحلة</label>
                            <input readonly  type="text" class="form-control" placeholder="Your Phone Number *" name="picture_number" class="form-control" value="{{$st->picture_number}}" />
                        </div>
                    </div>
                </div>
                @endforeach
            <br>
                <button style="background-color:#1dc8d9;border-color: #1dc8d9;" class="btn btn-primary nextBtn btn-lg pull-right" type="button" >التالي</button>
            </div>
        </div>
    </div>


    <div class="row setup-content" id="step-3">
        <div class="col-xs-12">
            <div c-lass="col-md-12">
                <h3 style="text-align: center;">الصورة الشخصية</h3>
                <br><br>
                <img src="{{ asset('imgChild/'.$ch->profile_ch) }}"  style="padding-top: 60px; margin-left: 270px ;border-radius: 15px 50px;  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);" width="160" height="160"  alt=""  title="" />

                <button style="align-self:  center;background-color:#3ae374;border-color: #3ae374;margin-top: 140px; " class="btn btn-success btn-lg pull-right" type="submit">تم</button>
            </div>
        </div>
    </div>
</form>
@endforeach
</div>
@endsection