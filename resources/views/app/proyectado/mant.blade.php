<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($proyectado, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group">
		{!! Form::label('comedor_id', 'Comedor:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::select('comedor_id', $cboComedor, null, array('class' => 'form-control input-xs', 'id' => 'comedor_id','onchange'=>'calcularComida();')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('fecha', 'Fecha:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			@if($user->usertype_id==1 || $user->usertype_id==35)
				{!! Form::date('fecha', null, array('class' => 'form-control input-xs', 'id' => 'fecha','onchange'=>'calcularComida();')) !!}
			@else
				{!! Form::date('fecha', null, array('class' => 'form-control input-xs', 'id' => 'fecha','onchange'=>'calcularComida();', 'max'=>date("Y-m-d",strtotime('+1 day')))) !!}
			@endif
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 text-center" id="divDetalle" style="color: blue;font-weight: bold;margin-top: 10px;">
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('desayuno', 'Desayuno:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::number('desayuno', null, array('class' => 'form-control input-xs', 'id' => 'desayuno')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('almuerzo', 'Almuerzo:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::number('almuerzo', null, array('class' => 'form-control input-xs', 'id' => 'almuerzo')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('cena', 'Cena:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::number('cena', null, array('class' => 'form-control input-xs', 'id' => 'cena')) !!}
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
	configurarAnchoModal('500');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
}); 

function calcularComida(){
	if($(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="comedor_id"]').val()!="" && $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="fecha"]').val()!=""){
	    $.ajax({
	        type: "POST",
	        url: "proyectado/calcularComida",
	        data: "comedor_id="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="comedor_id"]').val()+"&fecha="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="fecha"]').val()+"&_token="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="_token"]').val(),
	        success: function(a) {
	            $("#divDetalle").html(a);
		    }
	    });
	}
}
</script>