@extends('admin.layouts.app')
@section('title', __('View User'))

@section('content')
    <div class="container-fluid py-3">
        <!-- Page Header and Success Message -->
        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
            <h1 class="h4 mb-0">View User</h1>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <!-- User Info and Characters Row -->
        <div class="row g-3 mb-3">
            <!-- User Details Card -->
            <div class="col-md-6">
                <div class="card" style="height: 300px; overflow-y: auto;">
                    <div class="card-header bg-primary text-white py-2">
                        <h6 class="mb-0">User Details</h6>
                    </div>
                    <div class="card-body py-2 px-2">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm mb-0">
                                <tbody>
                                    @if(config('global.server.version') === 'vSRO')
                                        <tr>
                                            <th scope="row">JID</th>
                                            <td>{{ $user->JID }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Username</th>
                                            <td>{{ $user->StrUserID }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Password</th>
                                            <td>
                                                <span id="password-display">********</span>
                                                <button id="edit-password-btn" class="btn btn-sm btn-secondary ms-1 py-0" onclick="togglePasswordEdit()">Change</button>
                                            </td>
                                        </tr>
                                        <tr id="password-edit-field" style="display: none;">
                                            <td colspan="2">
                                                <form method="POST" action="{{ route('admin.users.update.password', $user->JID) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="row g-1 mb-1">
                                                        <div class="col-6">
                                                            <input id="password" type="password" class="form-control form-control-sm @error('password') is-invalid @enderror" name="password" placeholder="New Password" required>
                                                            @error('password')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-4">
                                                            <input id="password_confirmation" type="password" class="form-control form-control-sm" name="password_confirmation" placeholder="Confirm" required>
                                                        </div>
                                                        <div class="col-2">
                                                            <button type="submit" class="btn btn-warning btn-sm w-100 py-0">Save</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Email</th>
                                            <td>
                                                <span id="current-email">{{ $user->Email }}</span>
                                                <button id="edit-email-btn" class="btn btn-sm btn-secondary ms-1 py-0" onclick="toggleEmailEdit()">Change</button>
                                            </td>
                                        </tr>
                                        <tr id="email-edit-field" style="display: none;">
                                            <td colspan="2">
                                                <form method="POST" action="{{ route('admin.users.update.email', $user->JID) }}" class="row g-1">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="col-8">
                                                        <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->Email) }}" required>
                                                        @error('email')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="col-4">
                                                        <button type="submit" class="btn btn-warning btn-sm w-100 py-0">Save</button>
                                                    </div>
                                                </form>
                                            </td>
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
                                        <tr>
                                            <th scope="row">{{ __('Reg. Date') }}</th>
                                            <td>{{ $user->regtime ?? '—' }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Last Login</th>
                                            <td>{{ $lastLogin ? \Carbon\Carbon::parse($lastLogin)->diffForHumans() : 'Unknown' }}</td>
                                        </tr>
                                    @else
                                        @php $cash = $user->muUser->getJCash() @endphp
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
                                            <td>
                                                <span id="current-email">{{ $user->muUser->muEmail->EmailAddr }}</span>
                                                <button id="edit-email-btn" class="btn btn-sm btn-secondary ms-1 py-0" onclick="toggleEmailEdit()">Change</button>
                                            </td>
                                        </tr>
                                        <tr id="email-edit-field" style="display: none;">
                                            <td colspan="2">
                                                <form method="POST" action="{{ route('admin.users.update.email', $user->JID) }}" class="row g-1">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="col-8">
                                                        <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->muUser->muEmail->EmailAddr) }}" required>
                                                        @error('email')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="col-4">
                                                        <button type="submit" class="btn btn-warning btn-sm w-100 py-0">Save</button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{ __('Silk') }}</th>
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
                                            <th scope="row">{{ __('3 Month Usage') }}</th>
                                            <td>{{ $cash->ThreeMonthUsage ?? 0 }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">VIP</th>
                                            <td>
                                                @isset($user->muUser->muVIPInfo->VIPUserType)
                                                    <span class="badge bg-success">{{ $vipLevel['level'][$user->muUser->muVIPInfo->VIPLv]['name'] }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ __('None') }}</span>
                                                @endisset
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Characters Card -->
            <div class="col-md-6">
                <div class="card" style="height: 300px; overflow-y: auto;">
                    <div class="card-header bg-primary text-white py-2">
                        <h6 class="mb-0">Characters</h6>
                    </div>
                    <div class="card-body py-2 px-2">
                        @if($characters->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-striped table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Jobname</th>
                                            <th>Level</th>
                                            <th>Gold</th>
                                            <th>Guild</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($characters->take(4) as $char)
                                            <tr>
                                                <td>{{ $char->CharID }}</td>
                                                <td>{{ $char->CharName16 }}</td>
                                                <td>{{ $char->NickName16 == '' ? 'No Job' : ($char->NickName16 ?? 'No Job') }}</td>
                                                <td>{{ $char->CurLevel }}</td>
                                                <td>{{ number_format($char->RemainGold) }}</td>
                                                <td>{{ $char->guild->Name == 'dummy' ? 'No Guild' : ($char->guild->Name ?? 'No Guild') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center text-muted mb-0">No characters found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Row -->
        <div class="row g-3 mb-3">
            <!-- Add Silk Card -->
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white py-2">
                        <h6 class="mb-0">Add Silk</h6>
                    </div>
                    <div class="card-body py-2 px-2">
                        <form method="POST" action="{{ route('admin.users.update', $user->JID) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-2">
                                <label for="amount" class="form-label mb-1">{{ __('Silk Amount') }}</label>
                                <input id="amount" type="number" class="form-control form-control-sm @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount') }}" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="type" class="form-label mb-1">{{ __('Type') }}</label>
                                @if(config('global.server.version') === 'vSRO')
                                    <select id="type" class="form-select form-select-sm @error('type') is-invalid @enderror" name="type" required>
                                        <option value="0" {{ old('type') == '0' ? 'selected' : '' }}>Normal</option>
                                        <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Gift</option>
                                        <option value="2" {{ old('type') == '2' ? 'selected' : '' }}>Point</option>
                                    </select>
                                @else
                                    <select id="type" class="form-select form-select-sm @error('type') is-invalid @enderror" name="type" required>
                                        <option value="0" {{ old('type') == '0' ? 'selected' : '' }}>Normal</option>
                                        <option value="3" {{ old('type') == '3' ? 'selected' : '' }}>Premium</option>
                                    </select>
                                @endif
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100">{{ __('Add') }}</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Block Account Panel -->
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white py-2">
                        <h6 class="mb-0">Ban Account</h6>
                    </div>
                    <div class="card-body py-2 px-2">

                        {{-- actually Ban notice --}}
                        @if($user->blockedUser && \Carbon\Carbon::parse($user->blockedUser->timeEnd)->isFuture())
                            <div class="alert alert-danger py-1 px-2 mb-2 small text-center">
                                <strong>Currently banned</strong><br>
                                Grund: <em>{{ $activePunishment->Guide }}</em><br>
                                {{ \Carbon\Carbon::parse($activePunishment->BlockStartTime)->format('d.m.Y H:i') }}
                                –
                                {{ \Carbon\Carbon::parse($activePunishment->BlockEndTime)->format('d.m.Y H:i') }}
                                ({{ \Carbon\Carbon::parse($activePunishment->BlockStartTime)->diffForHumans(\Carbon\Carbon::parse($activePunishment->BlockEndTime), true) }})
                            </div>
                        @endif

                        {{-- Ban --}}
                        <form method="POST" action="{{ route('admin.users.block', $user->JID) }}">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label mb-1">Reason</label>
                                <select name="reason" class="form-select form-select-sm" required onchange="toggleCustomReason(this)">
                                    <option value="Botting">Botting</option>
                                    <option value="Insults">Insults</option>
                                    <option value="Scamming">Scamming</option>
                                    <option value="Custom">Custom</option>
                                </select>
                            </div>
                            <div class="mb-2" id="custom-reason-field" style="display: none;">
                                <label class="form-label mb-1">Custom Reason</label>
                                <input type="text" name="custom_reason" class="form-control form-control-sm" placeholder="Custom">
                            </div>
                            <div class="mb-2">
                                <label class="form-label mb-1">Duration (Hour)</label>
                                <input type="number" name="duration" class="form-control form-control-sm" min="1" value="24" required>
                            </div>
                            <button type="submit" class="btn btn-danger btn-sm w-100">Ban Account</button>
                        </form>

                        {{-- Uunbaaaan --}}
                        @if($user->blockedUser && \Carbon\Carbon::parse($user->blockedUser->timeEnd)->isFuture())
                            <hr class="my-2">
                            <form method="POST" action="{{ route('admin.users.unban', $user->JID) }}" onsubmit="return confirm('Really unban?');">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success btn-sm w-100">Unban Account</button>
                            </form>
                        @endif

                    </div>
                </div>
            </div>



            <!-- Ban History Panel -->
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white py-2">
                        <h6 class="mb-0">Letzte Bans</h6>
                    </div>
                    <div class="card-body py-2 px-2">
                        @if($punishmentHistory->isNotEmpty())
                            <ul class="list-group list-group-sm">
                                @foreach($punishmentHistory as $punish)
                                    <li class="list-group-item px-2 py-1">
                                        <small><strong>Reason:</strong> {{ $punish->Guide }}</small><br>
                                        <small><strong>Duration:</strong>
                                            {{ \Carbon\Carbon::parse($punish->BlockStartTime)->format('d.m.Y H:i') }}
                                            –
                                            {{ \Carbon\Carbon::parse($punish->BlockEndTime)->format('d.m.Y H:i') }}
                                            ({{ \Carbon\Carbon::parse($punish->BlockStartTime)->diffForHumans(\Carbon\Carbon::parse($punish->BlockEndTime), true) }})
                                        </small>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted small text-center">No entrys found. Good User.</p>
                        @endif
                    </div>
                </div>
            </div>


            <!-- Last Silk Transactions -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0">Last Silk Transactions</h6>
                </div>
                <div class="card-body py-2 px-2">
                    <div class="table-responsive" style="max-height: 300px;">
                        <table class="table table-striped table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Method</th>
                                    <th>Transaction ID</th>
                                    <th>Status</th>
                                    <th>Amount (€)</th>
                                    <th>Value (Silk)</th>
                                    <th>Description</th>
                                    <th>IP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($donationLogs as $log)
                                    <tr>
                                        <td>{{ $log->created_at }}</td>
                                        <td>{{ $log->method }}</td>
                                        <td>{{ $log->transaction_id }}</td>
                                        <td>{{ $log->status }}</td>
                                        <td>{{ $log->amount }}</td>
                                        <td>{{ $log->value }}</td>
                                        <td>{{ $log->desc }}</td>
                                        <td>{{ $log->ip }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No transactions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('styles')
    <style>
        .card-header.bg-primary {
            border-bottom: 1px solid #003087;
        }
        .table-sm th, .table-sm td {
            padding: 0.2rem;
            font-size: 0.85rem;
        }
        .card-body p, .form-label {
            font-size: 0.9rem;
        }
        .btn-sm {
            font-size: 0.8rem;
        }
        .form-control-sm, .form-select-sm {
            font-size: 0.85rem;
            padding: 0.25rem 0.5rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function toggleEmailEdit() {
            const editField = document.getElementById('email-edit-field');
            const editBtn = document.getElementById('edit-email-btn');
            if (editField.style.display === 'none') {
                editField.style.display = 'table-row';
                editBtn.textContent = 'Cancel';
            } else {
                editField.style.display = 'none';
                editBtn.textContent = 'Change';
            }
        }

        function togglePasswordEdit() {
            const editField = document.getElementById('password-edit-field');
            const editBtn = document.getElementById('edit-password-btn');
            if (editField.style.display === 'none') {
                editField.style.display = 'table-row';
                editBtn.textContent = 'Cancel';
            } else {
                editField.style.display = 'none';
                editBtn.textContent = 'Change';
            }
        }
        function toggleCustomReason(select) {
            const field = document.getElementById('custom-reason-field');
            field.style.display = (select.value === 'Custom') ? 'block' : 'none';
        }
    </script>
@endpush
