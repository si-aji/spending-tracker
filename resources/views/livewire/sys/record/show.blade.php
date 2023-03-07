@section('parentTitle', 'Record Detail')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-1 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('sys.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('sys.record.index') }}">Record List</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Detail</li>
        </ol>
    </nav>
@endsection

<div id="record-detail">
    {{-- The best athlete wants his opponent at his best. --}}
    <div class="row">
        <div class="col-12 col-lg-9">
            <div class="card border">
                <div class="card-header border-bottom pb-0">
                    <div class="d-sm-flex align-items-start tw__mb-4 lg:tw__mb-0">
                        <div>
                            <h6 class="font-weight-semibold text-lg mb-0">Record Detail</h6>
                            <p class="text-sm">See detailed information about related record</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Summary --}}
                    <div class=" tw__grid tw__grid-cols-1 md:tw__grid-cols-2 tw__gap-2">
                        {{-- Type --}}
                        <div class=" tw__grid tw__grid-flow-row tw__gap-1">
                            <strong>Type</strong>
                            <div class="">
                                <span class=" tw__px-2 tw__py-1 tw__rounded tw__text-sm {{ $recordData->type === 'expense' ? 'tw__bg-red-300' : 'tw__bg-green-300' }}">{{ (!empty($recordData->to_wallet_id) ? 'Transfer - ' : '').ucwords($recordData->type) }}</span>
                            </div>
                        </div>
                        {{-- Category --}}
                        <div class=" tw__grid tw__grid-flow-row tw__gap-1">
                            <strong>Category</strong>
                            <span>
                                @if ($recordData->category()->exists())
                                    <a href="{{ route('sys.category.index') }}">{{ ($recordData->category->parent()->exists() ? $recordData->category->parent->name.' - ' : '').$recordData->category->name }}</a>
                                @else
                                    <span>No Category</span>
                                @endif
                            </span>
                        </div>
                        {{-- Wallet --}}
                        <div class=" tw__grid tw__grid-flow-row tw__gap-1">
                            <strong>Wallet</strong>
                            <a href="javascript:void(0)">{{ ($recordData->fromWallet->parent()->exists() ? $recordData->fromWallet->parent->name.' - ' : '').$recordData->fromWallet->name }}</a>
                        </div>
                        {{-- Wallet --}}
                        <div class=" tw__grid tw__grid-flow-row tw__gap-1">
                            <strong>To Wallet</strong>
                            @if (!empty($recordData->to_wallet_id))
                                <a href="javascript:void(0)">{{ ($recordData->toWallet->parent()->exists() ? $recordData->toWallet->parent->name.' - ' : '').$recordData->toWallet->name }}</a>
                            @else
                                <span>-</span>
                            @endif
                        </div>
                        {{-- Date --}}
                        <div class=" tw__grid tw__grid-flow-row tw__gap-1">
                            <strong>Date / Time</strong>
                            <span data-period="{{ $recordData->datetime }}">-</span>
                        </div>
                    </div>
                    <hr/>
                    <div class="">
                        <div class=" tw__w-full tw__p-4 tw__rounded-lg tw__border-2 tw__border-dashed">
                            <span class=" tw__flex tw__items-center tw__gap-1">
                                <i class="bx bx-paragraph"></i>
                                <strong>Note(s)</strong>
                            </span>
                            <span class=" tw__block tw__mt-2">{{ $recordData->note ? $recordData->note : 'No description' }}</span>
                        </div>
                    </div>
                </div>
                <div class="table-responsive" x-data="{toggle: false}">
                    <table class=" table border-top m-0">
                        <tbody>
                            <tr>
                                <th>Base Amount</th>
                                <td data-orig="{{ formatRupiah($recordData->amount) }}" data-short="{{ formatRupiah($recordData->amount, 'Rp', true) }}" x-on:click="toggle = !toggle" x-text="(toggle ? $el.dataset.orig : $el.dataset.short)">
                                    <span>{{ formatRupiah($recordData->amount, 'Rp', true) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Extra Amount</th>
                                <td>
                                    <span class=" tw__block" data-orig="{{ formatRupiah($recordData->extra_amount) }}" data-short="{{ formatRupiah($recordData->extra_amount, 'Rp', true) }}" x-on:click="toggle = !toggle" x-text="(toggle ? $el.dataset.orig : $el.dataset.short)">{{ formatRupiah($recordData->extra_amount, 'Rp', true) }}</span>
                                    @if ($recordData->extra_amount > 0)
                                        <small class="badge bg-secondary">{{ ucwords($recordData->extra_type) }}</small>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <td data-orig="{{ formatRupiah($recordData->amount + $recordData->extra_amount) }}" data-short="{{ formatRupiah($recordData->amount + $recordData->extra_amount, 'Rp', true) }}" x-on:click="toggle = !toggle" x-text="(toggle ? $el.dataset.orig : $el.dataset.short)">
                                    <strong>{{ formatRupiah($recordData->amount + $recordData->extra_amount, 'Rp', true) }}</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer border-top">
                    <small>
                        <strong>Note</strong>, record was created at <span data-period="{{ $recordData->created_at }}">-</span>. {!! $recordData->created_at != $recordData->updated_at ? ('Last update at <span data-period="'.$recordData->updated_at.'">-</span>.') : '' !!}
                    </small>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3 order-first order-lg-last tw__mb-4 lg:tw__mb-0">
            <div class=" lg:tw__sticky lg:tw__top-[5.5rem]">
                <div class="card border">
                    <div class="card-body">
                        <a href="{{ route('sys.record.index') }}" class="btn btn-sm btn-white tw__w-full tw__mb-0">
                            <span class=" tw__flex tw__items-center tw__gap-2">
                                <i class="fa-solid fa-chevron-left"></i>
                                Back
                            </span>
                        </a>
                    </div>
                </div>

                @if (!empty($recordData->to_wallet_id) && !empty($recordData->getRelated()))
                    @php
                        $related = $recordData->getRelated();
                    @endphp
                    <div class="card border tw__mt-4">
                        <div class="card-header pb-0">
                            <div class="d-sm-flex align-items-start tw__mb-4 lg:tw__mb-0">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Related Record</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('sys.record.show', $related->uuid) }}" class="btn btn-sm btn-white tw__w-full tw__mb-0">Transfer - {{ ucwords($related->type) }}</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@section('js_inline')
    <script>
        var timezone = null;
        const updateTimestamp = () => {
            let container = document.getElementById('record-detail');

            container.querySelectorAll('[data-period]').forEach((el) => {
                let recordDate = momentFormated('YYYY-MM-DD HH:mm:ss', timezone, el.dataset.period);
                el.innerHTML = `${moment(recordDate).format('DD MMM, YYYY / HH:mm')} <small>(${moment().tz(timezone ?? 'Asia/Jakarta').format('Z')})</small>`;
            });
        };

        document.addEventListener('DOMContentLoaded', () => {
            if(moment.tz.guess()){
                timezone = moment.tz.guess();
            }

            updateTimestamp();
        });
    </script>
@endsection