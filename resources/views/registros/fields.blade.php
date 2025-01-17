<input type="hidden" name="evaluacionId" id="evaluacionId" value="{{ $evaluacion['id'] }}" />
<input type="hidden" name="evaluable_formula" id="evaluable_formula" value="{{ $indicador['evaluable_formula'] }}" />
@foreach ($registros as $registro)
    <input type="hidden" name="registroId_{{ $loop->index }}" id="id_registro" value="{{ $registro['id'] }}" />
    <input type="hidden" name="code_{{ $loop->index }}" id="code_{{ $loop->index }}"
        value="{{ $registro['code'] }}" />

    <input type="hidden" name="variableId_{{ $loop->index }}" id="variableId_{{ $loop->index }}"
        value="{{ $registro['variableId'] }}" />
    <div class="form-group">
        <label for="registro_{{ $loop->index }}"
            class="form-label text-sm">{{ $registro['nombre_variable'] }}</label>
        <input class="form-control" type="number" id="registro_{{ $loop->index }}"
            name="registro_{{ $loop->index }}" placeholder="Valor" value="{{ $registro['valor'] ?? '' }}" />
    </div>
@endforeach
@if ($indicador['requiere_anexo'] == 1)
    <label for="anexo" class="form-label text-sm">Anexo: {{ $indicador['medio_verificacion'] }}</label>
    <input type="file" class="form-control" id="anexo" name="anexo" placeholder="Anexo">
    <small class="text-muted">El archivo debe ser en formato PDF</small>
@endif
