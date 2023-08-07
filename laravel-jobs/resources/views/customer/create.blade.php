@extends('layout.app')

@section('title', 'Create New Customer')

@section('content')
    <h1>Create New Customer</h1>
    {!! FB::open('/customers') !!}
        {!!  FB::setErrors($errors) !!}
        
        @csrf

        {!! FB::input('name', 'Name') !!}
        <br/>

        {!!  FB::textarea('address', 'Address') !!}
        <br/>

        {!!  FB::textarea('notes', 'Notes') !!}
        
        {!! FB::submit('Save') !!}

    {!! FB::close() !!}
@endsection
     