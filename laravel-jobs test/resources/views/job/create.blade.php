@extends('layout.app')

@section('title', 'Create New Job')

@section('content')
    <h1>Create New Job</h1>
    {!! FB::open('/jobs') !!}
        {!!  FB::setErrors($errors) !!}
        
        @csrf

        {!! FB::input('title', 'Title') !!}

          <br/>

        {!!  FB::textarea('notes', 'Notes') !!}

        
        {!! FB::submit('Save') !!}

    {!! FB::close() !!}
@endsection
     



