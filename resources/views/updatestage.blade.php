@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="/style.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="../form/css/style.css" rel='stylesheet' type='text/css' />
<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="/a/astyle.css"  />
<script type="text/javascript" src="/a/ajs.js"></script>
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


<form   action="../../../SaveUpdateSt" method="post" enctype="multipart/form-data">
  {{ csrf_field() }}
    @foreach($stageinfo as $stagein)
    <input type="hidden" value="{{$stagein->st_id}}" name="st_id" placeholder="" required="">
    <input type="hidden" value="{{$stagein->ch_id}}" name="ch_id" placeholder="" required="">
    <input type="hidden" value="{{$stagein->date}}" name="date" placeholder="" required="">
    @endforeach
 
    <div class="row setup-content" id="step-1">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3 style="text-align: center;">تعديل المرحلة</h3>
                <div class="form-group">
                    <label class="control-label"> تحديد المرحلة</label>
                       <select  style="direction:RTL;" class="form-control" id="sel" name="sel1">
                                           <option value="#">اختر المرحلة</option>
                                           @foreach ($stage as $st)
                                           <option value={{$st->st_id}}>{{$st->st_name}}</option>
                                           @endforeach
                        </select>
                </div>
                </div>
               <button style="background-color:#3ae374;border-color: #3ae374; margin-bottom: 197px;" class="btn btn-success btn-lg pull-right" type="submit">حفظ التعديلات</button>
            </div>
        </div>
    </div>

    <div class="row setup-content" id="step-2">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3 style="text-align: center;">معلومات المراحل</h3>
              
            </div>
        </div>
    </div>
</form>




</div>
@endsection