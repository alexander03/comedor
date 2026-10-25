<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($refrigerio, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group">
		{!! Form::label('fecha', 'Fecha:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::date('fecha', null, array('class' => 'form-control input-xs', 'id' => 'fecha', 'placeholder' => 'Ingrese fecha')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('tipo', 'Tipo:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::select('tipo', $cboTipo, null, array('class' => 'form-control input-xs', 'id' => 'tipo','onchange'=>'mostrarTipo(this.value)')) !!}
		</div>
	</div>
	<div class="form-group" hidden>
		{!! Form::label('bebida', 'Bebida:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('bebida', null, array('class' => 'form-control input-xs col-lg-6 col-md-6 col-sm-6', 'id' => 'bebida', 'placeholder' => 'Ingrese bebida')) !!}
			{!! Form::number('kcal_bebida', null, array('class' => 'form-control input-xs col-lg-3 col-md-3 col-sm-3', 'id' => 'kcal_bebida', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
			{!! Form::label('kcal', KCAL, array('class' => 'col-lg-2')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('sandwich', 'Principal:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('sandwich', null, array('class' => 'form-control input-xs col-lg-6 col-md-6 col-sm-6', 'id' => 'sandwich', 'placeholder' => 'Ingrese principal')) !!}
			{!! Form::number('kcal_sandwich', null, array('class' => 'form-control input-xs col-lg-3 col-md-3 col-sm-3', 'id' => 'kcal_sandwich', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
			{!! Form::label('kcal', KCAL, array('class' => 'col-lg-2')) !!}
		</div>
	</div>
	<div class="form-group" hidden>
		{!! Form::label('complemento', 'Complemento:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('complemento', null, array('class' => 'form-control input-xs col-lg-6 col-md-6 col-sm-6', 'id' => 'complemento', 'placeholder' => 'Ingrese complemento')) !!}
			{!! Form::number('kcal_complemento', null, array('class' => 'form-control input-xs col-lg-3 col-md-3 col-sm-3', 'id' => 'kcal_complemento', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
			{!! Form::label('kcal', KCAL, array('class' => 'col-lg-2')) !!}
		</div>
	</div>
	<div class="form-group" hidden>
		{!! Form::label('fruta', 'Fruta:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('fruta', null, array('class' => 'form-control input-xs col-lg-6 col-md-6 col-sm-6', 'id' => 'fruta', 'placeholder' => 'Ingrese fruta')) !!}
			{!! Form::number('kcal_fruta', null, array('class' => 'form-control input-xs col-lg-3 col-md-3 col-sm-3', 'id' => 'kcal_fruta', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
			{!! Form::label('kcal', KCAL, array('class' => 'col-lg-2')) !!}
		</div>
	</div>
	<div class="form-group desayuno" style="display: none;">
		{!! Form::label('extra', 'Extra:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('extra', null, array('class' => 'form-control input-xs col-lg-6 col-md-6 col-sm-6', 'id' => 'extra', 'placeholder' => 'Ingrese extra')) !!}
			{!! Form::number('kcal_extra', null, array('class' => 'form-control input-xs col-lg-3 col-md-3 col-sm-3', 'id' => 'kcal_extra', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
			{!! Form::label('kcal', KCAL, array('class' => 'col-lg-2')) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-check "></i> '.$boton, array('class' => 'btn btn-primary btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-undo "></i> Cancelar', array('class' => 'btn btn-default btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('600');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
}); 
function mostrarTipo(vValor){
	if(vValor=="Refrigerio Comun"){
		$('.desayuno').css("display","");
	}else{
		$('.desayuno').css("display","none");
	}
}
@if(!is_null($refrigerio))
mostrarTipo('{{$refrigerio->tipo}}');
@endif
</script>