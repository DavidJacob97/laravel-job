@extends('layout.app')

@section('title', 'Notifications')

@section('content')
<div class="row">
    <h1>Notifications</h1>    
</div>

    <div style="text-align:right">
        <a class="btn btn-primary" href="/notifications/readAll">Mark all as Read</a>
    </div>

<table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Message</th>
                <th scope="col">Created At</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
    <tbody>
         
    @foreach($notifications as $n)

    <tr>
        <td>{{$n->message}}</td>
        <td><em>{{$n->created_at}}</em></td>
        <td>
        @if($n->read_at == null)
            <a class="btn btn-secondary" href="/notifications/read/{{$n->id}}">Unread</a></td>
        @else                                                     
            <a class="btn btn-primary" href="/notifications/read/{{$n->id}}">Read</a></td>
        @endif
    </tr>

    @endforeach

    </tbody>
</table>
    
{{$notifications->links()}}
<hr/>


@endsection
