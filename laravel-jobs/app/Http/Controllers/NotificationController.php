<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
     //view all notifications 
     public function index(){
      $notifications = Notification::query();
      //(1) get notifications for current user
      $notifications = $notifications->where('user_id', Auth::user()->id)
                                     ->orderbyDesc('id')
                                     ->simplePaginate(10);
      //(2) return it to view
      return view('notification.index')->with('notifications', $notifications);
   }
      //view a specific notification
   public function read($id){
      //(1) find or fail notification
      $notification = Notification::findOrFail($id);
      //(2) validate current user
      $user = Auth::user()->id;
      //(2) update notification
      $notification->read_at = NOW();

      $notification->save();

      //(3) redirect to the url of the notification
      return redirect($notification->url);
   }

   //count number of unread notifications
  public function count(){

     //get count of unread notifications for current user
     $notifications = Notification::query();
     $notifications = $notifications->where('user_id', Auth::user()->id)
                                    ->where('read_at', null)   
                                    ->get();

     $count = count($notifications);
     echo $count;
  }

  public function readAll(){
   //retrieve all unread  notifications 
   $notifications = Notification::query();
   $notifications = $notifications->where('user_id', Auth::user()->id)
                                  ->where('read_at', null)
                                  ->get();
   // mark as read
   foreach($notifications as $n){
      $n->read_at = NOW();
      $n->save();
   }
   //notifications
   $updatedNotifications = Notification::query();
   $updatedNotifications = $updatedNotifications->where('user_id', Auth::user()->id)
                                         ->orderbyDesc('id')
                                         ->simplePaginate(10);

   return view('notification.index')->with('notifications', $updatedNotifications);
  }
}
