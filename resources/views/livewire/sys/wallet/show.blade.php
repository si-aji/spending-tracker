@section('parentTitle', 'Wallet: Detail')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-1 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('sys.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('sys.wallet.index') }}">Wallet</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Detail</li>
        </ol>
    </nav>
@endsection

<div id="wallet-detail">
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <div class="card border">
        <div class="card-header border-bottom pb-0">
            <div class="d-sm-flex align-items-center">
                <div>
                    <h6 class="font-weight-semibold text-lg mb-0">Wallet: Detail</h6>
                    <p class="text-sm">See detailed information about your Wallet</p>
                </div>
                <div class="ms-auto d-flex">
                    <a href="{{ route('sys.wallet.index') }}" class="btn btn-sm btn-white me-2">
                        <span class=" tw__flex tw__items-center tw__gap-2">
                            <i class="fa-solid fa-chevron-left"></i>
                            Back
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="card card-background card-background-after-none align-items-start">
                <div class="full-background" style="background-image: url('{{ asset('assets/corporate-ui/img/curved-images/img-7.jpg') }}')"></div>
                <div class="card-body text-start p-2 w-100">
                    <div class="row">
                        <div class="col-12">
                            <div class="blur w-100 border-radius-md border border-white mb-4 p-2 tw__flex tw__items-start md:tw__items-center tw__justify-between tw__flex-wrap tw__flex-col md:tw__flex-row tw__gap-4">
                                <div class=" tw__flex tw__flex-col">
                                    <p class="mb-0 font-weight-bold tw__text-black tw__text-lg tw__block">{{ ($data->parent()->exists() ? $data->parent->name.' - ' : '').$data->name }}</p>
                                
                                    @if ($data->parent()->exists())
                                        <a href="{{ route('sys.wallet.show', $data->parent->uuid) }}">
                                            <small class=" tw__text-slate-600 tw__flex tw__items-center tw__gap-2 tw__ml-1">
                                                <span class=" tw__rotate-90"><i class="fa-solid fa-arrow-turn-up"></i></span>
                                                <span>{{ $data->parent->name }}</span>
                                            </small>
                                        </a>
                                    @endif
                                </div>
                                <div class="">
                                    <button class="btn btn-sm btn-primary tw__uppercase tw__mb-0" wire:click="$emitTo('component.wallet.wallet-adjustment-modal', 'edit', '{{ $data->uuid }}')">Adjustment</button>
                                </div>
                            </div>
                        </div>
                        <div class=" tw__flex tw__flex-col md:tw__flex-row md:tw__items-end md:tw__justify-between tw__gap-2">
                            <div class="">
                                <p class="font-weight-semibold mb-0 mt-3 tw__text-2xl">{{ formatRupiah($data->getBalance()) }}</p>
                            </div>
                            <div class="">
                                @if (empty($data->getLastTransaction()))
                                    <small>No transactions recorded</small>
                                @else
                                    <small class="">Last Transaction was recorded at <strong><a href="{{ route('sys.record.show', $data->getLastTransaction()->uuid) }}" class=" tw__text-white" data-period="{{ $data->getLastTransaction()->datetime }}">-</a></strong></small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card tw__mt-4">
                <div class="card-body" wire:ignore>
                    <div id="recordList-container"></div>
                </div>
                <div class="card-footer">
                    <div class=" tw__flex tw__items-center tw__justify-between">
                        @if (isset($paginate))
                            <button wire:loading.remove wire:target="loadMore" type="button" class="btn btn-primary disabled:tw__cursor-not-allowed tw__mb-0" {{ $paginate->hasMorePages() ? '' : 'disabled' }} wire:click="loadMore">
                                <span>Load more</span>
                            </button>
                            <button wire:loading.block wire:target="loadMore" type="button" class="btn btn-primary disabled:tw__cursor-not-allowed" disabled>
                                <span class=" tw__flex tw__items-center tw__gap-2">
                                    <i class=" fa-solid fa-spinner fa-spin"></i>
                                    <span>Loading</span>
                                </span>
                            </button>

                            <span>Showing {{ $paginate->count() }} of {{ $paginate->total() }} entries</span>
                        @else
                            <button wire:loading.remove wire:target="loadMore" type="button" class="btn btn-primary disabled:tw__cursor-not-allowed tw__mb-0" disabled wire:click="loadMore">
                                <span>Load more</span>
                            </button>

                            <span>Showing 0 entrie(s)</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('content_modal')
    @livewire('component.wallet.wallet-adjustment-modal', ['user' => \Auth::user()], key(\Auth::user()->id))
@endsection

@section('js_inline')
    <script>
        var timezone = null;
        const updateTimestamp = () => {
            console.log('Update Timestamp');
            let container = document.getElementById('wallet-detail');

            container.querySelectorAll('[data-period]').forEach((el) => {
                let recordDate = momentFormated('YYYY-MM-DD HH:mm:ss', timezone, el.dataset.period);
                el.innerHTML = `${moment(recordDate).format('DD MMM, YYYY / HH:mm')} <small>(${moment().tz(timezone ?? 'Asia/Jakarta').format('Z')})</small>`;
            });
        };

        let loadSkeleton = false;
        const loadDataSkeleton = () => {
            console.log('Preparation for Content Skeleton');
            let container = document.getElementById('recordList-container');

            if(container){
                container.innerHTML = ``;
                let template = `
                    <div class=" tw__flex tw__flex-col">
                        <div class="list-wrapper tw__flex tw__gap-2 tw__mb-4 last:tw__mb-0">
                            <div class=" tw__py-4 md:tw__px-4 tw__text-center">
                                <div class="tw__sticky tw__top-24 md:tw__top-40 tw__flex tw__items-center tw__flex-col">
                                    <span class="tw__font-semibold tw__bg-gray-300 tw__animate-pulse tw__h-4 tw__w-8 tw__block tw__rounded tw__mr-0 tw__mb-2"></span>
                                    <div class=" tw__min-h-[40px] tw__min-w-[40px] tw__bg-gray-300 tw__bg-opacity-60 tw__rounded-full tw__flex tw__leading-none tw__items-center tw__justify-center tw__align-middle tw__animate-pulse">
                                        <p class="tw__mb-0 tw__font-bold tw__text-xl tw__text-white"></p>
                                    </div>
                                    <span class="tw__font-semibold tw__bg-gray-300 tw__animate-pulse tw__h-3 tw__w-12 tw__block tw__rounded tw__mr-0 tw__mt-1"></span>
                                </div>
                            </div>
                            <div class=" tw__bg-gray-300 tw__rounded-lg tw__w-full content-list tw__p-4 tw__h-20 tw__animate-pulse tw__self-center">
                            </div>
                        </div>
                    </div>
                `;

                // Check if child element exists
                if(container.querySelectorAll('.list-item').length > 0){
                    container.querySelectorAll('.list-item').forEach((el, index) => {
                        el.classList.add('tw__animate-pulse');
                        el.innerHTML = template;
                    });
                } else {
                    // Child not yet exists, create new data instead using existing data
                    for(i = 0; i < 5; i++){
                        let el = document.createElement('div');
                        el.classList.add('list-item', 'tw__animate-pulse');
                        el.dataset.index = i;
                        el.innerHTML = template;
                        container.appendChild(el);
                    }
                }
            }
        };

        loadDataSkeleton();
        document.addEventListener('DOMContentLoaded', () => {
            updateTimestamp();
            if(moment.tz.guess()){
                timezone = moment.tz.guess();
            }

            window.dispatchEvent(new Event('recordListLoadData'));
            if(document.getElementById('record-modal')){
                document.getElementById('record-modal').addEventListener('hidden.bs.modal', (e) => {
                    loadDataSkeleton();
                    window.dispatchEvent(new Event('recordListLoadData'));
                    @this.emit('refreshComponent')
                });
            }
            if(document.getElementById('wallet_adjustment-modal')){
                document.getElementById('wallet_adjustment-modal').addEventListener('hidden.bs.modal', (e) => {
                    loadDataSkeleton();
                    window.dispatchEvent(new Event('recordListLoadData'));
                    @this.emit('refreshComponent')
                });
            }
        });

        window.addEventListener('walletShow-load', (e) => {
            updateTimestamp();
        });

        window.addEventListener('recordListLoadData', () => {
            console.log('Load Record List Data');
            if(document.getElementById('recordList-container')){
                if(moment.tz.guess()){
                    @this.timezone = moment.tz.guess();
                }

                @this.fetchRecordData();
            }
        });
        window.addEventListener('fetchData', () => {
            console.log('Fetch Data');
            let data = @this.get('dataRecord');

            let paneEl = document.getElementById('recordList-container');
            if(data.length > 0){
                // Remove skeleton
                let skeletonEl = document.getElementById('recordList-container').querySelectorAll('.list-item');
                if(skeletonEl.length > 0){
                    paneEl.innerHTML = ``;
                }    

                // Append content
                let prevDate = null;
                data.forEach((val, index) => {
                    let date = val.datetime;
                    let original = val.datetime;
                    let recordDate = momentFormated('YYYY-MM-DD HH:mm:ss', val.timezone, date);

                    // Create element based on date
                    let recordWrapper = null;
                    if(!paneEl.querySelector(`.record-wrapper[data-date="${moment(recordDate).format('YYYY-MM-DD')}"]`)){
                        recordWrapper = document.createElement('div');
                        recordWrapper.classList.add('record-wrapper', 'tw__flex', 'tw__gap-4', 'tw__mb-4', 'last:tw__mb-0');
                        recordWrapper.dataset.date = moment(recordDate).format('YYYY-MM-DD');
                        recordWrapper.innerHTML = `
                            <div class="record-date tw__py-4 md:tw__px-4 tw__text-center">
                                <!-- This is for date -->
                                <div class="tw__sticky lg:tw__top-24 tw__top-40">
                                    <span class="tw__font-semibold">${moment(recordDate).format('ddd')}</span>
                                    <div class=" tw__h-[45px] tw__w-[45px] tw__bg-[#7166ef] tw__bg-opacity-60 tw__rounded-full tw__flex tw__leading-none tw__items-center tw__justify-center tw__align-middle">
                                        <p class="tw__mb-0 tw__font-bold tw__text-xl tw__text-white">${moment(recordDate).format('DD')}</p>
                                    </div>
                                    <small>${moment(recordDate).format('MMM')} '${moment(recordDate).format('YY')}</small>
                                </div>
                            </div>
                            <div class="record-list tw__bg-gray-50 tw__rounded-lg tw__w-full content-list tw__p-4">
                                <!-- List Goes Here -->
                            </div>
                        `;
                        paneEl.appendChild(recordWrapper);
                    } else {
                        recordWrapper = paneEl.querySelector(`.record-wrapper[data-date="${moment(recordDate).format('YYYY-MM-DD')}"]`);
                    }

                    // Append Item to 
                    if(recordWrapper){
                        let recordList = recordWrapper.querySelector(`.record-list`);
                        console.log(recordList);
                        if(recordList && !(recordList.querySelector(`[data-uuid="${val.uuid}"]`))){
                            // Generate content
                            let content = document.createElement('div');
                            content.classList.add('tw__border-b', 'last:tw__border-b-0', 'tw__py-4', 'first:tw__pt-0', 'last:tw__pb-0');
                            content.dataset.uuid = val.uuid;

                            // Fetch Wallet
                            let walletName = [];
                            let joinSeparator = '';
                            if(val.from_wallet.parent){
                                walletName.push(`${val.from_wallet.parent.name} - ${val.from_wallet.name}`);
                            } else {
                                walletName.push(val.from_wallet.name);
                            }
                            // Define if related record is transfer
                            if(val.to_wallet){
                                if(val.to_wallet.parent){
                                    walletName.push(`${val.to_wallet.parent.name} - ${val.to_wallet.name}`);
                                } else {
                                    walletName.push(val.to_wallet.name);
                                }

                                if(val.type === 'expense'){
                                    joinSeparator = `<i class="fa-solid fa-caret-right"></i>`;
                                } else if(val.type === 'income'){
                                    joinSeparator = `<i class="fa-solid fa-caret-left"></i>`;
                                }
                            }
                            let amount = parseFloat(val.amount);
                            if(val.extra_amount){
                                amount += parseFloat(val.extra_amount);
                            }
                            // Define extra information
                            let extraInformation = [];
                            if(val.category){
                                extraInformation.push(`<small class=" tw__flex tw__items-center tw__gap-1"><i class="fa-regular fa-bookmark"></i>${val.category.parent ? (`${val.category.parent.name} - ${val.category.name}`) : val.category.name}</small>`);
                            }
                            if(val.note){
                                extraInformation.push(`<small class=" tw__flex tw__items-center tw__gap-1"><i class="fa-solid fa-paragraph"></i> Note</small>`);
                            }
                            // Define Action
                            let actionBtn = [];
                            // Edit Action
                            actionBtn.push(`
                                <li>
                                    <a href="javascript:void(0)" class="dropdown-item tw__text-yellow-400" data-uuid="${val.uuid}" x-on:click="$wire.emitTo('component.record.record-modal', 'edit', '${val.uuid}')">
                                        <span class=" tw__flex tw__items-center"><i class="bx bx-edit tw__mr-2"></i>Edit</span>
                                    </a>
                                </li>
                            `);
                            // Detail Action
                            actionBtn.push(`
                                <li>
                                    <a href="{{ route('sys.record.index') }}/${val.uuid}" class="dropdown-item tw__text-blue-400">
                                        <span class=" tw__flex tw__items-center"><i class="bx bx-show tw__mr-2"></i>Detail</span>
                                    </a>
                                </li>
                            `);
                            // Delete Action
                            actionBtn.push(`
                                <li>
                                    <a href="javascript:void(0)" class="dropdown-item tw__text-red-400">
                                        <span class=" tw__flex tw__items-center"><i class="bx bx-trash tw__mr-2"></i>Remove</span>
                                    </a>
                                </li>
                            `);

                            let action = `
                                <div class="dropdown no-arrow dropend">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownaMenuLink">
                                        ${actionBtn.join('')}
                                    </ul>
                                </div>
                            `;
                            
                            content.innerHTML = `
                                <div class="">
                                    <!-- First row, for time;wallet;and amount -->
                                    <div class=" tw__flex tw__items-center tw__leading-none tw__gap-1">
                                        <!-- Time;Action & Wallet -->
                                        <div class=" tw__flex tw__flex-col md:tw__flex-row md:tw__items-center tw__gap-2 tw__w-full">
                                            <!-- Time & Action -->
                                            <span class=" tw__flex tw__items-center">
                                                <!-- Time -->
                                                <small class=" tw__text-gray-500 tw__flex tw__items-center tw__gap-2">
                                                    <i class="fa-regular fa-clock"></i>
                                                    ${moment(recordDate).format('HH:mm')}
                                                </small>
                                                <!-- Action -->
                                                <span class=" tw__block md:tw__hidden tw__ml-auto">
                                                    ${action}
                                                </span>
                                            </span>
                                            <!-- Wallet -->
                                            <small class=" tw__flex tw__items-center tw__leading-none tw__gap-1 tw__flex-wrap tw__text-gray-500">
                                                <span><i class="fa-solid fa-wallet"></i></span>
                                                <span class=" tw__flex tw__items-center tw__gap-1">${walletName.join(joinSeparator)}</span>
                                            </small>
                                        </div>

                                        <!-- Amount & Action -->
                                        <div class=" tw__ml-auto tw__flex tw__items-center tw__gap-2 md:tw__w-full tw__justify-end">
                                            <!-- Amount -->
                                            <span class=" ${val.type === 'expense' ? 'tw__text-red-600' : 'tw__text-green-600'} tw__text-base tw__hidden md:tw__block">${formatRupiah(amount)}</span>
                                            <!-- Action -->
                                            <span class=" tw__hidden md:tw__block">
                                                ${action}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Second row, for icon;type;and notes -->
                                    <div class="">
                                        <div class=" tw__my-2 tw__mt-4 lg:tw__mt-2 tw__flex tw__items-center tw__gap-4">
                                            <!-- Icon -->
                                            <span class=" tw__min-h-[35px] tw__min-w-[35px] tw__rounded-full tw__text-white ${val.to_wallet ? 'tw__bg-gray-400' : (val.type === 'expense' ? 'tw__bg-red-400' : 'tw__bg-green-400')} tw__bg-opacity-75 tw__flex tw__items-center tw__justify-center">
                                                <span class=" ${val.to_wallet ? 'tw__rotate-90' : (val.type === 'expense' ? 'tw__-rotate-90' : 'tw__rotate-90')}">
                                                    <i class="fa-solid ${val.to_wallet ? 'fa-arrow-right-arrow-left' : 'fa-right-from-bracket'}"></i>
                                                </span>
                                            </span>
                                            <!-- Type & Note -->
                                            <div class=" tw__flex tw__flex-col">
                                                <!-- Type -->
                                                <span class=" tw__text-black">${val.to_wallet ? 'Transfer - ' : ''}${ucwords(val.type)}</span>
                                                <!-- Note -->
                                                <small class=" tw__items-center tw__gap-1 tw__text-xs tw__hidden md:tw__flex"><i class="fa-solid fa-align-left"></i>${val.note ? val.note : 'No description provided'}</small>
                                            </div>
                                        </div>
                                        <div class="tw__block md:tw__hidden">
                                            <small class=" tw__flex tw__items-center tw__gap-1 tw__text-md"><i class="fa-solid fa-align-left"></i>${val.note ? val.note : 'No description provided'}</small>
                                        </div>
                                    </div>

                                    <!-- Third row, for extra information -->
                                    <div class="${!(extraInformation.length > 0) ? 'tw__hidden' : ' tw__mt-4 tw__flex tw__items-center tw__gap-2'}">
                                        ${extraInformation.join('')}    
                                    </div>
                                </div>
                            `;
                            recordList.appendChild(content);
                        }
                        
                    }

                });
            } else {

            }
        });
    </script>
@endsection