<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\FriendRequest;
use App\Friend;
use DateTime;


class HomeController extends Controller
{
   
    public function __construct()
    {


        $this->middleware('auth');
    }

   
    public function index()
    {


        $user_id = \Auth::user()->id;
        $friends = Friend::where('user_id',$user_id)
                   ->join('users', 'friends.friend_id', '=', 'users.id')
                   
                  ->get();


        
        
        return view('home',compact('friends'));
    }


    public function search_user(Request $request){

          $search_term = $request->search_text;
          $user_id = \Auth::user()->id;

          $users = User::where('name','LIKE','%'.$search_term.'%')
                    ->leftJoin('friends', function($q) use($user_id) {
                        $q->on('users.id', '=', 'friends.friend_id');
                        $q->where('friends.user_id', '=', "$user_id");
                    })
                    ->leftJoin('friend_request', function($q) use($user_id) {
                        $q->on('users.id', '=', 'friend_request.reciever_id');
                        
                    })
                    ->leftJoin('friend_request as f1', function($q) use($user_id) {
                        
                        $q->on('users.id', '=', 'f1.sender_id');
                     })
                    
                    ->select('users.id','users.name','users.email','users.avatar',
                      DB::raw('CASE WHEN friends.id IS NULL THEN 0 ELSE 1 END as is_friend'),
                      DB::raw('CASE WHEN friend_request.id IS NULL THEN 0 ELSE 1 END as self_requested'),
                      DB::raw('CASE WHEN f1.id IS NULL THEN 0 ELSE 1 END as is_requested')
                      
                      
                      
                    )
                   
                    ->distinct('users.id')
                    ->get(); 


         return view('serch_result', compact('users','user_id'));

          
    }


    public function save_friend_request($id){

         $sender_id = \Auth::user()->id;
         $reciever_id = $id;
         $status = "Recieved";

         $now = new DateTime();
         $date =  $now->getTimestamp();

         $reqObj = new FriendRequest();
         $reqObj->reciever_id = $reciever_id;
         $reqObj->sender_id = $sender_id;
         $reqObj->posted_on = $date;
         $reqObj->status = $status;
         $reqObj->is_seen = 0;

         $reqObj->save();

         return redirect('home');




    }


    public function notification_count(){

         $user_id = \Auth::user()->id;

         $count = FriendRequest::where('reciever_id', $user_id)->where('is_seen',0)->count();

         echo json_encode($count );
    }

    public function notifications(){

         $user_id = \Auth::user()->id;

         $notifications = FriendRequest::where('reciever_id', $user_id)
                         ->join('users', 'friend_request.sender_id', '=', 'users.id')
                         ->select('users.name','friend_request.posted_on','friend_request.id as nId')
                         ->get();

         echo json_encode($notifications );
    }

    public function user_profile_n($id){

          

          DB::table('friend_request')
            ->where('id', $id)
            ->update(['status' => "Seen", 'is_seen' =>1] );

          $f_request = FriendRequest::where('id', $id)->first();
          $user_id = $f_request->sender_id;
          $f_request_id = $id;

          $user = User::find($f_request->sender_id)
                   ->leftJoin('friend_request', function($q) use($user_id) {
                        $q->on('users.id', '=', 'friend_request.sender_id');
                        $q->where('friend_request.sender_id', '=', "$user_id");
                    })
                 ->select('users.id','users.avatar','users.name','users.email',
                  DB::raw('CASE WHEN friend_request.id IS NULL THEN 0 ELSE 1 END as is_requested'))

                 ->first();

         

          $sender_friends = Friend::where('user_id',$f_request->sender_id)
                       ->join('users', 'friends.friend_id', '=', 'users.id')
                       ->where('friends.friend_id','!=',$id)
                       ->select('users.id','users.name','users.avatar','friends.friend_since')
                       ->get()->toArray();
          $reciever_friends = Friend::where('user_id',$f_request->reciever_id)
                         ->join('users', 'friends.friend_id', '=', 'users.id')
                         ->select('users.id','users.name','users.avatar','friends.friend_since')
                        ->get()->toArray();

          $common = array_intersect_key($sender_friends,$reciever_friends);

          $mutual_friends = collect($common);

          
         return view('profile', compact('user','f_request_id','mutual_friends'));


    }

    public function user_profile($id){

           $user_id = \Auth::user()->id;

           

            $user = User::where('users.id',$id)
                   ->leftJoin('friend_request', function($q) use($id) {
                        $q->on('users.id', '=', 'friend_request.sender_id');
                        $q->where('friend_request.sender_id', '=', "$id");
                    })
                 ->select('users.id','users.avatar','users.name','users.email','friend_request.id as f_request_id',
                  DB::raw('CASE WHEN friend_request.id IS NULL THEN 0 ELSE 1 END as is_requested'))

                 ->first();

           $f_request_id = $user->f_request_id;

          $my_friends = Friend::where('user_id',$user_id)
                       ->join('users', 'friends.friend_id', '=', 'users.id')
                       ->where('friends.friend_id','!=',$id)
                       ->select('users.id','users.name','users.avatar','friends.friend_since')
                       ->get()->toArray();
          $profile_friends = Friend::where('user_id',$id)
                         ->join('users', 'friends.friend_id', '=', 'users.id')
                         ->select('users.id','users.name','users.avatar','friends.friend_since')
                        ->get()->toArray();

          $common = array_intersect_key($my_friends,$profile_friends);

          $mutual_friends = collect($common);
          
          return view('profile', compact('user','mutual_friends','f_request_id'));

         

    }

    public function add_friend($id){

         $user_id = \Auth::user()->id;
         $now = new DateTime();
         $date =  $now->getTimestamp();

         $f_request = FriendRequest::where('id', $id)->first();

         $fObj = new Friend();

         $fObj->user_id = $user_id;
         $fObj->friend_id = $f_request->sender_id;
         $fObj->friend_since = $date;

         $fObj->save();

         $fObj2 = new Friend();

         $fObj2->user_id = $f_request->sender_id;
         $fObj2->friend_id = $user_id;
         $fObj2->friend_since = $date;

         $fObj2->save();

         FriendRequest::where('id', $id)->delete();


         return redirect('home');


    }
}
