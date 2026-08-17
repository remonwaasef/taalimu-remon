@if ($paginator->hasPages())
    <nav class="d-flex justify-content-center mt-4" aria-label="{{ __('Pagination') }}">
        <ul class="pagination shadow-sm rounded-pill overflow-hidden flex-wrap">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item page-prev disabled" aria-disabled="true">
                    <span class="page-link border-0 bg-white text-muted" aria-label="{{ __('Previous') }}">&lsaquo;</span>
                </li>
            @else
                <li class="page-item page-prev">
                    <a class="page-link border-0 bg-white text-dark" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('Previous') }}">&lsaquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item page-ellipsis disabled" aria-disabled="true"><span class="page-link border-0 bg-white">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link border-0 bg-primary">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link border-0 bg-white text-dark" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item page-next">
                    <a class="page-link border-0 bg-white text-dark" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('Next') }}">&rsaquo;</a>
                </li>
            @else
                <li class="page-item page-next disabled" aria-disabled="true">
                    <span class="page-link border-0 bg-white text-muted" aria-label="{{ __('Next') }}">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
