@extends('admin.layouts.app')
@section('title', __('Donate Logs'))

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Donate Logs</h1>
        </div>

        <div class="table-responsive small">
            <table class="table table-striped table-sm">
                <thead>
                <tr>
                    <th scope="col">Method</th>
                    <th scope="col">Transaction ID</th>
                    <th scope="col">Username</th>
                    <th scope="col">User IP</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Silk</th>
                    <th scope="col">Date</th>
                    <th scope="col">Status</th>
                </tr>
                </thead>
                <tbody>
                @forelse($data as $value)
                    <tr>
                        <td>{{ $value->method }}</td>
                        <td>{{ $value->transaction_id }}</td>
                        <td>{{ $value->user->username }}</td>
                        <td>{{ $value->ip }}</td>
                        <td>{{ $value->amount }}</td>
                        <td>{{ $value->value }}</td>
                        <td>{{ $value->updated_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            @if($value->status)
                                <span class="text-success">Success<span>
                            @else
                                <span class="text-danger">Failed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No Records Found!</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
