<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($desayuno, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group">
		{!! Form::label('fecha', 'Fecha:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::date('fecha', null, array('class' => 'form-control input-xs', 'id' => 'fecha', 'placeholder' => 'Ingrese fecha')) !!}
		</div>
	</div>
	<div class="form-group" hidden>
		{!! Form::label('dieta', 'Dieta:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::select('dieta', $cboDieta, null, array('class' => 'form-control input-xs', 'id' => 'tipo','onchange'=>'mostrarTipo(this.value)')) !!}
		</div>
	</div>
	<div class="form-group dieta" style="display: none;">
		{!! Form::label('jugo', 'Jugo de fruta:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('jugo', null, array('class' => 'form-control input-xs col-lg-7 col-md-7 col-sm-7', 'id' => 'jugo', 'placeholder' => 'Ingrese jugo')) !!}
			{!! Form::number('kcal_jugo', null, array('class' => 'form-control input-xs col-lg-3 col-md-3 col-sm-3', 'id' => 'kcal_jugo', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
			{!! Form::label('kcal', KCAL, array('class' => 'col-lg-2')) !!}
		</div>
	</div>
	<div class="form-group" hidden>
		{!! Form::label('fruta', 'Fruta/Postre:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('fruta', null, array('class' => 'form-control input-xs col-lg-7 col-md-7 col-sm-7', 'id' => 'fruta', 'placeholder' => 'Ingrese fruta')) !!}
			{!! Form::number('kcal_fruta', null, array('class' => 'form-control input-xs col-lg-3 col-md-3 col-sm-3', 'id' => 'kcal_fruta', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
			{!! Form::label('kcal', KCAL, array('class' => 'col-lg-2')) !!}
		</div>
	</div>
	<div class="form-group dieta" style="display: none;">
		{!! Form::label('cereal', 'Cereal Caliente:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('cereal', null, array('class' => 'form-control input-xs col-lg-7 col-md-7 col-sm-7', 'id' => 'cereal', 'placeholder' => 'Ingrese cereal')) !!}
			{!! Form::number('kcal_cereal', null, array('class' => 'form-control input-xs col-lg-3 col-md-3 col-sm-3', 'id' => 'kcal_cereal', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
			{!! Form::label('kcal', KCAL, array('class' => 'col-lg-2')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('fondo', 'Plato de Fondo o Sopa:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('fondo', null, array('class' => 'form-control input-xs col-lg-7 col-md-7 col-sm-7', 'id' => 'fondo', 'placeholder' => 'Ingrese fondo')) !!}
			{!! Form::number('kcal_fondo', null, array('class' => 'form-control input-xs col-lg-3 col-md-3 col-sm-3', 'id' => 'kcal_fondo', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
			{!! Form::label('kcal', KCAL, array('class' => 'col-lg-2')) !!}
		</div>
	</div>
	<div class="form-group" hidden>
		{!! Form::label('guarnicion1', 'Guarnicion 1:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('guarnicion1', null, array('class' => 'form-control input-xs col-lg-7 col-md-7 col-sm-7', 'id' => 'guarnicion1', 'placeholder' => 'Ingrese guarnicion1')) !!}
			{!! Form::number('kcal_guarnicion1', null, array('class' => 'form-control input-xs col-lg-3 col-md-3 col-sm-3', 'id' => 'kcal_guarnicion1', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
			{!! Form::label('kcal', KCAL, array('class' => 'col-lg-2')) !!}
		</div>
	</div>
	<div class="form-group" hidden>
		{!! Form::label('guarnicion2', 'Guarnicion 2:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('guarnicion2', null, array('class' => 'form-control input-xs col-lg-7 col-md-7 col-sm-7', 'id' => 'guarnicion2', 'placeholder' => 'Ingrese guarnicion2')) !!}
			{!! Form::number('kcal_guarnicion2', null, array('class' => 'form-control input-xs col-lg-3 col-md-3 col-sm-3', 'id' => 'kcal_guarnicion2', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
			{!! Form::label('kcal', KCAL, array('class' => 'col-lg-2')) !!}
		</div>
	</div>
	<div class="form-group " style="display: none;">
		{!! Form::label('huevo', 'Huevo a la Orden:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('huevo', null, array('class' => 'form-control input-xs col-lg-7 col-md-7 col-sm-7', 'id' => 'huevo', 'placeholder' => 'Ingrese huevo')) !!}
			{!! Form::number('kcal_huevo', null, array('class' => 'form-control input-xs col-lg-3 col-md-3 col-sm-3', 'id' => 'kcal_huevo', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
			{!! Form::label('kcal', KCAL, array('class' => 'col-lg-2')) !!}
		</div>
	</div>
	<div class="form-group " style="display: none;">
		{!! Form::label('pan', 'Pan:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('pan', null, array('class' => 'form-control input-xs col-lg-7 col-md-7 col-sm-7', 'id' => 'pan', 'placeholder' => 'Ingrese pan')) !!}
			{!! Form::number('kcal_pan', null, array('class' => 'form-control input-xs col-lg-3 col-md-3 col-sm-3', 'id' => 'kcal_pan', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
			{!! Form::label('kcal', KCAL, array('class' => 'col-lg-2')) !!}
		</div>
	</div>
	<div class="form-group " style="display: none;">
		{!! Form::label('cafe', 'Café o Infusión:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('cafe', null, array('class' => 'form-control input-xs col-lg-7 col-md-7 col-sm-7', 'id' => 'cafe', 'placeholder' => 'Ingrese cafe')) !!}
			{!! Form::number('kcal_cafe', null, array('class' => 'form-control input-xs col-lg-3 col-md-3 col-sm-3', 'id' => 'kcal_cafe', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
			{!! Form::label('kcal', KCAL, array('class' => 'col-lg-2')) !!}
		</div>
	</div>
	<div class="form-group " style="display: none;">
		{!! Form::label('opcional', 'Opcional:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('opcional', null, array('class' => 'form-control input-xs col-lg-7 col-md-7 col-sm-7', 'id' => 'opcional', 'placeholder' => 'Ingrese opcional')) !!}
			{!! Form::number('kcal_opcional', null, array('class' => 'form-control input-xs col-lg-3 col-md-3 col-sm-3', 'id' => 'kcal_opcional', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
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
	if(vValor=="N"){
		//$('.dieta').css("display","");
	}else{
		//$('.dieta').css("display","none");
	}
}
@if(!is_null($desayuno))
//mostrarTipo('{{$desayuno->dieta}}');
@endif
</script>