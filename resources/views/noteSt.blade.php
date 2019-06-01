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
<br><br><br>
<div class="container">
   
      <div class="row" >
      <div class="col-md-6" style="width: 1000px;">
        <div class="panel panel-primary" style="border-color :#dadfe1;width: 1100px;" >
          <div class="panel-heading" style="border-color:#e9ecef; background-color:#e9ecef; ">
            <h3 class="panel-title">المراحل التي مر بها الطفل</h3>
            <div class="pull-right">
              <span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body">
                <i class="glyphicon glyphicon-search"></i>
              </span>
             
            </div>
          </div>
          <div class="panel-body">
            <input  type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="بحث" />
          </div>
          <table class="table table-hover" id="dev-table">
            <thead>
              <tr>
                <th>إضافة ملاحظة أو تعديلها</th>
                <th>القسم</th>
                <th>المرحلة</th>
              </tr>
            </thead>
            <tbody>
              @foreach($child as $ch)
              <tr>
                <td>
                  <a href="../addnoteSt/{{$ch->ch_id}}/{{$ch->st_id}}" style="color:#f5cd79;"><i class="material-icons">&#xe22b;</i></a>
                </td>
                <td>{{$ch->sub_st_name}}</td>
                <td>{{$ch->st_name}}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    
  </div>
<br>
@endsection