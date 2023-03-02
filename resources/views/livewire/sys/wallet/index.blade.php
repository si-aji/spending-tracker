@section('parentTitle', 'Wallet List')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-1 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('sys.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Wallet List</li>
        </ol>
    </nav>
@endsection

<div>
    {{-- The whole world belongs to you. --}}
    <div class="card border">
        <div class="card-header border-bottom pb-0">
            <div class="d-sm-flex align-items-center">
                <div>
                    <h6 class="font-weight-semibold text-lg mb-0">Wallet list</h6>
                    <p class="text-sm">See information about all wallet</p>
                </div>
                <div class="ms-auto d-flex">
                    <a href="{{ route('sys.wallet.re-order.index') }}" class="btn btn-sm btn-white me-2">
                        Re-order
                    </a>
                    <button type="button" class="btn btn-sm btn-dark btn-icon tw__flex tw__items-center tw__gap-2" wire:click="$emitTo('component.wallet.wallet-modal', 'showModal')">
                        <i class="fa-solid fa-plus"></i>
                        <span class="btn-inner--text">Add new</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body tw__px-0 tw__py-0">
            <div class="border-bottom py-3 px-3 d-sm-flex align-items-center">
                <div class="btn-group tw__mb-4 md:tw__mb-0" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="wallet_status" id="wallet_status-all" value="all" autocomplete="off" wire:model.lazy="wallet_filter_state">
                    <label class="btn btn-white px-3 mb-0" for="wallet_status-all">All</label>
                    <input type="radio" class="btn-check" name="wallet_status" id="wallet_status-active" value="active" autocomplete="off" wire:model.lazy="wallet_filter_state">
                    <label class="btn btn-white px-3 mb-0" for="wallet_status-active">Active</label>
                    <input type="radio" class="btn-check" name="wallet_status" id="wallet_status-archive" value="archive" autocomplete="off" wire:model.lazy="wallet_filter_state">
                    <label class="btn btn-white px-3 mb-0" for="wallet_status-archive">Archive</label>
                </div>
                <div class="input-group w-sm-45 w-lg-25 ms-auto">
                    <span class="input-group-text text-body">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Search" wire:model.debounce="wallet_filter_search">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-items-center justify-content-center mb-0">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="text-secondary text-sm font-weight-semibold opacity-7 ps-2">Name</th>
                            <th class="text-secondary text-sm font-weight-semibold opacity-7 ps-2">Balance</th>
                            <th class="text-secondary text-sm font-weight-semibold opacity-7 ps-2">Last Record</th>
                            <th class="text-secondary text-sm font-weight-semibold opacity-7 ps-2"></th>
                        </tr>
                    </thead>
                    <tbody id="walletList-container"></tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $paginate->links('vendor.livewire.sys-paginate') }}
        </div>
    </div>
</div>

@section('content_modal')
    @livewire('component.wallet.wallet-modal', ['user' => \Auth::user()], key(\Auth::user()->id))
@endsection

@section('js_inline')
    <script>
        let loadSkeleton = false;
        const loadDataSkeleton = () => {
            console.log('Preparation for Content Skeleton');
            let container = document.getElementById('walletList-container');

            console.log(container);
            if(container){
                let template = `
                    <td>
                        <div class=" tw__grid tw__grid-flow-row">
                            <div>
                                <span class="tw__bg-gray-300 tw__rounded tw__w-20 tw__h-5 tw__flex"></span>
                            </div>
                            <span class=" tw__mt-2">
                                <span class="tw__bg-gray-300 tw__rounded tw__w-48 tw__h-5 tw__flex"></span>
                            </span>
                        </div>
                    </td>
                    <td>
                        <span class="tw__bg-gray-300 tw__rounded tw__w-24 tw__h-5 tw__flex"></span>
                    </td>
                    <td>
                        <div class=" tw__grid tw__grid-flow-row">
                            <div>
                                <span class="tw__bg-gray-300 tw__rounded tw__w-20 tw__h-5 tw__flex"></span>
                            </div>
                            <span class=" tw__mt-2">
                                <span class="tw__bg-gray-300 tw__rounded tw__w-48 tw__h-5 tw__flex"></span>
                            </span>
                        </div>
                    </td>
                    <td>
                        <span class="tw__bg-gray-300 tw__rounded tw__w-20 tw__h-5 tw__flex"></span>
                    </td>
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
                        let el = document.createElement('tr');
                        el.classList.add('list-item', 'tw__animate-pulse', 'hover:tw__bg-gray-100');
                        el.dataset.index = i;
                        el.innerHTML = template;
                        container.appendChild(el);
                    }
                }
            }
        };

        loadDataSkeleton();
        document.addEventListener('DOMContentLoaded', () => {
            window.dispatchEvent(new Event('walletListLoadData'));

            if(document.getElementById('wallet-modal')){
                document.getElementById('wallet-modal').addEventListener('hidden.bs.modal', (e) => {
                    loadDataSkeleton();
                    Livewire.emit('refreshComponent');
                });
            }
        });

        window.addEventListener('walletListLoadData', () => {
            if(document.getElementById('walletList-container')){
                generateList();
            }
        });
        function generateList(){
            // Get data from Component
            let data = @this.get('dataWallet');
            // console.log(data);

            let paneEl = document.getElementById('walletList-container');
            if(data.length > 0){
                let existingItem = paneEl.querySelectorAll('.list-item');
                if(existingItem.length > 0){
                    data.forEach((val, index) => {
                        // console.log(val);
                        let wrapper = paneEl.querySelector(`.list-item[data-index="${index}"]`);
                        if(!wrapper){
                            let wrapperTemp = document.createElement('tr');
                            wrapperTemp.classList.add('list-item', 'tw__animate-pulse', 'hover:tw__bg-gray-100');
                            wrapperTemp.dataset.index = index;
                            wrapperTemp.innerHTML = `<td colspan="4"></td>`;
                            paneEl.appendChild(wrapperTemp);

                            wrapper = paneEl.querySelector(`.list-item[data-index="${index}"]`);
                        }

                        // Remove animate
                        if(wrapper.classList.contains('tw__animate-pulse')){
                            wrapper.classList.remove('tw__animate-pulse');
                        }

                        // Variable
                        let walletName = `${val.name}`;
                        if(val.parent){
                            walletName = `${val.parent.name} - ${val.name}`;
                        }

                        // Generate Action Button
                        let actionButton = [];
                        actionButton.push(`
                            <a href="javascript:void(0);" class="text-secondary font-weight-bold text-xs" data-bs-toggle="tooltip" data-bs-title="Show Detail">
                                <span class=" tw__flex tw__items-center tw__gap-1">
                                    <i class="fa-solid fa-eye"></i>
                                    <span>Detail</span>
                                </span>
                            </a>
                        `);
                        actionButton.push(`
                            <a href="javascript:void(0);" class="text-secondary font-weight-bold text-xs" data-bs-toggle="tooltip" data-bs-title="Edit Wallet" x-on:click="$wire.emitTo('component.wallet.wallet-modal', 'edit', '${val.uuid}')">
                                <span class=" tw__flex tw__items-center tw__gap-1">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span>Edit</span>
                                </span>
                            </a>
                        `);
                        // Create content
                        wrapper.innerHTML = `
                            <td>
                                <span class="text-sm text-dark font-weight-semibold">${walletName}</span>
                            </td>
                            <td>
                                <span class="text-sm">Balance</span>
                            </td>
                            <td>
                                <div class=" tw__grid tw__grid-flow-row">
                                    <div>
                                        <span class="badge badge-sm border border-success text-success bg-success">Income</span>
                                    </div>
                                    <span class="text-sm tw__mt-2"><strong>Rp 50.000,-</strong> at ${moment().format('Do, MMM YYYY / HH:mm')}</span>
                                </div>
                            </td>
                            <td>
                                <div class=" tw__flex tw__items-center tw__gap-2">
                                    ${actionButton.join('<span>/</span>')}
                                </div>
                            </td>
                        `;
                    });

                    let extra = [].filter.call(paneEl.querySelectorAll('.list-item'), (el) => {
                        return el.dataset.index >= data.length;
                    });
                    extra.forEach((el) => {
                        el.remove();
                    });
                } else {

                }
            }
        }
    </script>
@endsection
