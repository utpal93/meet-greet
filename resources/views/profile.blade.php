@extends('layouts.app')

@section('content')
<div class="container">

    


 <div class="top">
    <h2> Profile</h2>
  </div>
  <div class="row">




    <div class="col-md-4">

        <div class="shadow">
          <div class="col-sm-8">
            <div class="col-sm-8">
              <img src="{{ asset('images/'.$user->avatar) }}"  class="img-circle" width="60px">
            </div>
            <div class="col-sm-8">
              <h4>{{ $user->name }}</h4>
              <h4>{{ $user->email }}</h4>

              @if( $user->is_requested == 1)

                 <a href="{{ url('add/friend', $f_request_id) }}" class="btn btn-xs btn-primary">Accept Request</a>
              @endif
              
            </div>
            
          </div>
          <div class="clearfix"></div>
          <hr />
          
        </div>

    </div>
  </div>


  <div class="top">
    <h2> Mutual Friend List</h2>
  </div>
  <div class="row">


   @if(empty($mutual_friends))

    <h4><p> No Mutual Friend's Found!! </p> </h4>


  @else
    @foreach($mutual_friends as $friend)

    <div class="col-md-4">

        <div class="shadow">
          <div class="col-sm-8">
            <div class="col-sm-8">
              <img src="{{ asset('images/'.$friend['avatar']) }}" class="img-circle" width="60px">
            </div>
            <div class="col-sm-8">
              <h4>{{ $friend['name']}}</h4>
              
            </div>
            
          </div>
          <div class="clearfix"></div>
          <hr />
          
        </div>

    </div>

   @endforeach

  @endif
    
  </div>


    
</div>
@endsection
