@extends('admin.layouts.app')
@section('title', __('View User'))

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">View User</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="table-responsive">
                    <table class="table table-striped">
                        @if(config('global.server.version') === 'vSRO')
                            <tbody>
                                <tr>
                                    <th scope="row">JID</th>
                                    <td>{{ $user->JID }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Username</th>
                                    <td>{{ $user->StrUserID }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Email</th>
                                    <td>{{ $user->Email }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">{{ __('Silk') }}</th>
                                    <td>{{ $user->getSkSilk->silk_own ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">{{ __('Gift Silk') }}</th>
                                    <td>{{ $user->getSkSilk->silk_gift ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">{{ __('Point Silk') }}</th>
                                    <td>{{ $user->getSkSilk->silk_point ?? 0 }}</td>
                                </tr>
                            </tbody>
                        @else
                            <tbody>
                                <tr>
                                    <th scope="row">Portal JID</th>
                                    <td>{{ $user->PortalJID }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Username</th>
                                    <td>{{ $user->StrUserID }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Email</th>
                                    <td>{{ $user->muUser->muEmail->EmailAddr }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">{{ __('Silk') }}</th>
                                    @php $cash = $user->muUser->getJCash() @endphp
                                    <td>{{ $cash->Silk ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">{{ __('Premium Silk') }}</th>
                                    <td>{{ $cash->PremiumSilk ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">{{ __('Month Usage') }}</th>
                                    <td>{{ $cash->MonthUsage ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">{{ __('3Month Usage') }}</th>
                                    <td>{{ $cash->ThreeMonthUsage ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">VIP</th>
                                    <td>
                                        @isset($user->muUser->muVIPInfo->VIPUserType)
                                            <img src="{{ asset($vipLevel['level'][$user->muUser->muVIPInfo->VIPLv]['image']) }}" width="24" height="24" alt="">
                                            <span>{{ $vipLevel['level'][$user->muUser->muVIPInfo->VIPLv]['name'] }}</span>
                                        @else
                                            <span>{{ __('None') }}</span>
                                        @endisset
                                    </td>
                                </tr>
                            </tbody>
                        @endif
                    </table>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card p-3">
                    <div class="card-body">
                        <h4 class="text-center">Add Silk</h4>
                        <form method="POST" action="{{ route('admin.users.update', $user->JID) }}">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label for="amount" class="col-md-2 col-form-label text-md-end">{{ __('Silk Amount') }}</label>

                                <div class="col-md-10">
                                    <input id="amount" type="number" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount') }}" required>

                                    @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            @if(config('global.server.version') === 'vSRO')
                                <div class="row mb-3">
                                    <label for="type" class="col-md-2 col-form-label text-md-end">{{ __('Type') }}</label>

                                    <div class="col-md-10">
                                        <select class="form-select" name="type" aria-label="Default select example">
                                            <option value="0">Normal</option>
                                            <option value="1">Gift</option>
                                            <option value="2">Point</option>
                                        </select>

                                        @error('category')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>
                            @else
                                <div class="row mb-3">
                                    <label for="type" class="col-md-2 col-form-label text-md-end">{{ __('Type') }}</label>

                                    <div class="col-md-10">
                                        <select class="form-select" name="type" aria-label="Default select example">
                                            <option value="0">Normal</option>
                                            <option value="3">Premium</option>
                                        </select>

                                        @error('category')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            <div class="row mb-0">
                                <div class="col-md-10 offset-md-2">
                                    <button type="submit" class="btn btn-primary">{{ __('Add') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')

@endpush
@push('scripts')

@endpush
