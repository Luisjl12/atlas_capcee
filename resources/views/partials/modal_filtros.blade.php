<div id="{{ $id }}" class="modal">
    <div class="modal-content">
        <span class="close" data-close="{{ $id }}">&times;</span>
        <h3>{{ $titulo }}</h3>

        <form id="{{ $formId }}">
            {{ $slot }}
            <button type="submit">Aplicar filtros</button>
        </form>
    </div>
</div>