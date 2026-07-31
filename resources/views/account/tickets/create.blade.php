@extends('layouts.app')
@section('title', __('New Ticket'))

@section('sidebar')
    @include('account.sidebar')
@stop

@section('content')
    <section class="card">
        <div class="card-body">
            <h3>New Ticket</h3>

            <form action="{{ route('account.ticket.send') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Subject</label>
                    <input type="text" name="subject" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-control" required>
                        @foreach($data as $key => $row)
                            <option value="{{ $key }}">{{ $row }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea name="message" class="form-control" rows="4" required></textarea>
                </div>

                <button class="btn btn-primary">Create Ticket</button>
            </form>
        </div>
    </section>
@endsection
@push('styles')

@endpush
@push('scripts')

@endpush
