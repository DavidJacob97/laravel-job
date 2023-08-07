

@extends('layout.app')

@section('title', 'Show Customer')

@section('content')
@include('layout.alert')
    <h1>Customer {{$customer->name}}</h1> <a href="/customers/{{$customer->id}}/edit" class="btn btn-primary">Edit Customer</a>   
    <div style="text-align:center">
        <h5>Jobs</h5>
    </div>

<div class="row">
       
    <div class="col-md-3 bg-light">

        <h4><em>Customer Details</em></h4>

        <b>Address:</b><br/>
        {{$customer->address}}
        <br/>

        <b>Notes:</b><br/>
        {{$customer->notes}}
        <br/>

        <b>Status:</b><br/>
        {{$customer->status}}
        <br/>

        <b>Created:</b><br/>
        {{$customer->created_at}}
        <br/>

        <b>Updated At:</b><br/>
        {{$customer->updated_at}}
        <br/>

    </div>

    <div class="col-md-9">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Title</th>        
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jobs as $job)
                    <tr>
                        <td><a href="/jobs/{{$job->id}}">{{$job->id}}</a></td>
                        <td><a href="/jobs/{{$job->id}}">{{$job->title}}</a></td>
                        <td>
                        @php 
                            $status = app\Http\Controllers\CustomerController::displayStatus($job->status);
                        @endphp
                        </td>
                    </tr>
                        
                @endforeach
    </div>

    </table>
        <div class="mx-auto pb-10 w-4/5">
            {{$jobs->links()}}
        </div>
</div>
@endsection
     