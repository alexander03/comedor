@php
if(is_null($programa)){
    $anio = date("Y");
}else{
    $anio = $programa->anio;
}

@endphp
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($programa, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="row">
		<div class="col-md-2 col-lg-2">
        	<div class="form-group">
        		{!! Form::label('anio', 'Año:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::select('anio',$cboAnio, $anio, array('class' => 'form-control input-xs', 'id' => 'anio')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-5 col-lg-5">
            <div class="form-group">
        		{!! Form::label('area_id', 'Area', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::select('area_id', $cboArea, null, array('class' => 'form-control input-xs', 'id' => 'area_id')) !!}
        		</div>
        	</div>
        </div>
    </div>
    <div class="row">
		<div class="col-md-2 col-lg-2">
        	<div class="form-group">
        		{!! Form::label('orden', 'Orden:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::number('orden', null, array('class' => 'form-control input-xs', 'id' => 'orden')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-10 col-lg-10">
            <div class="form-group">
        		{!! Form::label('nombre', 'Detalle:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::text('nombre', null, array('class' => 'form-control input-xs', 'id' => 'nombre', 'placeholder' => 'Ingrese detalle')) !!}
        		</div>
        	</div>
        </div>
	</div>
	<div class="row">
		<div class="col-md-6 col-lg-6">
        	<div class="form-group">
        		{!! Form::label('descripcion', 'Descripcion:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::text('descripcion', null, array('class' => 'form-control input-xs', 'id' => 'descripcion')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-6 col-lg-6">
        	<div class="form-group">
        		{!! Form::label('responsable', 'Responsable:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::text('responsable', null, array('class' => 'form-control input-xs', 'id' => 'responsable')) !!}
        		</div>
        	</div>
        </div>
    </div>
    <div class="row">
		<div class="col-md-3 col-lg-3">
        	<div class="form-group">
        		{!! Form::label('frecuencia', 'Frecuencia:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::select('frecuencia', $cboFrecuencia, null, array('class' => 'form-control input-xs', 'id' => 'frecuencia')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-3 col-lg-3">
        	<div class="form-group">
        		{!! Form::label('mes', 'Mes Inicio:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::select('mes', $cboMes, null, array('class' => 'form-control input-xs', 'id' => 'mes')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-6 col-lg-6">
        	<div class="form-group">
        		{!! Form::label('observacion', 'Observacion:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::textarea('observacion', null, array('class' => 'form-control input-xs', 'id' => 'observacion','rows'=>'3')) !!}
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

var equipo = new Bloodhound({
	datumTokenizer: function (d) {
		return Bloodhound.tokenizers.whitespace(d.value);
	},
	queryTokenizer: Bloodhound.tokenizers.whitespace,
	remote: {
		url: 'calibracion/equipoautocompletar/%QUERY',
		filter: function (equipo) {
			return $.map(equipo, function (movie) {
				return {
					value: movie.value,
					id: movie.id,
				};
			});
		}
	}
});
equipo.initialize();
$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="equipo"]').typeahead(null,{
	displayKey: 'value',
	source: equipo.ttAdapter()
}).on('typeahead:selected', function (object, datum) {
	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="equipo"]').val(datum.value);
    $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="equipo_id"]').val(datum.id);
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

function guardar2 (entidad, idboton) {
    var band=true;
	var msg="";
	var contador = 0;
    if(band && contador==0){
        contador=1;
    	var idformulario = IDFORMMANTENIMIENTO + entidad;
    	var data         = submitForm(idformulario);
    	var respuesta    = '';
    	var btn = $(idboton);
    	btn.button('loading');
    	data.done(function(msg) {
    		respuesta = msg;
    	}).fail(function(xhr, textStatus, errorThrown) {
    		respuesta = 'ERROR';
            contador=0;
    	}).always(function() {
    		btn.button('reset');
            contador=0;
    		if(respuesta === 'ERROR'){
    		}else{
    		 	// alert(respuesta);
				var dat = JSON.parse(respuesta);
                if(dat[0]!==undefined){
                    resp=dat[0].respuesta;    
                }else{
                    resp='VALIDACION';
				}
    			if (resp === 'OK') {
    			    enviarArchivo(dat[0].programa_id , dat[0].accion);
    				cerrarModal();
                    buscarCompaginado('', 'Accion realizada correctamente', entidad, 'OK');
    			} else if(resp === 'ERROR') {
    				alert(dat[0].msg);
    			} else {
    				mostrarErrores(respuesta, idformulario, entidad);
    			}
    		}
    	});
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

function enviarArchivo(idcompra ,accion){
    //var form = $('#formMantenimientoCompra')[0];
	//var formulario = new FormData(form);
	
    var data = new FormData();
    jQuery.each($(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="archivo"]')[0].files, function(i, file) {
        data.append('file-'+i, file);
    });
    data.append("id",idcompra);
    data.append("accion",accion);
    $.ajax({
		url: '{{ url("/programa/archivos") }}',
		headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
		type: 'POST',
		enctype: 'multipart/form-data',
		data: data,
		processData: false,
		contentType: false,
		cache: false,
		timeout: 600000
	});

	console.log('archivo enviado');
}


</script>