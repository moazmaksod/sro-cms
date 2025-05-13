<div class="card mb-4">
    <div class="card-body text-center">
        <p class="mb-0">{{ __('Server Time:') }} <span id="idTimerClock">{{ date('H:i:s') }}</span></p>
        <p>{{ __('Online Players:') }} {{ $onlinePlayer+$fakePlayer }} / {{ $maxPlayer }}</p>

        @php $progress = ceil(($onlinePlayer+$fakePlayer)*100/$maxPlayer); @endphp
        <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar w-{{ $progress }}"></div>
        </div>
    </div>
</div>
