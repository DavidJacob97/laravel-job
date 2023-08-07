<?php use App\Classes\helpers;
?>
@extends('layout.app')

@section('title', 'Jobs')
                  

@section('content')
    {!!FB::open('', 'GET')!!}
    {!!FB::setInput(['status' => $status,'customer_id' => $customer_id])!!}
        <h1>{{$title}} <a href="/jobs/create" class="btn btn-primary">New Job</a></h1>

   
    <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">@sortablelink('id', 'ID')</th>
                    <th scope="col">@sortablelink('title', 'Title')</th>
                    <th scope="col">Customer</th>
                    <th scope="col">User</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
        <tbody>
            <div>
                <div class="row">  
                    <div class="col-md-2">
                       Status:
                        {!! FB::select('status', '', $status_array) !!}
                
                    </div>

                    <div class="col-md-2" >
                        Customer:
                        {!! FB::select('customer_id', '', $customers_array) !!}
                        <br/>
                        
                    </div>

                    <div style="text-align:right">
                        <input type ="hidden" name="all" value= {{$all}}>  
                        <input class="btn btn-primary" type="submit" value="Filter">
                    </div>
                    {!!FB::close()!!}    
                </div>
            </div>
            @foreach($jobs as $job)

                <tr>
        
                    <td><a href="/jobs/{{$job->id}}">{{$job->id}}</a></td>
                    <td><a href="/jobs/{{$job->id}}">{{$job->title}}</a></td>
                    <td>
                        @if( $job->customer_id >=1)
                            {{$job->customer->name}}
                            
                        @else  
                            <em>None</em>
                        @endif
                    </td>
                    <td>
                    @if($job->user_id >= 1)
                        {{$job->user->name}}

                    @else
                        <em>Unassigned</em>
                    @endif

                    </td>
                    <td>
                        @php 
                            $status = app\Http\Controllers\JobController::displayStatus($job->status);
                        @endphp
                    
                    </td>

                </tr>

            @endforeach

        </tbody>
    </table>

        {{$jobs->appends(request()->all())->links()}}
    <hr/>

@endsection
