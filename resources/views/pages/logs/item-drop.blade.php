@extends('layouts.full')
@section('title', __('Sox Drop'))

@section('content')
    <section class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ __('ItemName') }}</th>
                        <th>{{ __('Degree') }}</th>
                        <th>{{ __('MobName') }}</th>
                        <th>{{ __('Character') }}</th>
                        <th>{{ __('Date') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($data as $row)
                        <tr>
                            <td>
                                <img src="{{ asset('images/sro/'.$row->AssocFileIcon128.'.png') }}" alt="" width="32" height="32" class="">
                                {{ $row->RealName ?? $row->ENG }}
                            </td>
                            <td>
                                {{ $row->Degree }} degrees
                            </td>
                            <td>
                                <!-- TODO: getting mob real name-->
                                {{ $row->MobCode }}
                            </td>
                            <td>
                                @if(!empty($row->CharName16))
                                    <a href="{{ route('ranking.character.view', ['name' => $row->CharName16]) }}" class="text-decoration-none">{{ $row->CharName16 }}</a>
                                @else
                                    <span>{{ __('NoName') }}</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::make($row->EventTime)->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">{{ __('No Records Found!') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
