
<div class="form-group {{ $errors->has('amount') ? 'has-error' : ''}}">
    <label for="amount" class="control-label">{{ 'Monto' }}</label>
    <input class="form-control" name="amount" type="number" id="amount" value="{{ isset($solicitudarticulo->amount) ? $solicitudarticulo->amount : ''}}" >
    {!! $errors->first('amount', '<p class="help-block">:message</p>') !!}
</div>
<br>
<div class="form-group">
    <input class="btn btn-info" type="submit" value="{{ $formMode === 'edit' ? 'Actualizar' : 'Create' }}">
</div>
