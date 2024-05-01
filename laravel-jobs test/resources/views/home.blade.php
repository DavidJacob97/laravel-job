@extends('layout.app')



@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>
              
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif 

                    {{ __('You are logged in as') }} {{$user}}                 
                </div>

                <h4> Enabled Users</h4>
                
                <div class="col-md-1">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $u)
                                <tr>
                                    <td> <em>{{$u->name}} </em></td>
                                    <td> <em>{{$u->id}} </em></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
       
        <br>
        <div class="row">
            
        </div>

            <br>
            <br>
            <br>
        </div>
    </div>

       
</div>
@endsection
