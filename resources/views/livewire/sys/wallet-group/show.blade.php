@section('parentTitle', 'Wallet Group: Detail')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-1 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('sys.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('sys.wallet.index') }}">Wallet</a></li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('sys.wallet.group.index') }}">Wallet Group</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Detail</li>
        </ol>
    </nav>
@endsection

<div id="wallet_group-detail">
    {{-- Because she competes with no one, no one can compete with her. --}}
    <div class="card border">
        <div class="card-header border-bottom pb-0">
            <div class="d-sm-flex align-items-center">
                <div>
                    <h6 class="font-weight-semibold text-lg mb-0">Wallet Group: Detail</h6>
                    <p class="text-sm">See detailed information about your Wallet Group</p>
                </div>
                <div class="ms-auto d-flex">
                    <a href="{{ route('sys.wallet.group.index') }}" class="btn btn-sm btn-white me-2">
                        <span class=" tw__flex tw__items-center tw__gap-2">
                            <i class="fa-solid fa-chevron-left"></i>
                            Back
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <tr>
                    <th class="text-sm">Name</th>
                    <td class="text-sm">{{ $data->name }}</td>
                </tr>
                <tr>
                    <th class="text-sm">Balance</th>
                    <td class="text-sm">{{ formatRupiah($data->getBalance()) }}</td>
                </tr>
            </table>

            <ul class="list-group">
                @if (!$data->walletGroupItem()->exists())
                    <li class="list-group-item">No data to be shown</li>
                @else
                    @foreach ($data->walletGroupItem as $item)
                        <li class="list-group-item">
                            <a href="javascript:void(0)" data-href="{{ route('sys.wallet.show', $item->uuid) }}" x-on:click="">
                                <div class=" tw__flex md:tw__items-center md:tw__justify-between tw__flex-col md:tw__flex-row tw__items-start">
                                    <span>{{ ($item->parent()->exists() ? $item->parent->name.' - ' : '').$item->name }}</span>
                                
                                    <div class=" tw__flex tw__flex-col tw__items-start md:tw__items-end tw__mt-2 md:tw__mt-0">
                                        <small>{{ formatRupiah($item->getBalance()) }}</small>
                                        <button class="btn btn-sm btn-primary tw__uppercase tw__mb-0" wire:click="$emitTo('component.wallet.wallet-adjustment-modal', 'edit', '{{ $item->uuid }}')">Adjustment</button>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>

@section('content_modal')
    @livewire('component.wallet.wallet-adjustment-modal', ['user' => \Auth::user()], key(\Auth::user()->id))
@endsection

@section('js_inline')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let container = document.getElementById('wallet_group-detail');
            if(container.querySelectorAll('a[data-href]').length > 0){
                container.querySelectorAll('a[data-href]').forEach(element => {
                    element.addEventListener('click', (e) => {
                        if(!((e.target).classList.contains('btn'))){
                            location.href = element.dataset.href;
                        }
                    });
                });
            }

            if(document.getElementById('wallet_adjustment-modal')){
                document.getElementById('wallet_adjustment-modal').addEventListener('hidden.bs.modal', (e) => {
                    @this.emit('refreshComponent')
                });
            }
        });
    </script>
@endsection
