@extends('admin.layouts.app')
@section('title', __('Vote'))

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Vote</h1>

            {{--
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="{{ route('admin.votes.create') }}" class="btn btn-sm btn-outline-secondary">Add New Vote</a>
                </div>
            </div>
            --}}
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive small">
            <table class="table table-striped table-sm">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Reward</th>
                    <th>Timeout (hrs)</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($data as $value)
                    <tr>
                        <td>{{ $value->title }}</td>
                        <td>{{ $value->reward }}</td>
                        <td>{{ $value->timeout }}</td>
                        <td>
                            @if($value->active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.votes.edit', $value->id) }}" class="btn btn-primary btn-sm">Edit</a>

                            {{--
                            <form action="{{ route('admin.votes.destroy', $value->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            --}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">No Records Found!</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
