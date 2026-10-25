<?php
use App\Person;
if(is_null($calibracion)){
    if(is_null($equipo)){
	    $equipo2 = '';
	    $categoria_id=0;
	    $subcategoria_id = null;
	    $equipo_id = null;
    }else{
        $equipo2 = $equipo->descripcion.' - '.$equipo->marca.' - '.$equipo->modelo.' - '.$equipo->serie;
	    $categoria_id=$equipo->subcategoria->categoria_id; 
	    $subcategoria_id = $equipo->subcategoria->id;
	    $equipo_id = $equipo->id;
    }
    $anio = date('Y');
}else{
	if(!is_null($calibracion->equipo)){
		$equipo2 = $calibracion->equipo->descripcion.' - '.$calibracion->equipo->marca.' - '.$calibracion->equipo->modelo.' - '.$calibracion->equipo->serie;
	}else{
		$equipo2 = "";
	}
	$categoria_id=$calibracion->subcategoria->categoria_id;
	$subcategoria_id = $calibracion->subcategoria_id;
	$equipo_id = $calibracion->equipo_id;
    $anio = $calibracion->anio;
}
?>
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($calibracion, $formData) !!}	
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
        		{!! Form::label('categoria_id', 'Categoria', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::select('categoria_id', $cboCategoria, $categoria_id, array('class' => 'form-control input-xs', 'id' => 'categoria_id')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-5 col-lg-5">
            <div class="form-group">
        		{!! Form::label('subcategoria_id', 'Subcategoria', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::select('subcategoria_id', $cboSubcategoria, $subcategoria_id, array('class' => 'form-control input-xs', 'id' => 'subcategoria_id', 'style'=>'width:100%')) !!}
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
        		{!! Form::label('equipo', 'Equipo:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::text('equipo', $equipo2, array('class' => 'form-control input-xs', 'id' => 'equipo', 'placeholder' => 'Ingrese equipo')) !!}
        			{!! Form::hidden('equipo_id', $equipo_id, array('id' => 'equipo_id')) !!}
        		</div>
        	</div>
        </div>
	</div>
	<div class="row">
		<div class="col-md-6 col-lg-6">
        	<div class="form-group">
        		{!! Form::label('frente', 'Frente de Ubicación:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::text('frente', null, array('class' => 'form-control input-xs', 'id' => 'frente')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-6 col-lg-6">
        	<div class="form-group">
        		{!! Form::label('area', 'Area Responsable:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::text('area', null, array('class' => 'form-control input-xs', 'id' => 'area')) !!}
        		</div>
        	</div>
        </div>
    </div>
    <div class="row">
		<div class="col-md-4 col-lg-4">
        	<div class="form-group">
        		{!! Form::label('frecuenciacalibracion', 'Frecuencia Calibración:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::text('frecuenciacalibracion', null, array('class' => 'form-control input-xs', 'id' => 'frecuenciacalibracion')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-4 col-lg-4">
        	<div class="form-group">
        		{!! Form::label('frecuenciaverificacion', 'Frecuencia Verificación:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::text('frecuenciaverificacion', null, array('class' => 'form-control input-xs', 'id' => 'frecuenciaverificacion')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-4 col-lg-4">
        	<div class="form-group">
        		{!! Form::label('codigocertificado', 'Codigo Certificado:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::text('codigocertificado', null, array('class' => 'form-control input-xs', 'id' => 'codigocertificado')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-4 col-lg-4">
        	<div class="form-group">
        		{!! Form::label('fechacertificado', 'Fecha Certificado:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::date('fechacertificado', null, array('class' => 'form-control input-xs', 'id' => 'fechacertificado')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-4 col-lg-4">
        	<div class="form-group">
        		{!! Form::label('proximacalibracion', 'Prox. Fecha Calibracion:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::date('proximacalibracion', null, array('class' => 'form-control input-xs', 'id' => 'proximacalibracion')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-4 col-lg-4">
        	<div class="form-group">
        		{!! Form::label('comentario', 'Comentario:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::textarea('comentario', null, array('class' => 'form-control input-xs', 'id' => 'comentario','rows'=>'3')) !!}
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
        <div class="col-md-8 col-lg-8">
            <div class="form-group">
        		{!! Form::label('persona', 'Responsable:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12 row">
        		    <div class="col-lg-9 col-md-9 col-sm-9">
        			    {!! Form::select('persona', $cboPersona, null, array('class' => 'form-control input-xs', 'id' => 'persona')) !!}
        			</div>
        			<div class="col-lg-3 col-md-3 col-sm-3">
        			    <button class="btn btn-sm btn-info" type="button" onclick="agregarPersona();"><i class="fa fa-plus fa-fw"></i></button>
        			</div>
        		</div>
        	</div>
    		<div class="col-lg-12 col-md-12 col-sm-12">
    			<table class="table table-sm text-center table-striped  table-hover">
    			    <thead>
    			        <tr>
    			            <th>Persona</th>
    			        </tr>
    			    </thead>
    			    <tbody id='tbPersona'>
    			        <?php 
    			        if(!is_null($calibracion)){
    			            $jscript="";
    			            if($calibracion->listaresponsable!=""){
        			            $lista = explode("|",$calibracion->listaresponsable);
                                for($x=0;$x<count($lista);$x++){
                                    $person = Person::find($lista[$x]);
                                    if(!is_null($person)){
                                        $iddia = rand()*10000;
            			                echo "<tr id='trPersona".$iddia."'>";
                                        echo "<td><input type='hidden' name='lstResponsable[]' value='".$person->id."' class='form-control input-xs' style='width:70px;' />".$person->apellidopaterno." ".$person->apellidomaterno." ".$person->nombres."</td>";
                                        echo "<td><a href='#' onclick=\"quitarPersona('".$iddia."')\"><i class='fa fa-minus-circle' title='Quitar' width='20px' height='20px'></i></td></tr>";
                                        $jscript.="carroLista.push(".$iddia.");";
                                    }
        			            }
    			            }
    			        }
    			        ?>
    			    </tbody>
    			</table>
    		</div>
        </div>
        <div class="col-md-4 col-lg-4">
        	<div class="form-group">
        		{!! Form::label('fechaenvio1', 'Fecha Envio 1:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::date('fechaenvio1', null, array('class' => 'form-control input-xs', 'id' => 'fechaenvio1')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-4 col-lg-4">
        	<div class="form-group">
        		{!! Form::label('fechaenvio2', 'Fecha Envio 2:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::date('fechaenvio2', null, array('class' => 'form-control input-xs', 'id' => 'fechaenvio2')) !!}
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

var carroLista = new Array();
function agregarPersona(){
    var iddia = Math.round(Math.random()*10000000);
    $("#tbPersona").append("<tr id='trPersona"+iddia+"'>"+
        "<td><input type='hidden' name='lstResponsable[]' value='"+$("#persona").val()+"' class='form-control input-xs' style='width:70px;' />"+$("#persona option:selected").text()+"</td>"+
        "<td><a href='#' onclick=\"quitarPersona('"+iddia+"')\"><i class='fa fa-minus-circle' title='Quitar' width='20px' height='20px'></i></td></tr>");
    carroLista.push(iddia);
}
function quitarPersona(iddia){
    $("#trPersona"+iddia).remove();
    for(c=0; c < carroLista.length; c++){
        if(carroLista[c] == iddia) {
            carroLista.splice(c,1);
        }
    }
}


var equipo = new Bloodhound({
	datumTokenizer: function (d) {
		return Bloodhound.tokenizers.whitespace(d.value);
	},
	queryTokenizer: Bloodhound.tokenizers.whitespace,
	remote: {
		url: 'calibracion/equipoautocompletar/%QUERY/'+$("#subcategoria_id").val(),
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

var selec = $('#subcategoria_id').select2({
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

selec.on("change", function (e) { 
    $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="equipo"]').typeahead('destroy');
    $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="equipo"]').val("");
    $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="equipo_id"]').val("0");
    var equipo = new Bloodhound({
    	datumTokenizer: function (d) {
    		return Bloodhound.tokenizers.whitespace(d.value);
    	},
    	queryTokenizer: Bloodhound.tokenizers.whitespace,
    	remote: {
    		url: 'calibracion/equipoautocompletar/%QUERY/'+$("#subcategoria_id").val(),
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
    console.log("change"); 
    
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
    			    enviarArchivo(dat[0].calibracion_id , dat[0].accion);
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
		url: '{{ url("/calibracion/archivos") }}',
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

@if(!is_null($calibracion))
    {{$jscript}}
@endif
</script>