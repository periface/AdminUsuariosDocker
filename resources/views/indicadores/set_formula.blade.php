            @if($indicador && $indicador->id)
                <input type="hidden" name="id" value="{{ $indicador->id }}">
                <input type="hidden" name="dimensionId" value="{{ $indicador["dimensionId"]}}">
                <input type="hidden" id="indicador_confirmado" name="indicador_confirmado" value="{{ $indicador["indicador_confirmado"] }}">
            @endif
            @if(!$indicador || !$indicador->id)
                <input type="hidden" name="dimensionId" value="{{ $dimensionId }}">
            @endif
            <input class="form-control" type="hidden" id="nombre" name="nombre" placeholder="Nombre del indicador" value="{{ $indicador["nombre"] ?? "" }}">
            <input class="form-control" type="hidden" id="descripcion" name="descripcion" placeholder="Descripción" value="{{ $indicador["descripcion"] ??"" }}">
            <input class="form-control" type="hidden" id="unidad_medida" name="unidad_medida" value="{{ $indicador["unidad_medida"] ??"" }}">
            <input class="form-control" type="hidden" id="status" name="status" value="{{ $indicador["status"] ??"" }}">
            <input class="form-control" type="hidden" id="sentido" name="sentido" value="{{ $indicador["sentido"] ??"" }}">
            <input class="form-control" type="hidden" id="requiere_anexo" name="requiere_anexo" value="{{ $indicador["requiere_anexo"] ??"" }}">
            <input class="form-control" type="hidden" id="medio_verificacion" name="medio_verificacion" value="{{ $indicador["medio_verificacion"] ??"" }}">
            <div class="mt-2">
                <label for="metodo_calculo" class="form-label text-sm">Método de Cálculo: </label>
                @if($indicador && $indicador["indicador_confirmado"])
                    <input class="form-control" type="hidden" id="metodo_calculo" name="metodo_calculo" value="{{ $indicador["metodo_calculo"] ?? "" }}">
                    <textarea rows="5" disabled class="form-control metodo_calculo disabled" id="metodo_calculo" name="metodo_calculo" placeholder="Método de Cálculo">{{ $indicador["metodo_calculo"] ?? "" }}</textarea>
                @else
                    <textarea rows="5" class="form-control metodo_calculo editable" id="metodo_calculo" name="metodo_calculo" placeholder="Método de Cálculo">{{ $indicador["metodo_calculo"] ?? "" }}</textarea>
                @endif
<h6 class="mt-2">Ejemplo</h6>
                <p>
 <span class="font-bold text-blue-500">(</span><span class="text-pink-900">Expedientes de contratos de arrendamiento devueltos con observaciones</span> <span class="font-bold text-blue-500">/</span> <span class="text-pink-900">Expedientes para generar contratos de arrendamiento</span><span class="text-blue-500 font-bold">)</span> <span class="text-blue-500 font-bold">*</span> <span class="text-pink-900">100</span>
                </p>
            </div>
            <div class="mt-2">
                <label for="js-formula" class="form-label text-md">Formula:</label>
                <div id="js-formula">
                </div>
            </div>
