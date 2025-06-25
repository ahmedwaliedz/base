@if ($paginator->hasPages())
    <nav aria-label="Page navigation" class="mt-4 d-flex justify-content-center">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item prev disabled">
                    <a class="page-link waves-effect" href="javascript:void(0);"><i class="ti ti-chevrons-left ti-xs"></i></a>
                </li>
            @else
                <li class="page-item prev">
                    <a class="page-link waves-effect" href="{{ $paginator->previousPageUrl() }}"><i class="ti ti-chevrons-left ti-xs"></i></a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled">
                        <a class="page-link waves-effect" href="javascript:void(0);">{{ $element }}</a>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <a class="page-link waves-effect" href="javascript:void(0);">{{ $page }}</a>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link waves-effect" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item next">
                    <a class="page-link waves-effect" href="{{ $paginator->nextPageUrl() }}"><i class="ti ti-chevrons-right ti-xs"></i></a>
                </li>
            @else
                <li class="page-item next disabled">
                    <a class="page-link waves-effect" href="javascript:void(0);"><i class="ti ti-chevrons-right ti-xs"></i></a>
                </li>
            @endif
        </ul>
    </nav>
@endif
