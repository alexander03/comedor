<?php
if(is_null($equipo)){
	$categoria_id=0;
}else{
	$categoria_id=(is_null($equipo->subcategoria)?0:$equipo->subcategoria->categoria_id);
}
?>
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($equipo, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
    <div class="form-group">
		{!! Form::label('categoria_id', 'Categoria', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::select('categoria_id', $cboCategoria, $categoria_id, array('class' => 'form-control input-xs', 'id' => 'categoria_id')) !!}
		</div>
	</div>
    <div class="form-group">
		{!! Form::label('subcategoria_id', 'Subcategoria', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::select('subcategoria_id', $cboSubcategoria, null, array('class' => 'form-control input-xs', 'id' => 'subcategoria_id', 'style'=>'width:100%')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('codigo', 'Codigo:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::text('codigo', null, array('class' => 'form-control input-xs', 'id' => 'codigo', 'placeholder' => 'Ingrese codigo')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('descripcion', 'Descripcion:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::text('descripcion', null, array('class' => 'form-control input-xs', 'id' => 'descripcion', 'placeholder' => 'Ingrese descripcion')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('marca', 'Marca:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::text('marca', null, array('class' => 'form-control input-xs', 'id' => 'marca', 'placeholder' => 'Ingrese marca')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('modelo', 'Modelo:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::text('modelo', null, array('class' => 'form-control input-xs', 'id' => 'modelo', 'placeholder' => 'Ingrese modelo')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('serie', 'Serie:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::text('serie', null, array('class' => 'form-control input-xs', 'id' => 'serie', 'placeholder' => 'Ingrese serie')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('fechaadquisicion', 'Fecha Adq.:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::date('fechaadquisicion', null, array('class' => 'form-control input-xs', 'id' => 'fechaadquisicion')) !!}
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
$('#subcategoria_id').select2({
	dropdownParent: $('.modal'),
	ajax: {
		url: "calibracion/subcategoriaautocompletar",
		dataType: 'json',
		delay: 250,
		data: function(params){
			return{
				q: $.trim(params.term),
				idcat: ($('#categoria_id').val())?($('#categoria_id').val()):'0',
			};
		},
		processResults: function(data){
			return{
				results: data
			};
		}
		
	}
});

$('#categoria_id').on('change',function(){
	$('#subcategoria_id').val(null).trigger('change');
});

</script>