@extends('layouts.full')
@section('title', __('Advanced Unique Tracker'))

@section('content')
    <div class="container">
        <div class="card-unique">
            <div class="card-body-unique">
                <div class="row">
                    @foreach($data as $key => $value)
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h3>
                                        <img src="{{ asset(config('global.ranking.unique_icons')[1]) }}" alt=""/>
                                        {{ $unique_list[$key]['name'] }}
                                    </h3>
                                    <small>{{ __('Last 5 Killers') }}</small>
                                </div>
                                <div class="card-body" style="min-height: 253px;">
                                    <table class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>{{ __('#') }}</th>
                                                <th>{{ __('Character') }}</th>
                                                <th>{{ __('Points') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <p class="text-center">{{ $unique_list[$key]['points'] }} {{ __('Points') }}</p>
                                            @php $i = 1 @endphp
                                            @foreach($data[$key] as $values)
                                                @if($i > 5) @break @endif
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $values->CharName16 }}</td>
                                                    @if(array_key_exists($values->CharName16, $data_rank))
                                                        @foreach($data_rank[$values->CharName16] as $value_char)
                                                            <td>{{ $value_char->Points }}</td>
                                                        @endforeach
                                                    @else
                                                        <td>{{ $unique_list[$key]['points'] }}</td>
                                                    @endif
                                                </tr>
                                                @php $i++ @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
