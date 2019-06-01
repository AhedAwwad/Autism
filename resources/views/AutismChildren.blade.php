@extends('layouts.app')
@section('css')
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="../form/css/style.css" rel='stylesheet' type='text/css' />
<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
@endsection

@section('js')
<script src="/js/filter.js"></script>
@endsection

@section('content')
<div class="container">
      <div class="row" >
       <div class="col-md-6" style="width: 1000px;">
        <div class="panel panel-primary" style="border-color :#dadfe1;width: 1100px;" >
          <div class="panel-heading" style="border-color:#e9ecef; background-color:#e9ecef; ">
            <h3 class="panel-title">الأطفال</h3>
            <div class="pull-right">
              <span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body">
                <i class="glyphicon glyphicon-search"></i>
              </span>
             
            </div>
          </div>
          <div class="panel-body">
            <input   type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="بحث" />
          </div>
          <table class="table table-hover" id="dev-table" >
            <thead>
              <tr>
                <th>الصورة</th>
                <th>عرض</th>
                <th>إضافة ملاحظة</th>
                <th>حذف</th>
                <th>المراحل</th>             
                <th>العمر</th>
                <th>اسم الأب</th>
                <th>اسم المستخدم</th>
                <th>الاسم</th>
              </tr>
            </thead>
            <tbody>
              @foreach($child as $ch)
              <tr>
                <td>
                <img src="{{ asset('imgChild/'.$ch->profile_ch) }}"  style="border-radius:20px;" height="13px" width="40" 
                     alt=""  title="" />
                </td>
                <td>
                  <a href="../displaychild/{{$ch->ch_id}}" style="color:#3dc1d3;"><i class="material-icons">&#xe8a6;</i></a>
                </td>
                <td>
                  <a href="../noteSt/{{$ch->ch_id}}" style="color:#3ae374;"><i class="material-icons">&#xe89c;</i></a>
                </td>
                <td>
                  <a href="../delete/{{$ch->ch_id}}" style="color:#e66767;"><i class="material-icons">&#xe92b;</i></a>
                </td>
                <td>
                  <a href="../stage/{{$ch->ch_id}}" style="color:#f5cd79;"><i class="material-icons">&#xe22b;</i></a>
                </td>
                <td>{{$ch->age}}</td>
                <td>{{$ch->parent}}</td>
                <td>{{$ch->user_name}}</td>
                <td>{{$ch->ch_name}}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection