<?php
use App\Person;
?>
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($documento, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="row">
		<div class="col-md-3 col-lg-3">
        	<div class="form-group">
        		{!! Form::label('tipodocumento_id', 'Tipo Doc.:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::select('tipodocumento_id',$cboTipodocumento, null, array('class' => 'form-control input-xs', 'id' => 'tipodocumento_id')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-2 col-lg-2">
            <div class="form-group">
        		{!! Form::label('codigo', 'Codigo', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::text('codigo', null, array('class' => 'form-control input-xs', 'id' => 'codigo')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-7 col-lg-7">
            <div class="form-group">
        		{!! Form::label('descripcion', 'Nombre:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::text('descripcion', null, array('class' => 'form-control input-xs', 'id' => 'descripcion', 'placeholder' => 'Ingrese descripcion')) !!}
        		</div>
        	</div>
        </div>
	</div>
	<div class="row">
		<div class="col-md-2 col-lg-2">
        	<div class="form-group">
        		{!! Form::label('revision', 'Revision:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::text('revision', null, array('class' => 'form-control input-xs', 'id' => 'revision')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-3 col-lg-3">
        	<div class="form-group">
        		{!! Form::label('fecha', 'Fecha Aprobacion:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::date('fecha', null, array('class' => 'form-control input-xs', 'id' => 'fecha')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-4 col-lg-4">
        	<div class="form-group">
        		{!! Form::label('proceso_id', 'Proceso Involucrado:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::select('proceso_id',$cboProceso, null, array('class' => 'form-control input-xs', 'id' => 'proceso_id')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-4 col-lg-4">
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
    			        if(!is_null($documento)){
    			            $jscript="";
    			            if($documento->listaresponsable!=""){
        			            $lista = explode("|",$documento->listaresponsable);
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

var carroLista = new Array();
function agregarPersona(){
    var iddia = Math.round(Math.random()*10000000);
    $("#tbPersona").append("<tr id='trParametro"+iddia+"'>"+
        "<td><input type='hidden' name='lstResponsable[]' value='"+$("#persona").val()+"' class='form-control input-xs' style='width:70px;' />"+$("#persona option:selected").text()+"</td>"+
        "<td><a href='#' onclick=\"quitarParametro('"+iddia+"')\"><i class='fa fa-minus-circle' title='Quitar' width='20px' height='20px'></i></td></tr>");
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

function guardar2 (entidad, idboton) {
    @if(is_null($documento))
    if($("#archivo").val()==""){
        alert("Debe elegir un archivo para subir");
        return false;
    }
    @endif
    var band=true;
	var msg="";
	var contador = 0;
	
	$("#btnGuardar").css("display","none");
	
    if(band && contador==0){
        contador=1;
    	var idformulario = IDFORMMANTENIMIENTO + entidad;
    	var data         = submitForm(idformulario);
    	var respuesta    = '';
    	var btn = $(idboton);
    	btn.button('loading');
    	$("#cargando").css("display","");
    	data.done(function(msg) {
    		respuesta = msg;
    	}).fail(function(xhr, textStatus, errorThrown) {
    		respuesta = 'ERROR';
            contador=0;
    	}).always(function() {
    	    $("#btnGuardar").css("display","");
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
    			    if($("#archivo").val()!=""){
    			        mostrarMensaje("Subiendo archivo...", "OK");
    			        enviarArchivo(dat[0].documento_id , dat[0].accion,entidad);
    			    }else{
    			        cerrarModal();
                        buscarCompaginado('', 'Accion realizada correctamente', entidad, 'OK');   
    			    }
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

function enviarArchivo(idcompra ,accion,entidad){
    //var form = $('#formMantenimientoCompra')[0];
	//var formulario = new FormData(form);
	
    var data = new FormData();
    jQuery.each($(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="archivo"]')[0].files, function(i, file) {
        data.append('file-'+i, file);
    });
    data.append("id",idcompra);
    data.append("accion",accion);
    $.ajax({
		url: '{{ url("/documento/archivos") }}',
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