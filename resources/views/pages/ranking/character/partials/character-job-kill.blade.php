@if(config('ranking.extra.character_job_kill', false))
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>{{ __('Killer') }}</th>
                <th>{{ __('Dead') }}</th>
                <th>{{ __('Time') }}</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>
@endif
