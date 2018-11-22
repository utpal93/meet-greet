@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <div id="imaginary_container">
              <form method="post" action="{{ url('user/search') }}"> 
                {{ csrf_field() }}
                <div class="input-group stylish-input-group">
                    <input type="text" class="form-control" name="search_text"  placeholder="Search a Friend" >
                    <span class="input-group-addon">
                        <button type="submit">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>  
                    </span>
                </div>
              </form>
            </div>
        </div>
    </div>


 <div class="top">
    <h2> Friend List</h2>
  </div>
  <div class="row">


    @foreach($friends as $friend)

    <div class="col-md-4">

        <div class="shadow">
          <div class="col-sm-8">
            <div class="col-sm-8">
              <img src="{{ asset('images/'.$friend->avatar) }}" class="img-circle" width="60px">
            </div>
            <div class="col-sm-8">
              <h4>{{ $friend->name }}</h4>
              <h4> Friend Since  {{ date('d-m-y',$friend->friend_since )}} </h4>

              <a href="{{ url('user_profile', $friend->id) }}" class="btn btn-xs btn-primary">View</a>
              
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
