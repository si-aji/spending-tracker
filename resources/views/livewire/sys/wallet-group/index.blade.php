@section('parentTitle', 'Wallet Group: List')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-1 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('sys.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('sys.wallet.index') }}">Wallet</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Wallet Group</li>
        </ol>
    </nav>
@endsection

<div>
    {{-- The best athlete wants his opponent at his best. --}}
    <div class="card border">
        <div class="card-header border-bottom pb-0">
            <div class="d-sm-flex align-items-center">
                <div>
                    <h6 class="font-weight-semibold text-lg mb-0">Wallet Group</h6>
                    <p class="text-sm">Group your wallet to know sum of balance amount</p>
                </div>
                <div class="ms-auto d-flex">
                    <button type="button" class="btn btn-sm btn-dark btn-icon tw__flex tw__items-center tw__gap-2" wire:click="$emitTo('component.wallet-group.wallet-group-modal', 'showModal')">
                        <i class="fa-solid fa-plus"></i>
                        <span class="btn-inner--text">Add new</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="border-bottom py-3 px-3 d-sm-flex align-items-center">
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
                            <th class="text-secondary text-sm font-weight-semibold opacity-7 ps-2">Item</th>
                            <th class="text-secondary text-sm font-weight-semibold opacity-7 ps-2"></th>
                        </tr>
                    </thead>
                    <tbody id="walletGroup-container" wire:ignore></tbody>
                </table>
            </div>
        </div>
        @if (isset($paginate))
            <div class="card-footer">
                {{ $paginate->links('vendor.livewire.sys-paginate') }}
            </div>
        @endif
    </div>
</div>

@section('content_modal')
    @livewire('component.wallet-group.wallet-group-modal', ['user' => \Auth::user()], key(\Auth::user()->id))
@endsection

@section('js_inline')
    <script>
        let loadSkeleton = false;
        const loadDataSkeleton = () => {
            console.log('Preparation for Content Skeleton');
            let container = document.getElementById('walletGroup-container');

            console.log(container);
            if(container){
                let template = `
                    <td>
                        <div class=" tw__grid tw__grid-flow-row">
                            <div>
                                <span class="tw__bg-gray-300 tw__rounded tw__w-20 tw__h-5 tw__flex"></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="tw__bg-gray-300 tw__rounded tw__w-24 tw__h-5 tw__flex"></span>
                    </td>
                    <td>
                        <div class=" tw__flex tw__flex-wrap tw__gap-2">
                            <span class="tw__bg-gray-300 tw__rounded tw__w-20 tw__h-5 tw__flex"></span>
                            <span class="tw__bg-gray-300 tw__rounded tw__w-20 tw__h-5 tw__flex"></span>
                            <span class="tw__bg-gray-300 tw__rounded tw__w-20 tw__h-5 tw__flex"></span>
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
            window.dispatchEvent(new Event('walletGroupLoadData'));

            if(document.getElementById('walletGroup-modal')){
                document.getElementById('walletGroup-modal').addEventListener('hidden.bs.modal', (e) => {
                    loadDataSkeleton();
                    Livewire.emit('refreshComponent');
                });
            }
            if(document.getElementById('record-modal')){
                document.getElementById('record-modal').addEventListener('hidden.bs.modal', (e) => {
                    loadDataSkeleton();
                    Livewire.emit('refreshComponent');
                });
            }
        });

        window.addEventListener('walletGroupLoadData', () => {
            console.log('Generate List');
            if(document.getElementById('walletGroup-container')){
                generateList();
            }
        });
        function generateList(){
            // Get data from Component
            let data = @this.get('dataWalletGroup');
            console.log(data);

            let paneEl = document.getElementById('walletGroup-container');
            if(data.length > 0){
                let existingItem = paneEl.querySelectorAll('.list-item');
                console.log(existingItem);
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
                        let item = [];
                        let maxShownItem = 2;
                        (val.wallet_group_item).some((groupItem, groupIndex) => {
                            item.push(`${groupItem.parent ? `${groupItem.parent.name} - ` : ''}${groupItem.name}`);

                            if(parseInt(groupIndex) + 1 === maxShownItem){
                                let leftover = (val.wallet_group_item).length - maxShownItem;
                                if(leftover > 0){
                                    item.push(`and ${leftover} more`);
                                }

                                return true;
                            }
                        });
                        

                        // Generate Action Button
                        let actionButton = [];
                        actionButton.push(`
                            <a href="{{ route('sys.wallet.group.index') }}/${val.uuid}" class="text-secondary font-weight-bold text-xs" data-bs-toggle="tooltip" data-bs-title="Show Detail">
                                <span class=" tw__flex tw__items-center tw__gap-1">
                                    <i class="fa-solid fa-eye"></i>
                                    <span>Detail</span>
                                </span>
                            </a>
                        `);
                        actionButton.push(`
                            <a href="javascript:void(0);" class="text-secondary font-weight-bold text-xs" data-bs-toggle="tooltip" data-bs-title="Edit Wallet" x-on:click="$wire.emitTo('component.wallet-group.wallet-group-modal', 'edit', '${val.uuid}')">
                                <span class=" tw__flex tw__items-center tw__gap-1">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span>Edit</span>
                                </span>
                            </a>
                        `);
                        // Create content
                        wrapper.innerHTML = `
                            <td>
                                <span class="text-sm text-dark font-weight-semibold">${val.name}</span>
                            </td>
                            <td>
                                <strong class="text-sm">${formatRupiah(val.balance)}</strong>
                            </td>
                            <td>
                                <strong class="text-sm">${item.join(', ')}</strong>
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
        };
    </script>
@endsection