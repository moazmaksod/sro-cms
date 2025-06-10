@extends('layouts.app')
@section('title', __('Invites'))

@section('sidebar')
    @include('profile.sidebar')
@stop

@section('content')
    <div class="container">
        <div class="card p-3">
            <div class="card-body">
                @admin
                    <div class="mb-3">
                        <label for="inviteLink" class="form-label">GM Invite Link</label>
                        <div class="input-group">
                            <input type="text" id="inviteLink" class="form-control" value="{{ url('/register?invite=ELITEPVPERS') }}" readonly>
                            <button class="btn btn-outline-primary" type="button" onclick="copyInviteLink()">Copy</button>
                        </div>
                    </div>
                @endadmin

                @if ($invite)
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="mb-3">
                        <label for="inviteLink" class="form-label">Your Invite Link</label>
                        <div class="input-group">
                            <input type="text" id="inviteLink" class="form-control" value="{{ url('/register?invite=' . $invite->code) }}" readonly>
                            <button class="btn btn-outline-primary" type="button" onclick="copyInviteLink()">Copy</button>
                        </div>
                    </div>

                    <div class="card d-flex justify-content-between align-items-center mb-3 mt-3 p-3">
                        <h5 class="mb-0">Total Invite Points: <span class="">{{ $totalPoints }}</span></h5>
                        <p class="mt-0 text-muted">Minimum 25 points to redeem</p>
                        @if ($totalPoints >= 25)
                            <form method="POST" action="{{ route('profile.invites-redeem') }}">
                                @csrf
                                <button class="btn btn-primary">Redeem Points</button>
                            </form>
                        @else
                            <button class="btn btn-secondary" disabled>Redeem Points</button>
                        @endif
                    </div>

                    <div class="mt-5">
                        <h5>Invited Users ({{ $usedInvites->count() }})</h5>
                        @if ($usedInvites->isNotEmpty())
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Registered At</th>
                                    <th>Points</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($usedInvites as $index => $inviteLog)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $inviteLog->invitedUser->username ?? 'Unknown' }}</td>
                                        <td>{{ $inviteLog->invitedUser->created_at->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                        <td>{{ $inviteLog->points }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info">No invited users yet.</div>
                        @endif
                    </div>

                    <script>
                        function copyInviteLink() {
                            const input = document.getElementById("inviteLink");
                            input.select();
                            input.setSelectionRange(0, 99999);
                            document.execCommand("copy");
                            alert("Invite link copied to clipboard!");
                        }
                    </script>
                @else
                    <div class="alert alert-warning">
                        No invite code found for this user.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
