@extends('layouts.app')
@section('title', __('Donate History'))

@section('sidebar')
    @include('account.sidebar')
@stop

@section('content')
    <section class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ __('ItemName') }}</th>
                        <th>{{ __('Remained Silk') }}</th>
                        <th>{{ __('Changed Silk') }}</th>
                        <th>{{ __('Silk Type') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Status') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($data as $row)
                        <tr>
                            <td>
                                @if(config('global.server.version') === 'vSRO')
                                    <span class="">{{ $row->OrderNumber }}</span>
                                @else
                                    @if($row->PTInvoiceID)
                                        <img src="{{ asset('in-game/webmall/images/'.$row->CPItemCode.'.jpg') }}" alt="" width="32" height="32" class="">
                                        {{ $row->CPItemName }}
                                    @elseif($row->ChangedSilk == 0 && $row->RemainedSilk > 0)
                                        <span class="">{{ __('Website') }}</span>
                                    @else
                                        <span class="">{{ __('NoName') }}</span>
                                    @endif
                                @endif
                            </td>
                            <td style="color: orange">{{ $row->RemainedSilk ?? $row->Silk_Offset }}</td>
                            <td style="color: orangered">{{ $row->ChangedSilk ?? $row->Silk_Remain }}</td>
                            <td>{{ ($row->SilkType == 3) ? __('Premium') : __('Normal') }}</td>
                            <td>
                                @if(config('global.server.version') === 'vSRO')
                                    {{ \Carbon\Carbon::make($row->RegDate)->diffForHumans() }}
                                @else
                                    {{ \Carbon\Carbon::make($row->ChangeDate)->diffForHumans() }}
                                @endif
                            </td>
                            <td>{{ ($row->AvailableStatus == 'Y') ? __('Available') : __('Not Available') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('No Records Found!') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                {{ $data->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </section>
@endsection
