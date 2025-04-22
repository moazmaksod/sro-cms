@extends('layouts.full')
@section('title', $data->title)

@section('content')
    <div class="container">
        {!! $data->content !!}
    </div>
@endsection
