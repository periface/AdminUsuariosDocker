<div id="dropZone" class="bg-slate-300 text-center border-dotted border-1">
    <div class="p-5">
        <h1 class="text-black">Arrastra y suelta tu archivo aquí</h1>
        <p class="text-sm">o</p>
        <button type="button" class="btn btn-primary " id="js-file-trigger">Selecciona un archivo</button>
        <input type="file" class="hidden" multiple id="js-file-input" accept=".pdf,.doc,.docx">
    </div>
</div>
<div id="anexos">
    @if (count($anexos) <= 0)
        <div class="grid grid-cols-1">
            <div class="justify-center w-full">
                <div class="alert alert-info">
                    <i class="fa-solid fa-info"></i> | No hay medios de verificación
                </div>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1">
            <ul class="w-full">
                <h6 class="text-md p-3 text-center text-red-950 font-bold">Medios de verificación cargados</h6>
                @foreach ($anexos as $anexo)
                    <li class="w-full shadow-md mb-2 p-3">
                        <div class="grid grid-cols-[80%_20%] justify-center align-middle items-center">
                            <div class="">
                                <a target="_blank" class="flex border-b-slate-400"
                                    href="{{ asset($anexo['filePath']) }}">
                                    <h6 class="text-md text-blue-600">{{ $anexo['fileName'] }}</h6>
                                    <i class="fa-solid fa-arrow-up-right-from-square ml-2"></i>
                                </a>
                            </div>
                            <div class="text-right">
                                <a data-id="{{ $anexo['id'] }}" class="btn btn-danger btn-sm js-delete-anexo">
                                    <i data-id="{{ $anexo['id'] }}" class="fa fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
