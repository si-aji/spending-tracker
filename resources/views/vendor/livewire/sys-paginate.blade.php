{{-- Mobile --}}
<div class=" lg:tw__hidden">
    <p class="font-weight-semibold text-dark text-sm">
        @if ($paginator->total() > 0)
            <span>Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} result{{ $paginator->total() > 1 ? 's' : '' }}</span>
        @else
            <span>No data to be shown</span>
        @endif
    </p>
    <div class=" tw__flex tw__justify-between">
        {{-- Prev Page --}}
        @if ($paginator->onFirstPage())
            <a href="javascript:void(0)" class="btn btn-sm btn-white mb-0 tw__cursor-not-allowed tw__bg-gray-200" disabled>Previous</a>
        @else
            <button type="button" class="btn btn-sm btn-white mb-0" wire:click="previousPage">Previous</button>
        @endif

        {{-- Next Page --}}
        @if ($paginator->hasMorePages())
            <button type="button" class="btn btn-sm btn-white mb-0" wire:click="nextPage">Next</button>
        @else
            <a href="javascript:void(0)" class="btn btn-sm btn-white mb-0 tw__cursor-not-allowed tw__bg-gray-200" disabled>Next</a>
        @endif
    </div>
</div>

{{-- Desktop --}}
<div class=" tw__hidden lg:tw__block">
    <div class=" tw__flex tw__items-center">
        {{-- Prev Page --}}
        @if ($paginator->onFirstPage())
            <a href="javascript:void(0)" class="btn btn-sm btn-white d-sm-block d-none mb-0 tw__cursor-not-allowed tw__bg-gray-200" disabled>Previous</a>
        @else
            <button type="button" class="btn btn-sm btn-white d-sm-block d-none mb-0" wire:click="previousPage">Previous</button>
        @endif

        {{-- Pagination Elements --}}
        <nav aria-label="..." class=" tw__mx-auto">
            <ul class="pagination pagination-light mb-0">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item">
                            <a class="page-link border-0 font-weight-bold" href="javascript:void(0);">...</a>
                        </li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page">
                                    <span class="page-link font-weight-bold">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <button type="button"  wire:click="gotoPage({{ $page }})" class="page-link border-0 font-weight-bold">{{ $page }}</button>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </ul>
        </nav>

        {{-- Next Page --}}
        @if ($paginator->hasMorePages())
            <button type="button" class="btn btn-sm btn-white d-sm-block d-none mb-0" wire:click="nextPage">Next</button>
        @else
            <a href="javascript:void(0)" class="btn btn-sm btn-white d-sm-block d-none mb-0 tw__cursor-not-allowed tw__bg-gray-200" disabled>Next</a>
        @endif
    </div>
</div>