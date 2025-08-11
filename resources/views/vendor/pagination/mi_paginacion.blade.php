@if ($paginator->hasPages())
<nav class="pagination-container" aria-label="Navegación de páginas">
    <ul class="pagination">

        {{-- Botón anterior --}}
        @if ($paginator->onFirstPage())
        <li class="page-item disabled">
            <span class="page-link">&laquo;</span>
        </li>
        @else
        <li class="page-item">
            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
        </li>
        @endif

        {{-- Números de página --}}
        @foreach ($elements as $element)
        {{-- Separador ... --}}
        @if (is_string($element))
        <li class="page-item disabled">
            <span class="page-link">{{ $element }}</span>
        </li>
        @endif

        {{-- Links de página --}}
        @if (is_array($element))
        @foreach ($element as $page => $url)
        <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
        </li>
        @endforeach
        @endif
        @endforeach

        {{-- Botón siguiente --}}
        @if ($paginator->hasMorePages())
        <li class="page-item">
            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
        </li>
        @else
        <li class="page-item disabled">
            <span class="page-link">&raquo;</span>
        </li>
        @endif

    </ul>
</nav>
@endif