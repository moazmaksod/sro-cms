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
                                <td>{{ $user->getMuUser->getEmailUser->EmailAddr }}</td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Silk') }}</th>
                                <td>{{ $user->getMuUser->getJCash()->Silk }}</td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Premium Silk') }}</th>
                                <td>{{ $user->getMuUser->getJCash()->PremiumSilk }}</td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Month Usage') }}</th>
                                <td>{{ $user->getMuUser->getJCash()->MonthUsage }}</td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('3Month Usage') }}</th>
                                <td>{{ $user->getMuUser->getJCash()->ThreeMonthUsage }}</td>
                            </tr>

                            <tr>
                                <th scope="row">VIP</th>
                                <td>
                                    @isset($user->getMuUser->getVipLevel->VIPUserType)
                                        <img src="{{ asset(config('global.ranking.vip_level.level')[$user->getMuUser->getVipLevel->VIPLv]['icon']) }}" width="24" height="24" alt="">
                                        <span>{{ config('global.ranking.vip_level.level')[$user->getMuUser->getVipLevel->VIPLv]['name'] }}</span>
                                    @else
                                        <span>{{ __('None') }}</span>
                                    @endisset
                                </td>
                            </tr>
                        </tbody>
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

                            <div class="row mb-3">
                                <label for="type" class="col-md-2 col-form-label text-md-end">{{ __('Type') }}</label>

                                <div class="col-md-10">
                                    <select class="form-select" name="type" aria-label="Default select example">
                                        <option value="1">Normal</option>
                                        <option value="3">Premium</option>
                                    </select>

                                    @error('category')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

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
