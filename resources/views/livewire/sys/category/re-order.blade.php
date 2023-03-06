@section('parentTitle', 'Category: Re Order')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-1 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('sys.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('sys.category.index') }}">Category</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Re-order</li>
        </ol>
    </nav>
@endsection

@section('css_plugins')
    {{-- Nestable --}}
    @include('layouts.plugins.nestable.css')
@endsection

@section('css_inline')
    <style>
        .nst-item .nst-content {
            display: block !important;
        }
    </style>
@endsection

<div>
    {{-- In work, do what you enjoy. --}}
    <div class="card border">
        <div class="card-header border-bottom pb-0">
            <div class="d-sm-flex align-items-center">
                <div>
                    <h6 class="font-weight-semibold text-lg mb-0">Category: Re-order</h6>
                    <p class="text-sm">Change how your category order</p>
                </div>
                <div class="ms-auto d-flex">
                    <a href="{{ route('sys.category.index') }}" class="btn btn-sm btn-white me-2">
                        <span class=" tw__flex tw__items-center tw__gap-2">
                            <i class="fa-solid fa-chevron-left"></i>
                            Back
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="sa-sortable" wire:ignore>
                <ol id="category-list">
                    @foreach ($categoryList_data as $item)
                        <li data-category_id="{{ $item->uuid }}">
                            <div class="nst-handle custom-handle">
                               <i class="fa-solid fa-grip-vertical"></i>
                            </div>
                            <span class="category-name" data-name="{{ $item->name }}">{{ $item->name }}</span>
        
                            @if ($item->child()->exists())
                                <ol>
                                    @foreach($item->child()->orderBy('order', 'asc')->get() as $child)
                                        <li data-category_id="{{ $child->uuid }}" data-parent_id="{{ $item->uuid }}">
                                            <div class="nst-handle custom-handle">
                                               <i class="fa-solid fa-grip-vertical"></i>
                                            </div>
                                            <span class="category-name" data-name="{{ $child->name }}"><p class="category_parent-name" data-name="{{ $item->name }}">{{ $item->name }} - </p>{{ $child->name }}</span>
                                        </li>
                                    @endforeach
                                </ol>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
</div>

@section('js_plugins')
    {{-- Nestable --}}
    @include('layouts.plugins.nestable.js')
@endsection

@section('js_inline')
    <script>
        var nestableInstance = null;
        const initNestable = () => {
            nestableInstance = new Nestable("#category-list", {
                maxDepth: 1,
                animation: 150,
            });

            // Listen to event
            nestableInstance.on("stop", (event) => {
                let activeEl = event.movedNode;
                let parentEl = event.newParentItem;
                let exitFunct = false;
                // Check if moved node has child
                activeEl.childNodes.forEach((el) => {
                    if(el.classList.contains('nst-list') && event.newParent && parentEl !== null){                    
                        exitFunct = true;
                    }
                }); 
                if(exitFunct){
                    // Sent alwert
                    // let alert = new CustomEvent('wire-action', {
                    //     detail: {
                    //         status: 'warning',
                    //         action: 'Failed',
                    //         message: `Parent data cannot be moved inside another parent data`
                    //     }
                    // });
                    // this.dispatchEvent(alert);    
                    
                    nestableInstance.destroy();
                    Livewire.emit('refreshComponent');
                    return;
                }

                if(parentEl === null || parentEl === undefined){
                    // ActiveEl is stand alone
                    if(event.newParent && event.originalParent !== event.newParent){
                        if(activeEl.hasAttribute('data-parent_id')){
                            // Check if activeEl has data-parent_id attribute
                            activeEl.removeAttribute('data-parent_id');
                        }

                        if(activeEl.querySelector('.category_parent-name')){
                            activeEl.querySelector('.category_parent-name').remove();
                        }
                    }
                } else {
                    // ActiveEl is moved to child position
                    if(!(activeEl.hasAttribute('data-parent_id'))){
                        activeEl.dataset.parent_id = parentEl.dataset.category_id;
                    }

                    if(!activeEl.querySelector('.category_parent-name')){
                        activeEl.querySelector('.category-name').insertAdjacentHTML('afterbegin', `
                            <p class="category_parent-name" data-name="${parentEl.querySelector('.category-name').dataset.name}">${parentEl.querySelector('.category-name').dataset.name} - </p>
                        `);
                    } else if(activeEl.dataset.parent_id !== parentEl.dataset.category_id){
                        console.log("Different parent El");
                        if(activeEl.querySelector('.category_parent-name')){
                            activeEl.querySelector('.category_parent-name').innerHTML = `${parentEl.querySelector('.category-name').dataset.name} - `;
                        }
                    }
                }

                let serialize = [];
                (event.hierarchy).forEach((e) => {
                    let child = [];
                    // Check if active el has child
                    if(e.children !== undefined){
                        (e.children).forEach((ec) => {
                            child.push({
                                'id': ec.node.dataset.category_id
                            });
                        });
                    }

                    if(child.length > 0){
                        // Push child arr if exists
                        serialize.push({
                            'id': e.node.dataset.category_id,
                            'child': child
                        });
                    } else {
                        serialize.push({
                            'id': e.node.dataset.category_id
                        });
                    }
                    
                });
                updateHierarchy(serialize);
            });
        };
        const updateHierarchy = (hierarchy) => {
            console.log("AAAA");
            let orderId = hierarchy.reduce(function (r, a) {
                r[a.id] = r[a.id] || [];
                r[a.id].push(a.child);
                return r;
            }, Object.create(null));

            Livewire.emit('reOrder', hierarchy);
            nestableInstance.destroy();
        }

        document.addEventListener('DOMContentLoaded', () => {
            window.dispatchEvent(new Event('categoryListInit-re_order'));
        });
        window.addEventListener('categoryListInit-re_order', (e) => {
            console.log('Category List Init');
            initNestable();
        });
    </script>
@endsection