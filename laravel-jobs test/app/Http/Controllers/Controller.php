<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Job;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function notifyUser($user_id, $message, $url){

        $new = new Notification();
        $new->user_id = $user_id;
        $new->message = $message;
        $new->url = $url;

        $new->save();

    }

    public function notify($mesage, $url){
        $this->notifyUser(Auth::user()->id, $message, $url);

    }

   

    public function notifyEnabledUsers($message, $url){

        $users = User::where('status', 'Enabled')->get();

        foreach($users as $user){
            $this->notifyUser($user->id, $message, $url);
        }
        
    }

    static function displayStatus($status){

        if($status == "Open"){
            echo '<span class="badge bg-success">Open</span>';
        }   
        
        elseif($status == "Hold"){
            echo '<span class="badge bg-warning">Hold</span>' ;     
        }

        elseif($status == "Closed"){
            echo '<span class="badge bg-secondary">Closed</span>';
        }
    }

}
