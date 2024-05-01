<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Job;
use App\Models\User;
use App\Models\Customer;
use App\Models\History;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class JobController extends Controller{

    public function index(){

        // customers array adding none
        $customers_array =  Customer::query()->where('status', 'Enabled')->pluck('name', 'id')->toArray();

        $customers_array = [0=>'None', null => 'All'] + $customers_array;

        //status array
         $status_array = [
            '' => 'All',
            'Open' => 'Open',
            'Hold' => 'Hold',
            'Closed' => 'Closed'];

        //start query
        $jobs = Job::query()->with('customer')->with('user');
        
        $all = request()->get('all');

         // My jobs by currently logged in user
        if($all == 0){
            $title = 'My Jobs';
            $jobs = $jobs->where('user_id', Auth::user()->id);                   
        }

        if($all == 1){ 
            $title = 'All Jobs';
        }
        
         // check status filter and if Open Hold Closed
         $status = null;
         if((request()->get('status', null)) != null ){
            $status = request()->get('status');
            if( in_array($status, $status_array)){
                $jobs = $jobs->where('status', $status);
            }
         }
         
         //check customer filter and if exists in customer array
         $customer_id = null;
         if((request()->get('customer_id', null)) != null ){
            $customer_id = request()->get('customer_id');

           if( array_key_exists($customer_id, $customers_array)){
             $jobs = $jobs->where('customer_id', $customer_id);
           }
         }

         //(3) get query
        $jobs = $jobs->sortable('id')->simplePaginate(10);
        
        return view('job.index')
                                ->with('jobs', $jobs)
                                ->with('all', $all)
                                ->with('title', $title)
                                ->with('status', $status)
                                ->with('customer_id', $customer_id)
                                ->with('status_array', $status_array)
                                ->with('customers_array', $customers_array);
    }

    public function show($id){
    
        // (1) FindOrFail
       $job = Job::with('history.user')->findOrFail($id);

        // (2) Get all enabled users id => name
       $users_array = User::query()->where('status','Enabled')->pluck('name', 'id')->toArray();
       
       $users_array = [0=>'Unassigned'] + $users_array;
        
       // (3) Get all enabled customers
       $customers_array =  Customer::query()->where('status', 'Enabled')->pluck('name', 'id')->toArray();

       $customers_array = [0=>'None']+ $customers_array;  

        //status options
        $status_array = [
            
            'Open' => 'Open',
            'Hold' => 'Hold',
            'Closed' => 'Closed'
        ];
        //view history at top Error attept to read property bool in job view
        $history = History::where('job_id', $job->id)->orderby('created_at', 'Desc')->get();
        
        // (3) View job.show - views/job/show.blade.php
        return view('job.show')
            ->with('job', $job)
            ->with('users_array', $users_array)
            ->with('customers_array', $customers_array)
            ->with('status_array', $status_array)
            ->with('history', $history);
    }
   
    public function create(){

        //(1) view form
        return view('job.create');
    }
   
    public function store(Request $request){

        //(1) validate data
        $valid = $request->validate([
            'title' => 'required|min:4|max:128',
            'notes' => 'nullable|min:2',
        
        ]);

        //(2) Create Job by using data passed in by form through request
        $job = new Job();
        
        $job->title = $valid['title'];
        $job->notes = $valid['notes'];

        //job made in db (->save stores and updates)
        $job->save();

        //(3) Create entry for history for 
        $history = new History();
        $history->job_id = $job->id;
        $history->user_id = Auth::user()->id;
        $history->type = 'history';

        //initializing details of tilte and notes
        $changes = ' Set Title to '.$job->title . '\n';
        $changes .= ' Set Notes to '.$job->notes . '\n';
       
        $history->details = $changes;

        //history made in database
        $history->save();
        
        //(4) Create Notification
        $this->notifyEnabledUsers('Job Created: '.$job->id.' '.$job->title,'/jobs/'.$job->id );
        
        //(5) Redirect to new job
        return redirect('/jobs/'.$job->id)->with('success', 'New Job Created');    
    }

    public function update(Request $request, $id){

        // (1) Find or Fail by id
        $job = Job::findOrFail($id);
       
        // (2) validate input
        //case for not checking user id and customer id when not equal to null or 0
        $rules = [

            'title' => 'required|min:4|max:128',
            'notes' => 'nullable|min:2',
            'user_id' =>'nullable|numeric',
            'customer_id' => 'nullable|numeric',
            'comments' => 'nullable|min:5',
            'status' => 'required|in:Open,Hold,Closed'
        ];

        // Customer ID?
        if ($request->post('customer_id', null) >= 1) {
            // Update rule to check customer exists
            $rules['customer_id'] = 'nullable|numeric|exists:customers,id';
        }        

        // User ID?
        if ($request->post('user_id', null) >= 1) {
            // Update rule to check user exists
            $rules['user_id'] = 'nullable|numeric|exists:users,id';
        }        

        $valid = $request->validate($rules);

        // initialise log
        $log = '';
        // Loop through valid input
        foreach ($valid as $k => $v) {
            // Check if change
            if($k != 'comments'){
                if ($job->{$k} != $v) {
                    // closed case
                    if($v == 'Closed'){
                        $job->closed_at = NOW();
                    }
                    // customer case
                    if($k == 'customer_id'){
                        if($v == "0"){
                            $log .= "Set the customer to None\n";
                        }else{
                            $customer = Customer::find($v);
                            $log .= "Set customer to {$customer->name}\n";
                        }
                    }
                    // user id case
                    if($k == 'user_id'){
                        if($v >= 1){
                            $user = User::find($v);
                            $log .= "Assigned job to {$user->name} \n ";
                        
                        }else{
                            $log .= "Set the job to Unassigned\n";
                        }
                    }
                    //all else (title, notes, status and comment)
                    else{
                        if($k != 'customer_id'){
                            $log .= "Set {$k} to {$v}\n ";
                        }
                    }
                    $job->{$k} = $v;
                }    
            }
        }
        //update jobs
        $job->save();
        
        //if history changed made
        $newHistory = false;
        if($log != ''){
            $history = new History();  
            $history->job_id = $job->id;
            //user logged in is assigned           
            $history->user_id = Auth::user()->id;
            $history->type = 'history';
            $history->details = $log;
            //changes added to details
            $newHistory = true;
            $history->save();
        }

        // if a comment has been made make a history entry as type comment
        $newComment = false;
        if($valid['comments'] != ''){
            $history = new History();  //addHistory 
            $history->job_id = $job->id;
            //user id is the logged user
            $history->user_id = Auth::user()->id;
            $history->type = 'comment';
            $log = $valid['comments'];
            //changes added history
            $history->details = $log;
            $newComment = true;
            $history->save();
        }

        // (7) Create  notification
        // notify assigned user if not currently logged in, if unassigned, notify all enabled users

        if($newHistory or $newComment){
            if($job->user_id == null or $job->user_id == 0){
                $this->notifyEnabledUsers('Job Updated: '.$job->id. ' '.$job->title,'/jobs/'.$job->id);
                return redirect('/jobs/'.$job->id)->with('success', 'New Job Updated');
            }else{
                if($job->user_id != Auth::user()->id){
                    $this->notifyUser($job->user_id, 'Job Updated: '.$job->id.' '.$job->title,'/jobs/'.$job->id);
                    return redirect('/jobs/'.$job->id)->with('success', 'New Job Updated');
                }
            }
        }
        // (8) redirect to updated job
        return redirect('/jobs/'.$job->id);
    }
}
