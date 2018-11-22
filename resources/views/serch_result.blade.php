@extends('layouts.app')

@section('content')
<div class="container">

    

 <div class="top">
    <h2> User List</h2>
  </div>
  <div class="row">

    @foreach($users as $user)

    <div class="col-md-4">

        <div class="shadow">
          <div class="col-sm-8">
            <div class="col-sm-8">
              <img  src="{{ asset('images/'.$user->avatar) }}" class="img-circle" width="60px">
            </div>
            <div class="col-sm-8">
              <h4> {{$user->name}} </h4>
              @if( $user->is_friend == 1)
              <h4> Friend Since  01-11-2017 </h4>
              @endif

               
 
              <a href="{{ url('user_profile', $user->id) }}" class="btn btn-xs btn-primary">View</a>

               @if( $user->is_friend == 0 && $user->is_requested == 0 && $user->self_requested == 0 )

               <a href="{{ url('send/friend_request', $user->id) }}" class="btn btn-xs btn-warning">Send Request</a>

               

              @endif

               
              
            </div>

          </div>

          <div class="clearfix"></div>
          <hr />
          
        </div>

    </div>

  @endforeach


  </div>


    
</div>
@endsection
