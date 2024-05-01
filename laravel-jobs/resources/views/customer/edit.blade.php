@extends('layout.app')

@section('title', 'Edit Customer')

@section('content')
    <h1>Edit Customer {{$customer->name}}</h1>

    {!! FB::open('/customers/'.$customer->id) !!}
    {!! FB::setErrors($errors) !!}
    {!! FB::setInput($customer) !!}
    @csrf
    @method('PUT')
<div class="row">
       
    {!! FB::input('name', 'Name') !!}
        <br/>
   
    {!!  FB::textarea('address', 'Address') !!}
        <br/>
    
    {!!  FB::textarea('notes', 'Notes') !!}
        <br/>
       
          
            {!! FB::select('status', 'Status', $status_array) !!}
    <br/>
       
            <br/>
            {!! FB::submit('Save') !!}
            <br/>
            {!! FB::close() !!}
      
</div>
@endsection
     