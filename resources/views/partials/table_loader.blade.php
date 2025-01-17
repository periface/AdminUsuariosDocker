<!-- simula la tabla como si tuviera contenido -->
<table hidden class="loader" id="loader">
    <!-- solo el array de string -->
    @if ($columnas ?? '')
        <thead>
            <tr>
                @foreach ($columnas as $columna)
                    <th>{{ $columna }}</th>
                @endforeach
            </tr>
        </thead>
    @else
        <thead>
            <tr>
                <th>---</th>
            </tr>
        </thead>
    @endif
    <tbody>
        <tr>
            <div class="d-flex justify-content-center">
                <div class="spinner-border m-3" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </tr>
    </tbody>
</table>
