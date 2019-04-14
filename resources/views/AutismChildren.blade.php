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
      <div class="col-md-6">
        <div class="panel panel-primary" style="border-color :#dadfe1">
          <div class="panel-heading" style="border-color: #bdc3c7; background-color:#bdc3c7; ">
            <h3 class="panel-title">الأطفال</h3>
            <div class="pull-right">
              <span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body">
                <i class="glyphicon glyphicon-filter"></i>
              </span>
              <span class="clickable filter1" data-toggle="tooltip" title="Toggle table filter" data-container="body">
                <i class="glyphicon glyphicon-plus" id="add_row" ></i>
              </span>
            </div>
          </div>
          <div class="panel-body">
            <input type="text" class="form-control" id="dev-table-filter" data-action="filter" data-filters="#dev-table" placeholder="Filter Developers" />
          </div>
          <table class="table table-hover" id="dev-table" >
            <thead>
              <tr>
                <th>Name</th>
                <th>Age</th>
                <th>Parent</th>
                <th>User Name</th>
                <th>stage</th>
                <th>add note</th>
                <th>update</th>
                <th>delete</th>
              </tr>
            </thead>
            <tbody>
              @foreach($child as $ch)
              <tr>
                <td>{{$ch->ch_name}}</td>
                <td>{{$ch->age}}</td>
                <td>{{$ch->parent}}</td>
                <td>{{$ch->user_name}}</td>
                <td>stage</td>
                <td>
                  <a href="../addnote/{{$ch->ch_id}}" style="color:black"><i class="material-icons">&#xe89c;</i></a>
                </td>
                <td>
                  <a href="../update/{{$ch->ch_id}}" style="color:black"><i class="material-icons">&#xe22b;</i></a>
                </td>
                <td>
                  <a href="../delete/{{$ch->ch_id}}" style="color:black"><i class="material-icons">&#xe92b;</i></a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    
  </div>

@endsection