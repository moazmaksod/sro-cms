@isset ($eventScheduleConfig['enabled'])
    <div class="card mb-4">
        <div class="card-header">
            {{ __('Event Schedule') }}
        </div>
        <div class="card-body">
            <ul class="list-unstyled">
                @php $i = 0; @endphp
                @foreach($eventSchedule as $key => $value)
                    @if(is_null($value)) @continue @endif
                    <li>
                        <span>{{ $eventScheduleConfig['names'][$key] }}</span>
                        <span class="float-end">
                            @if($value['status'])
                                <span class="text-success">{{ __('Active') }}</span>
                            @else
                                <span class="timerCountdown" id="idTimeCountdown_{{ $i }}" data-time="{{ $value['start'] }}"></span>
                            @endif
                        </span>
                    </li>
                    @php $i++; @endphp
                @endforeach
            </ul>
        </div>
    </div>
@endisset
