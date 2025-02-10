<div id="dropZone" class="bg-slate-300 rounded-md text-center border-dotted border-1">
    <div class="p-5" <h1>Arrastra y suelta tu archivo aquí</h1>
        <p class="text-sm">o</p>
        <button type="button" class="btn btn-primary " id="js-file-trigger">Selecciona un archivo</button>
        <input type="file" class="hidden " id="js-file-input" accept=".pdf,.doc,.docx">
    </div>
</div>
<div id="anexos">
    <div class="row">
        @if (count($anexos) <= 0)
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fa-solid fa-info"></i> | No hay anexos
                </div>
            </div>
        @else
            @foreach ($anexos as $anexo)
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title
                    text-center">{{ $anexo->nombre }}</h5>
                            <p class="card-text text-center">
                            </p>
                        </div>
                    </div>
            @endforeach
        @endif
    </div>
</div>
