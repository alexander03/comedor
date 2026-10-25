<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($almuerzo, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="row">
		<div class="form-group">
			{!! Form::label('fecha', 'Fecha:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
			<div class="col-lg-12 col-md-12 col-sm-12">
				{!! Form::date('fecha', null, array('class' => 'form-control input-xs', 'id' => 'fecha', 'placeholder' => 'Ingrese fecha')) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('dieta', 'Dieta:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
			<div class="col-lg-12 col-md-12 col-sm-12">
				{!! Form::select('dieta', $cboDieta, null, array('class' => 'form-control input-xs', 'id' => 'tipo','onchange'=>'mostrarTipo(this.value)')) !!}
			</div>
		</div>
	</div>
	<div class="form-group " style="display: none;">
		{!! Form::label('salad', 'Salad Bar:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('salad', null, array('class' => 'form-control input-xs col-lg-6 col-md-6 col-sm-6', 'id' => 'salad', 'placeholder' => 'Ingrese salad')) !!}
			{!! Form::number('kcal_salad', null, array('class' => 'form-control input-xs col-lg-3 col-md-3 col-sm-3', 'id' => 'kcal_salad', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
			{!! Form::label('kcal', KCAL, array('class' => 'col-lg-2')) !!}
		</div>
	</div>
	<div class="form-group " style="display: none;">
		{!! Form::label('entrada1', 'Entrada a Campo:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('entrada1', null, array('class' => 'form-control input-xs col-lg-7 col-md-7 col-sm-7', 'id' => 'entrada1', 'placeholder' => 'Ingrese entrada a campo')) !!}
			{!! Form::number('kcal_entrada1', null, array('class' => 'form-control input-xs col-lg-3 col-md-3 col-sm-3', 'id' => 'kcal_entrada1', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
			{!! Form::label('kcal', KCAL, array('class' => 'col-lg-2')) !!}
		</div>
	</div>
	<div class="form-group " style="display: none;">
		{!! Form::label('entrada2', 'Entrada:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('entrada2', null, array('class' => 'form-control input-xs col-lg-7 col-md-7 col-sm-7', 'id' => 'entrada2', 'placeholder' => 'Ingrese entrada')) !!}
			{!! Form::number('kcal_entrada2', null, array('class' => 'form-control input-xs col-lg-3 col-md-3 col-sm-3', 'id' => 'kcal_entrada2', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
			{!! Form::label('kcal', KCAL, array('class' => 'col-lg-2')) !!}
		</div>
	</div>
	<div class="form-group " style="display: none;">
		{!! Form::label('sopa', 'Sopa:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('sopa', null, array('class' => 'form-control input-xs col-lg-6 col-md-6 col-sm-6', 'id' => 'sopa', 'placeholder' => 'Ingrese sopa')) !!}
			{!! Form::number('kcal_sopa', null, array('class' => 'form-control input-xs col-lg-3 col-md-3 col-sm-3', 'id' => 'kcal_sopa', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
			{!! Form::label('kcal', KCAL, array('class' => 'col-lg-2')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('fondo', 'Plato de Fondo 1:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('fondo', null, array('class' => 'form-control input-xs col-lg-7 col-md-7 col-sm-7', 'id' => 'fondo', 'placeholder' => 'Ingrese fondo')) !!}
			{!! Form::number('kcal_fondo', null, array('class' => 'form-control input-xs col-lg-2 col-md-2 col-sm-2', 'id' => 'kcal_fondo', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
			{!! Form::label('kcal_fondo', KCAL, array('class' => 'form-control input-xs col-lg-2 col-md-2 col-sm-2')) !!}
		</div>
	</div>
	<div class="form-group" style="display: none;">
		{!! Form::label('guarnicion1', 'Plato de Fondo 2:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('guarnicion1', null, array('class' => 'form-control input-xs col-lg-7 col-md-7 col-sm-7', 'id' => 'guarnicion1', 'placeholder' => 'Ingrese guarnicion1')) !!}
			{!! Form::number('kcal_guarnicion1', null, array('class' => 'form-control input-xs col-lg-2 col-md-2 col-sm-2', 'id' => 'kcal_guarnicion1', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
			{!! Form::label('kcal_fondo', KCAL, array('class' => 'form-control input-xs col-lg-2 col-md-2 col-sm-2')) !!}
		</div>
	</div>
	<div class="form-group" hidden>
		{!! Form::label('guarnicion2', 'Guarnicion 2:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('guarnicion2', null, array('class' => 'form-control input-xs col-lg-6 col-md-6 col-sm-6', 'id' => 'guarnicion2', 'placeholder' => 'Ingrese guarnicion2')) !!}
			{!! Form::number('kcal_guarnicion2', null, array('class' => 'form-control input-xs col-lg-3 col-md-3 col-sm-3', 'id' => 'kcal_guarnicion2', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
			{!! Form::label('kcal', KCAL, array('class' => 'col-lg-2')) !!}
		</div>
	</div>
	<div class="form-group dieta" style="display: none;">
		{!! Form::label('refresco', 'Refresco:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('refresco', null, array('class' => 'form-control input-xs col-lg-7 col-md-7 col-sm-7', 'id' => 'refresco', 'placeholder' => 'Ingrese refresco')) !!}
			{!! Form::number('kcal_refresco', null, array('class' => 'form-control input-xs col-lg-3 col-md-3 col-sm-3', 'id' => 'kcal_refresco', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
			{!! Form::label('kcal', KCAL, array('class' => 'col-lg-2')) !!}
		</div>
	</div>
	<div class="form-group" hidden>
		{!! Form::label('fruta', 'Postre o Fruta:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12 row">
			{!! Form::text('fruta', null, array('class' => 'form-control input-xs col-lg-7 col-md-67col-sm-7', 'id' => 'fruta', 'placeholder' => 'Ingrese fruta')) !!}
			{!! Form::number('kcal_fruta', null, array('class' => 'form-control input-xs col-lg-3 col-md-3 col-sm-3', 'id' => 'kcal_fruta', 'placeholder' => 'Ingrese kcal','style'=>'margin-left:10px')) !!}
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
	configurarAnchoModal('900');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
}); 
function mostrarTipo(vValor){
	if(vValor=="N"){
		//$('.dieta').css("display","");
	}else{
		//$('.dieta').css("display","none");
	}
}
@if(!is_null($almuerzo))
mostrarTipo('{{$almuerzo->dieta}}');
@endif
</script>