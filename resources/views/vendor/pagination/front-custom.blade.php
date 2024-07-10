@if ($paginator->hasPages())
    <div class="col-md-12 d-flex justify-content-center">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <a class="previous" href="javascript:void(0)">&lsaquo;</a>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}">&lsaquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li><a href="javascript:void(0)">{{ $element }}</a></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active"><a href="javascript:void(0)">{{ $page }}</a>
                            </li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach


            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
            @else
                <li><a class="next" href="javascript:void(0)">&rsaquo;</a></li>
            @endif
        </ul>
    </div>
@endif
