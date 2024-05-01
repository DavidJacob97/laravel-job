<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Job;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller{   
    //view list of customers
    public function index(){
        //(1) Get customers
        $customers = Customer::query();
       
        //(2) Check for filters

        $status_array = [
            '' => 'Both',
            'Enabled' => 'Enabled',
            'Disabled' => 'Disabled',
        ];
        //status
        $status = null;
        if((request()->get('status', null)) != null){
            $status = request()->get('status');
            if( in_array($status, $status_array)){
                $customers = $customers->where('status', $status);
              }
        }

        //get query
        $customers = $customers->sortable('id')->simplePaginate(10);
        //(3) Pass customers to view
        return view('customer.index')->with('customers', $customers)
                                     ->with('status', $status)
                                     ->with('status_array', $status_array);
    }
    
    //show customer
    public function show($id){
       
        // (1) find customer by id
        $customer = Customer::query()->findOrFail($id);
        
        $jobs = $customer->jobs()->orderBy('updated_at', 'DESC')->simplePaginate(5);

        // (2) view the customer 
        return view('customer.show')
                ->with('customer', $customer)
                ->with('jobs', $jobs);
    }

    public function create(){
        //(1) Show form
        return view('customer.create');          
    }

    public function store(Request $request){
       
        //(1) Validate

        $valid = $request->validate([
            'name' => 'required|unique:customers|min:4|max:128',
            'notes' => 'nullable|min:5',
            'address' => 'nullable|min:7',
        ]);
 
        //(2) Create Customer by using data passed in by form through request
        $customer = new Customer();
        
        $customer->name = $valid['name'];
        $customer->notes = $valid['notes'];
        $customer->address = $valid['address'];

        $customer->save();
        
        //(3) Create Notification
        $this->notifyEnabledUsers('Customer Created: '.$customer->id. ' '.$customer->name,'/customers/'.$customer->id );

        //(4) Redirect to new customer
        return redirect('/customers/'.$customer->id)->with('success', 'New Customer Created');
    }

    //update a customer
    public function update(Request $request, $id){

        //(1) find customer by id
        $customer = Customer::findOrFail($id);

        //(2) validate
        //own rule to ignore current customer id
        $valid = $request->validate([
            'name' => 'required|min:4|max:128|unique:customers,name,'.$id,
            'notes' => 'nullable|min:5',
            'address' => 'nullable|min:7',
            'status' => 'required|in:Enabled,Disabled',
        ]);

        //(3) update customer with new data
        // check if changed for notifications
        $change = false;
        foreach ($valid as $k => $v) {
            if ($customer->{$k} != $v) {
                $customer->{$k} = $v;
                $change = true;
            }
        }
        $customer->save();

        //(5) Create Notification
        if($change){
            $this->notifyEnabledUsers('Customer Updated: '.$customer->id,'/customers/'.$customer->id);
            return redirect('/customers/'.$customer->id)->with('success', 'Customer Updated');
        }  
       
        //(6) redirect to updated customer
        return redirect('/customers/'.$customer->id);
    }

    //show edit form
    public function edit($id){
        //(1) find or fail
        $customer = Customer::findOrFail($id);

        $status_array = [  
            
            'Enabled' => 'Enabled',
            'Disabled' => 'Disabled'
         ];

        //(2) return the view of editing a specified customer
        return view('customer.edit')
                    ->with('customer', $customer)
                    ->with('status_array', $status_array);   
    }
}
