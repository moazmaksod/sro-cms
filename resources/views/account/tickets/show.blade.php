@extends('layouts.app')
@section('title', __('Show Ticket'))

@section('sidebar')
    @include('account.sidebar')
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <a href="{{ route('account.tickets') }}" class="btn btn-secondary mb-3">Back to Tickets</a>

            <h4>{{ $data->subject }}</h4>

            <div class="card mb-2">
                <div class="card-body">
                    <strong>You</strong>
                    <p>{{ $data->message }}</p>
                    <small class="text-muted">{{ $data->created_at }}</small>
                </div>
            </div>

            @foreach($replies as $row)
                <div class="card mb-2 {{ $row->type === 'admin' ? 'text-end' : 'text-start' }}">
                    <div class="card-body">
                        <strong>{{ $row->type === 'admin' ? 'Admin' : 'You' }}</strong>
                        <p>{{ $row->message }}</p>
                        <small class="text-muted">{{ $row->created_at }}</small>
                    </div>
                </div>
            @endforeach

            @if($data->status)
                <form action="{{ route('account.ticket.send', $data) }}" method="POST" class="mt-3">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $data->id }}">

                    <div class="mb-3">
                        <textarea name="message" class="form-control" rows="3" required placeholder="Write your reply..."></textarea>
                    </div>
                    <button class="btn btn-primary">Send Reply</button>
                </form>
            @else
                <div class="alert alert-warning mt-3">Ticket is closed</div>
            @endif
        </div>
    </div>
@endsection
@push('styles')

@endpush
@push('scripts')

@endpush
