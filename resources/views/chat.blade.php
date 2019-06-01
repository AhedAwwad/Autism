@extends('layouts.app')
@push('css1')
  <link href="{{ url('chat/css/style.css') }}" rel="stylesheet">
@endpush
@push('js1')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.js"></script>
<script type="text/javascript">
 var user_id  = '{{ auth()->user()->sp_id}}';
 var username = '{{ auth()->user()->sp_name }}';
 var typingurl = '{{ url('chat/image/typing.gif') }}';
</script>
<script src="{{ url('chat/js/script.js') }}"></script>
@endpush
@section('content')



 <div class="container">

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Chat Control</div>

                <div class="card-body">


<div class="dropdown">
  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
    <span class="current_status" status="online">Online</span>
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item status" status="online" href="#">Online</a>
    <a class="dropdown-item status" status="offline" href="#">Offline</a>
    <a class="dropdown-item status" status="bys" href="#">Busy</a>
    <a class="dropdown-item status" status="dnd" href="#">don't disturb</a>
  </div>
</div>

                    <div id="chat-sidebar">
                        @foreach(App\specialist::where('sp_id','!=',auth()->user()->sp_id)->get() as $user)
                        <div id="sidebar-user-box" class="user" uid="{{ $user->sp_id }}">
                        <img id="img1" src="{{ url('chat/image/user.png') }}" />
                        <span id="slider-username">{{ $user->sp_name}}</span>
                        <span class="user_status user_{{ $user->sp_id }}">&nbsp;</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
@endsection