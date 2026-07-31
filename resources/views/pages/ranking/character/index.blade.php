@extends('layouts.full')
@section('title', __('Character') . ' - ' .$data->CharName16)

@section('content')
    <section class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    @if(config('global.server.version') === 'vSRO')
                        <img class="d-inline-block align-middle object-fit-cover rounded border" src="{{ asset('images/character/'.config('ranking.character_image_vsro')[$data->RefObjID]) }}" width="100" height="100" alt=""/>
                    @else
                        <img class="d-inline-block align-middle object-fit-cover rounded border" src="{{ asset('images/character/'.config('ranking.character_image')[$data->RefObjID]) }}" width="100" height="100" alt=""/>
                    @endif

                    <div class="d-inline-block align-middle">
                        <h3 class="m-0">{{ $data->CharName16 }}</h3>
                        <p class="m-0">{{ __('Item Points:') }} <span>{{ $data->ItemPoint }}</span></p>

                        @if(config('ranking.extra.character_build', false) && $data->buildInfo)
                        <ul class="list-unstyled d-flex mb-0">
                            @foreach($data->buildInfo as $key => $row)
                                <li class="me-1">
                                    @if(isset(config('ranking.skill_mastery')[$row->MasteryID]))
                                        <span>{{ config('ranking.skill_mastery')[$row->MasteryID]['name'] }}</span> @if($key < count($data->buildInfo) - 1) / @endif
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        @endif
                        @if(config('ranking.extra.character_buff', false) && $data->buffInfo)
                        <ul class="list-unstyled d-flex mb-0">
                            @foreach($data->buffInfo as $row)
                                <li class="me-1">
                                    <img src="{{ asset('images/sro/'.$row->UI_IconFile_PNG) }}" title="{{ $row->UI_SkillName }}" alt="" width="24" height="24">
                                </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row mt-5">
                        @if(config('ranking.extra.character_job', false) && $data->charJob->JobType)
                            <div class="col">
                                @if(config('global.server.version') === 'vSRO')
                                    <img class="d-inline-block align-middle" src="{{ asset(config('ranking.job_type_vsro')[$data->charJob->JobType]['image']) }}" width="50" height="" alt=""/>
                                @else
                                    <img class="d-inline-block align-middle" src="{{ asset(config('ranking.job_type')[$data->charJob->JobType]['image']) }}" width="50" height="" alt=""/>
                                @endif

                                <div class="d-inline-block align-middle">
                                    @if(config('global.server.version') === 'vSRO')
                                        <p class="mb-0">{{ config('ranking.job_type_vsro')[$data->charJob->JobType]['name'] }}</p>
                                    @else
                                        <p class="mb-0">{{ config('ranking.job_type')[$data->charJob->JobType]['name'] }}</p>
                                    @endif
                                    <p class="mb-0">{{ __('Job Level:') }} <span>{{ $data->charJob->JobLevel ?? $data->charJob->Level }}</span></p>
                                </div>
                            </div>
                        @endif
                        <div class="col">
                            <p class="mb-0">{{ __('Health:') }} <span>{{ $data->HP }}</span></p>
                            <p class="mb-0">{{ __('Mana:') }} <span>{{ $data->MP }}</span></p>
                        </div>
                        <div class="col">
                            <p class="mb-0">{{ __('Strength:') }} <span>{{ $data->Strength }}</span></p>
                            <p class="mb-0">{{ __('Intellect:') }} <span>{{ $data->Intellect }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-lg-6 d-flex flex-column">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-tab-pane" type="button" role="tab" aria-controls="info-tab-pane" aria-selected="true">{{ __('Information') }}</button>
                        </li>
                        @if(config('ranking.extra.character_global_history'))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="global-tab" data-bs-toggle="tab" data-bs-target="#global-tab-pane" type="button" role="tab" aria-controls="global-tab-pane" aria-selected="false">{{ __('Global Chat') }}</button>
                        </li>
                        @endif
                        @if(config('ranking.extra.character_unique_history'))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="unique-tab" data-bs-toggle="tab" data-bs-target="#unique-tab-pane" type="button" role="tab" aria-controls="unique-tab-pane" aria-selected="false">{{ __('Unique Kills') }}</button>
                        </li>
                        @endif
                        @if(config('ranking.extra.character_job_kill'))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="job-tab" data-bs-toggle="tab" data-bs-target="#job-tab-pane" type="button" role="tab" aria-controls="job-tab-pane" aria-selected="false">{{ __('Job Kills') }}</button>
                        </li>
                        @endif
                        @if(config('ranking.extra.character_pvp_kill'))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pvp-tab" data-bs-toggle="tab" data-bs-target="#pvp-tab-pane" type="button" role="tab" aria-controls="pvp-tab-pane" aria-selected="false">{{ __('Pvp Kills') }}</button>
                        </li>
                        @endif
                        @if(config('widgets.custom.owned_titles.enabled'))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="titles-tab" data-bs-toggle="tab" data-bs-target="#titles-tab-pane" type="button" role="tab" aria-controls="titles-tab-pane" aria-selected="false">{{ __('Owned Titles') }}</button>
                        </li>
                       @endif
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="info-tab-pane" role="tabpanel" aria-labelledby="info-tab" tabindex="0">
                            @include('pages.ranking.character.partials.character-information')
                        </div>
                        <div class="tab-pane fade" id="global-tab-pane" role="tabpanel" aria-labelledby="global-tab" tabindex="0">
                            @include('pages.ranking.character.partials.character-global-history')
                        </div>
                        <div class="tab-pane fade" id="unique-tab-pane" role="tabpanel" aria-labelledby="unique-tab" tabindex="0">
                            @include('pages.ranking.character.partials.character-unique-history')
                        </div>
                        <div class="tab-pane fade" id="job-tab-pane" role="tabpanel" aria-labelledby="job-tab" tabindex="0">
                            @include('pages.ranking.character.partials.character-job-kill')
                        </div>
                        <div class="tab-pane fade" id="pvp-tab-pane" role="tabpanel" aria-labelledby="pvp-tab" tabindex="0">
                            @include('pages.ranking.character.partials.character-pvp-kill')
                        </div>
                        <div class="tab-pane fade" id="titles-tab-pane" role="tabpanel" aria-labelledby="titles-tab" tabindex="0">
                            @include('partials.character-owned-titles', ['Limit' => 5, 'CharID' => $data->CharID])
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-flex">
                    <div class="card mt-3 w-100">
                        <div class="card-body position-relative d-flex flex-column" id="display-inventory">
                            <div class="position-relative z-2 flex-grow-1">
                                <div class="d-block" id="display-inventory-set">
                                    @include('pages.ranking.character.partials.inventory.inventory-view', ['inventorySetList' => $data->getCharInventorySet()])
                                </div>
                                @if(config('global.server.version') !== 'vSRO')
                                    <div class="d-none" id="display-inventory-job">
                                        @include('pages.ranking.character.partials.inventory.inventory-job-view', ['inventoryJobList' => $data->charInventoryJob])
                                    </div>
                                @endif
                                <div class="d-none" id="display-inventory-avatar">
                                    @include('pages.ranking.character.partials.inventory.inventory-avatar-view', ['inventoryAvatarList' => $data->charInventoryAvatar])
                                </div>
                            </div>

                            @if(config('global.server.version') === 'vSRO')
                                <img class="position-absolute top-0 start-0 w-100 h-100 object-fit-contain opacity-100 p-2" src="{{ asset('images/character_full/'.config('ranking.character_image_vsro')[$data->RefObjID]) }}" alt=""/>
                            @else
                                <img class="position-absolute top-0 start-0 w-100 h-100 object-fit-contain opacity-100 p-2" src="{{ asset('images/character_full/'.config('ranking.character_image')[$data->RefObjID]) }}" alt=""/>
                            @endif

                            <button id="display-inventory-switch" data-type="set" class="btn btn-secondary d-block mx-auto mt-auto position-relative z-1">{{ __('Switch') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .sro-item-detail {
            background: #808080;
            width: 38px;
            margin: 0 auto;
        }

        .sro-item-detail.sro-item-special {
            background: #FF8C00;
        }

        .sro-item-detail.sro-item-special .sro-item-special-seal {
            z-index: 4;
        }

        .sro-item-detail .item {
            width: 32px;
            height: 32px;
            float: left;
            margin: 3px;
            padding: 0 !important;
            color: #fff;
            background: #5f5f5f;
            position: relative;
        }
        .sro-item-detail .item img {
            position: absolute;
        }

        .sro-item-detail .item .amount {
            background: rgba(50, 50, 50, 0.5);
            padding: 1px 2px;
            float: left;
            font-size: 11px;
        }

        .sro-item-detail .info {
            color: #fff;
            z-index: 80;
            position: absolute;
            left: 34px;
            top: 3px;
            width: 180px;
            background: rgba(88, 98, 170, 0.85);
            border: 2px solid #303d4d;
            padding: 5px;
            display: none;
            line-height: 18px;
            font-size: 10px;
        }

        .table.table-inventory td, .table.table-inventory th {
            padding: 4px;
        }

        .sro-item-detail .tooltip {
            font-size: 10px;
            line-height: 15px;
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            position: fixed;
            padding: 5px;
            border: 1px solid #ccc;
            visibility: hidden;
            box-shadow: -2px 2px 5px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transition:
                opacity 0.3s,
                visiblity 0s;
        }
        .sro-item-detail:hover .tooltip {
            visibility: visible;
            opacity: 1;
        }

        /********/
        .sro-item-detail .tooltip {
            text-align: left !important;
            font-size: 12px;
            width: 300px;
            min-height: 200px;
            background-color: rgba(28, 30, 52, .8);
            color: #fff;
            padding: 6px;
            border: 1px solid #808bba;
            border-radius: 5px;
            box-shadow: none;
            z-index: 999;
        }
        .sro-item-detail .item > img {
            position: absolute;
            width: 32px;
            height: 32px;
        }
    </style>
    <style>
        .table.table-inventory td,
        .table.table-inventory th {
            background: none !important;
            border: none !important;
        }
        #display-inventory .d-none {
            display: none !important;
        }
    </style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-iteminfo]').forEach(el => {
            const info = el.parentElement.querySelector('.info');

            if (info.innerHTML === '') return;

            const tip = document.createElement('div');
            tip.classList.add('tooltip');
            tip.innerHTML = info.innerHTML;
            tip.style.transform = `translate(${el.hasAttribute('tip-left') ? 'calc(-100% - 5px)' : '15px'}, ${el.hasAttribute('tip-top') ? '-100%' : '0'})`;

            el.appendChild(tip);

            el.addEventListener('mousemove', e => {
                tip.style.left = e.clientX + 'px';
                tip.style.top = e.clientY + 'px';
            });
        });

        const switchBtn = document.getElementById('display-inventory-switch');
        if (switchBtn) {
            switchBtn.addEventListener('click', function () {
                const current = this.dataset.type;
                const stages = ['set'];

                @if(config('global.server.version') !== 'vSRO')
                stages.push('job');
                @endif
                stages.push('avatar');

                const currentIndex = stages.indexOf(current);
                const nextIndex = (currentIndex + 1) % stages.length;
                const change = stages[nextIndex];

                document.getElementById('display-inventory-' + current).classList.add('d-none');
                document.getElementById('display-inventory-' + change).classList.remove('d-none');

                this.dataset.type = change;
            });
        }
    });
</script>
@endpush

