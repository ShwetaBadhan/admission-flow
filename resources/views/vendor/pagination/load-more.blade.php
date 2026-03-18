@if ($paginator->hasPages())
    <nav class="load-btn text-center mt-5">
        <ul class="pagination justify-content-center mb-0">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link btn btn-light">Previous</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link btn btn-light" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>
                </li>
            @endif

            {{-- Load More Button (Next Page) --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link btn btn-primary" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        <i class="ti ti-loader me-1"></i> Load More
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link text-muted">All Loaded ✓</span>
                </li>
            @endif
        </ul>
    </nav>
@endif