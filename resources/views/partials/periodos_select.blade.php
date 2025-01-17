<select class="form-select" id="{{ $id }}" name="{{ $name }}">
    <option value="">Seleccione tipo de periodo</option>
    <option value="mensual" {{ $selected == 'mensual' ? 'selected' : '' }}>Mensual</option>
    <option value="bimestral" {{ $selected == 'bimestral' ? 'selected' : '' }}>Bimestral</option>
    <option value="trimestral" {{ $selected == 'trimestral' ? 'selected' : '' }}>Trimestral</option>
    <option value="semestral" {{ $selected == 'semestral' ? 'selected' : '' }}>Semestral</option>
    <option value="anual" {{ $selected == 'anual' ? 'selected' : '' }}>Anual</option>
</select>
