@extends('layouts.full')
@section('title', $data->title)

@section('content')
    <section class="card">
        <div class="card-body">
            {!! $data->content !!}
        </div>
    </section>
@endsection
