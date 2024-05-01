@extends('layout.app')

@section('title', 'Customers')

@section('content')
    {!!FB::open('', 'GET')!!}

<h1>Customers <a href="/customers/create" class="btn btn-primary">New Customer</a></h1>

    <!--
        table headers sortable
    -->
<table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">@sortablelink('id', 'ID')</th>
                <th scope="col">@sortablelink('name','Name')</th>
                <th scope="col">Address</th>
                <th scope="col">Status</th>
            </tr>
        </thead>

    <tbody>

    
        <b>Status:</b>
        <div style="text-align:right">
            
                <div class="row">
                    <div class="col-1">
                        {!! FB::select('status', '', $status_array) !!}
                    </div>
                    <br/>
                </div>
        
            <input class="btn btn-primary" type="submit" value="Filter">
        </div>
    {!!FB::close()!!}    
    </div>

    <!--
        displaying all customers and status and address
-->
    @foreach($customers as $customer)

    <tr>
        <td><a href="/customers/{{$customer->id}}">{{$customer->id}}</a></td>
        <td><a href="/customers/{{$customer->id}}">{{$customer->name}}</a></td>
        <td>{{$customer->address}}</td>

        <td> 
            @if($customer->status == 'Enabled')
                <span class="badge bg-success">Enabled</span>
            @endif    
            @if($customer->status == 'Disabled')
                <span class="badge bg-secondary">Disabled</span>       
            @endif
            
        </td>
        
    </tr>

    @endforeach

    </tbody>
</table>
<!--
    pagination
-->
<div class="mx-auto pb-10 w-4/5">
    {{$customers->links()}}
</div>

<hr/>

@endsection