@extends('layouts.full')
@section('title', __('Global History'))

@section('content')
    <section class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('Message') }}</th>
                            <th>{{ __('Character') }}</th>
                            <th>{{ __('Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                            <tr>
                                <td>{{ $row->Comment }}</td>
                                <td>
                                    @if(!empty($row->CharName))
                                        <a href="{{ route('ranking.character.view', ['name' => $row->CharName]) }}" class="text-decoration-none">{{ $row->CharName }}</a>
                                    @else
                                        <span>{{ __('NoName') }}</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::make($row->EventTime)->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">{{ __('No Records Found!') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
