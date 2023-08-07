<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Http\Controllers\NotificationController;

class PurgeNotif extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purge-notif';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes read notifiactions older than 30 days';

    /**
     * Execute the console command.
     */
    public function handle(){
        $allNotifications = Notification::all();

        $cutoff = strtotime("-1 days");
             
        $formatted = date("Y-m-d H:i:s", $cutoff);
     
        foreach($allNotifications as $n){
           if($n->read_at < $formatted){
              $n->delete();
            }
        }
    }
}
