    <div class=" lg:tw__hidden">
        <div class=" tw__flex tw__items-center">
            <p class="font-weight-semibold mb-0 text-dark text-sm">
                {{-- @if ($paginate->total() > 0)
                    <span>Showing {{ $paginate->firstItem() }} to {{ $paginate->lastItem() }} of {{ $paginate->total() }} result{{ $paginate->total() > 1 ? 's' : '' }}</span>
                @else
                    <span>No data to be shown</span>
                @endif --}}
            </p>
            <div class="ms-auto">
                <button class="btn btn-sm btn-white mb-0">Previous</button>
                <button class="btn btn-sm btn-white mb-0">Next</button>
            </div>
        </div>
    </div>
    <div class=" tw__hidden lg:tw__block">
        <div class=" tw__flex tw__items-center">
            {{-- Prev Page --}}
            <button class="btn btn-sm btn-white d-sm-block d-none mb-0" {{ $paginator->onFirstPage() ? 'disabled' : '' }}>Previous</button>

            {{-- Specific Page --}}
            <nav aria-label="..." class="ms-auto">
                <ul class="pagination pagination-light mb-0">
                    <li class="page-item active" aria-current="page">
                        <span class="page-link font-weight-bold">1</span>
                    </li>
                    <li class="page-item"><a class="page-link border-0 font-weight-bold" href="javascript:;">2</a></li>
                    <li class="page-item"><a class="page-link border-0 font-weight-bold d-sm-inline-flex d-none" href="javascript:;">3</a></li>
                    <li class="page-item"><a class="page-link border-0 font-weight-bold" href="javascript:;">...</a></li>
                    <li class="page-item"><a class="page-link border-0 font-weight-bold d-sm-inline-flex d-none" href="javascript:;">8</a></li>
                    <li class="page-item"><a class="page-link border-0 font-weight-bold" href="javascript:;">9</a></li>
                    <li class="page-item"><a class="page-link border-0 font-weight-bold" href="javascript:;">10</a></li>
                </ul>
            </nav>

            {{-- Next Page --}}
            <button class="btn btn-sm btn-white d-sm-block d-none mb-0 ms-auto" {{ !($paginator->hasMorePages() ? 'disabled' : '') }}>Next</button>
        </div>
    </div>