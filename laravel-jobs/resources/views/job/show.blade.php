
@extends('layout.app')


@section('title', 'Job Title')
@section('content')
@include('layout.alert')


<div class="row">
    <h1>Job {{$job->id}} - {{$job->title}}  
        @if($job->status == 'Open')
        <span class="badge bg-success">Open</span>       
        @endif

        @if($job->status == 'Hold')
        <span class="badge bg-warning">Hold</span>       
        @endif

        @if($job->status == 'Closed')
        <span class="badge bg-secondary">Closed</span>       
        @endif
    </h1>



{!! FB::open('/jobs/'.$job->id) !!}
{!! FB::setErrors($errors) !!}
{!! FB::setInput($job) !!}
@csrf
@method('PUT')
<div class="row">
        
        <!--display job info -->
        
        <div class="col-md-3 bg-light">

                <h4>Job Details</h4>

                <b>Created:</b><br/>
                {{$job->created_at}}
                <br/>

                <b>Updated At:</b><br/>
                {{$job->updated_at}}
                <br/>

                <b>Closed At:</b>
                @if($job->status == 'Closed')
                    {{$job->closed_at}}
                @endif
                <br/>
            
                <b>Status:</b>
                {!! FB::select('status', '', $status_array) !!}
                <br/>

                <b>Assigned to:</b>
                {!! FB::select('user_id', '', $users_array) !!}

                <b>Customer:</b>
                {!! FB::select('customer_id', '', $customers_array) !!}
               
                <b>Notes</b>
                {!! FB::textarea('notes', '') !!}

                <b>Title</b>
                {!! FB::input('title', '') !!}
         
        </div>

        <div class="col-md-9">
            <!-- Comment Form -->
            <b>Comment</b>
                {!! FB::textarea('comments', '') !!}
            <input type="submit" class="form-control btn btn-primary" value="Update Job" />

            <!-- History -->
            @foreach($history as $h)
                <div class="col-md-12 md-2">
                    <div class="card @if($h->type == 'comment') bg-warning @endif">
                        <div class="card-header">
                            At {{$h->created_at}} User <b>{{$h->user->name}}</b>
                            @if($h->type == 'comment')Wrote:@else Changed @endif  <br/><hr>
                                <div class="card-body">
                                    {{$h->details}}<br/>
                                </div>
                        </div>
                    </div>
                    <br/>
                </div>
            @endforeach
        </div>
{!! FB::close() !!}

@endsection