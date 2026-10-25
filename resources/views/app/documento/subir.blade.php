<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($documento, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	{!! Form::hidden('situacion', $situacion, array('id' => 'situacion')) !!}
	<div class="row">
		<div class="col-md-3 col-lg-3">
        	<div class="form-group">
        		{!! Form::label('tipodocumento_id', 'Tipo Doc.:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::select('tipodocumento_id',$cboTipodocumento, null, array('class' => 'form-control input-xs', 'id' => 'tipodocumento_id','readonly'=>'true')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-9 col-lg-9">
            <div class="form-group">
        		{!! Form::label('descripcion', 'Nombre:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::text('descripcion', null, array('class' => 'form-control input-xs', 'id' => 'descripcion', 'placeholder' => 'Ingrese descripcion','readonly'=>'true')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="form-group">
        	    {!! Form::label('archivo', 'Archivo', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
        		<div class="col-lg-3 col-md-3 col-sm-3">
        			{!! Form::file('archivo', null, array('class' => 'form-control input-xs', 'id' => 'archivo')) !!}
        		</div>
        	</div>
        </div>
    </div>
	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
		    <img id="cargando" src="dist/img/cargando.gif" style="display:none;" width='80px;' />
			{!! Form::button('<i class="fa fa-check "></i> '.$boton, array('class' => 'btn btn-primary btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar2(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-undo "></i> Cancelar', array('class' => 'btn btn-default btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
  <style>
	  .select2-container--default .select2-selection--single {
				border: 1px solid #ced4da;
				padding: .46875rem .75rem;
				height: calc(2.25rem + 2px);
		}
		</style>	
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('900');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
}); 


function guardar2 (entidad, idboton) {
    var band=true;
	var msg="";
	var contador = 0;
	$("#btnGuardar").css("display","none");
    if(band && contador==0){
        contador=1;
        mostrarMensaje("Subiendo archivo...", "OK");
        $("#cargando").css("display","");
        enviarArchivo({{$documento->id }} , 'store',entidad);
    }else{
        alert("Corregir los sgtes errores: \n"+msg);
    }
}

function submitForm2 (idformulario) {
	var parametros = $(idformulario).serialize();
	var accion     = $(idformulario).attr('action');
	var metodo     = $(idformulario).attr('method');
	var respuesta  = $.ajax({
		url : accion,
		type: metodo,
		data: parametros
	});
	return respuesta;
}

function enviarArchivo(idcompra ,accion,entidad){
    //var form = $('#formMantenimientoCompra')[0];
	//var formulario = new FormData(form);
	
    var data = new FormData();
    jQuery.each($(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="archivo"]')[0].files, function(i, file) {
        data.append('file-'+i, file);
    });
    data.append("id",idcompra);
    data.append("situacion",$(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="situacion"]').val());
    data.append("accion",accion);
    $.ajax({
		url: '{{ url("/documento/archivos2") }}',
		headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
		type: 'POST',
		enctype: 'multipart/form-data',
		data: data,
		processData: false,
		contentType: false,
		cache: false,
		timeout: 600000
	}).done(function() {
	    cerrarModal();
        buscarCompaginado('', 'Accion realizada correctamente', entidad, 'OK');
    });
	//console.log('archivo enviado');
}


</script>