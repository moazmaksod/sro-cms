@extends('layouts.full')
@section('title', __('Fortress History'))

@section('content')
    <section class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('Fortress') }}</th>
                            <th>{{ __('Winner') }}</th>
                            <th>{{ __('Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                            <tr>
                                <td>
                                    <img src="{{ config('widgets.fortress_war')['names'][$row->FortressID]['image'] }}" alt="">
                                    {{ config('widgets.fortress_war')['names'][$row->FortressID]['name'] }}
                                </td>
                                <td>
                                    @if(!empty($row->strDesc))
                                        <a href="{{ route('ranking.guild.view', ['name' => $row->strDesc]) }}" class="text-decoration-none">{{ $row->strDesc }}</a>
                                    @else
                                        <span>{{ __('No Winner') }}</span>
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
