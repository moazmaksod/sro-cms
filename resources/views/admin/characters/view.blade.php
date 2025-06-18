@extends('admin.layouts.app')
@section('title', __('View Character'))

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">View Character</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card p-0">
                    <div class="card-header">
                        <h4 class="text-center">Character Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="min-height: auto !important;">
                            <table class="table table-striped">
                                <tbody>
                                <tr>
                                    <th scope="row">CharID</th>
                                    <td>{{ $char->CharID }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">CharName</th>
                                    <td>
                                        @if($char->RefObjID > 2000)
                                            <img src="{{ asset(config('ranking.character_race')[1]['image']) }}" width="16" height="16" alt=""/>
                                        @else
                                            <img src="{{ asset(config('ranking.character_race')[0]['image']) }}" width="16" height="16" alt=""/>
                                        @endif
                                        {{ $char->CharName16 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Guild</th>
                                    <td>
                                        @if($char->guild->Name != 'DummyGuild')
                                            <a href="{{ route('ranking.guild.view', ['name' => $char->guild->Name]) }}" class="text-decoration-none">{{ $char->guild->Name }}</a>
                                        @else
                                            {{ __('None') }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Jobname</th>
                                    <td>
                                        @if(!empty($char->NickName16))
                                            {{ $char->NickName16 }}
                                        @else
                                            {{ __('None') }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Level</th>
                                    <td>{{ $char->CurLevel }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Exp</th>
                                    <td>{{ number_format($char->ExpOffset , 0, ',') }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Gold</th>
                                    <td>{{ number_format($char->RemainGold , 0, ',') }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card p-0 mt-4">
                    <div class="card-header">
                        <h4 class="text-center">Character Items</h4>
                    </div>
                    <div class="card-body">
                        <div class="" id="display-inventory-set">
                            @include('ranking.character.partials.inventory.inventory-view', ['inventorySetList' => $inventorySet])
                        </div>
                    </div>
                </div>

                <div class="card p-0 mt-4">
                    <div class="card-header">
                        <h4 class="text-center">Logged in history</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="min-height: auto !important;">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">Status</th>
                                    <th scope="col">Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($status as $value)
                                    @if($value->EventID == 4 || $value->EventID == 6)
                                        <tr>
                                            @if($value->EventID == 4)
                                                <td><span class="text-success">Login</span></td>
                                            @elseif($value->EventID == 6)
                                                <td><span class="text-danger">Logout</span></td>
                                            @endif
                                            <td>{{ \Carbon\Carbon::parse($value->EventTime)->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">No Records Found!</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-0">
                    <div class="card-header">
                        <h4 class="text-center">Unstuck</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled w-50 m-auto p-3">
                            <li>
                                <span>Current X:</span>
                                <span class="float-end">{{ round($char->PosX, 2) }}</span>
                            </li>
                            <li>
                                <span>Current Y:</span>
                                <span class="float-end">{{ round($char->PosY, 2) }}</span>
                            </li>
                            <li>
                                <span>Current Z:</span>
                                <span class="float-end">{{ round($char->PosZ, 2) }}</span>
                            </li>
                        </ul>
                        <hr>

                        <form method="POST" action="{{ route('admin.characters.update', $char) }}">
                            @csrf
                            @method('PUT')

                            <div class="row mb-0">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-danger w-100">{{ __('Unstuck') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--
    <div class="" id="display-inventory-set">
        <h2 style="display: none">Inventory</h2>
        <div class="table-responsive">
            <table class="table table-borderless table-inventory mx-auto">
                <tbody>
                <tr>
                    <td>
                        <div class="sro-item-detail">
                            <div class="item"></div>
                            <div class="clearfix"></div>
                        </div>
                    </td>
                    <td>
                        <div class="sro-item-detail">
                            <div class="item"></div>
                            <div class="clearfix"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="sro-item-detail">
                            <div class="item"></div>
                            <div class="clearfix"></div>
                        </div>
                    </td>
                    <td>
                        <div class="sro-item-detail">
                            <div class="item"></div>
                            <div class="clearfix"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="sro-item-detail">
                            <div class="item"></div>
                            <div class="clearfix"></div>
                        </div>
                    </td>
                    <td>
                        <div class="sro-item-detail">
                            <div class="item"></div>
                            <div class="clearfix"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="sro-item-detail">
                            <div class="item"></div>
                            <div class="clearfix"></div>
                        </div>
                    </td>
                    <td>
                        <div class="sro-item-detail">
                            <div class="item"></div>
                            <div class="clearfix"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="sro-item-detail">
                            <div class="item"></div>
                            <div class="clearfix"></div>
                        </div>
                    </td>
                    <td>
                        <div class="sro-item-detail">
                            <div class="item"></div>
                            <div class="clearfix"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="sro-item-detail">
                            <div class="item"></div>
                            <div class="clearfix"></div>
                        </div>
                    </td>
                    <td>
                        <div class="sro-item-detail">
                            <div class="item"></div>
                            <div class="clearfix"></div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    -->
@endsection

@push('styles')
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <style>
        .sro-item-detail .tooltip {
            text-align: left !important;
            font-size: 12px;
            width: 250px;
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
        /* Style for paginated layout */
        #display-inventory-set .page-grid {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 8px;
            margin-bottom: 20px;
        }

        /* Hide all pages initially */
        #display-inventory-set .page-hidden {
            display: none !important;
        }

        /* Buttons */
        #display-inventory-set .page-buttons {
            text-align: center;
            margin-top: 20px;
        }

        #display-inventory-set .page-buttons button {
            padding: 8px 16px;
            margin: 0 5px;
            cursor: pointer;
            font-weight: bold;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/function.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('display-inventory-set');
            const table = container.querySelector('table');
            const itemElements = Array.from(container.querySelectorAll('.sro-item-detail'));

            // Hide first 12 items
            const remainingItems = itemElements.slice(12);
            itemElements.slice(0, 12).forEach(item => {
                item.parentElement.style.display = 'none'; // hides <td>
            });

            // Remove the original table
            const tableWrapper = container.querySelector('.table-responsive');
            if (tableWrapper) tableWrapper.remove();

            const itemsPerPage = 32;
            const totalPages = Math.ceil(remainingItems.length / itemsPerPage);
            const pages = [];

            // Create new pages
            for (let i = 0; i < totalPages; i++) {
                const pageDiv = document.createElement('div');
                pageDiv.classList.add('page-grid');
                if (i !== 0) pageDiv.classList.add('page-hidden');

                const chunk = remainingItems.slice(i * itemsPerPage, (i + 1) * itemsPerPage);
                chunk.forEach(item => pageDiv.appendChild(item));

                container.appendChild(pageDiv);
                pages.push(pageDiv);
            }

            // Create page switch buttons
            const btnContainer = document.createElement('div');
            btnContainer.className = 'page-buttons';

            pages.forEach((page, i) => {
                const btn = document.createElement('button');
                btn.textContent = `Page ${i + 1}`;
                btn.className = 'btn btn-primary';
                btn.addEventListener('click', () => {
                    pages.forEach(p => p.classList.add('page-hidden'));
                    page.classList.remove('page-hidden');
                });
                btnContainer.appendChild(btn);
            });

            container.appendChild(btnContainer);
        });
    </script>
@endpush
