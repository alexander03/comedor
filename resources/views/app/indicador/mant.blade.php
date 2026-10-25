<?php
use App\Person;
if($user->usertype_id=="5"){
    $readonly='readonly';
}else{
    $readonly='';
}
?>
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($indicador, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	{!! Form::hidden('archivo2', null, array('id' => 'archivo2')) !!}
	<div class="row">
		<div class="col-md-2 col-lg-2">
        	<div class="form-group">
        		{!! Form::label('anio', 'Año:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::select('anio',$cboAnio, null, array('class' => 'form-control input-xs', 'id' => 'anio', $readonly =>'')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-5 col-lg-5">
            <div class="form-group">
        		{!! Form::label('proceso_id', 'Proceso', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::select('proceso_id', $cboProceso, null, array('class' => 'form-control input-xs', 'id' => 'proceso_id', 'onchange' => 'buscarIndicador();', $readonly =>'')) !!}
        		</div>
        	</div>
        </div>
    </div>
    <div class="row">
		<div class="col-md-2 col-lg-2">
        	<div class="form-group">
        		{!! Form::label('orden', 'Orden:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::number('orden', null, array('class' => 'form-control input-xs', 'id' => 'orden', $readonly =>'')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-10 col-lg-10">
            <div class="form-group">
        		{!! Form::label('indicador', 'Indicador:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        		    {!! Form::hidden('indicadorbase_id', null, array('id' => 'indicadorbase_id')) !!}
        			{!! Form::text('indicador', null, array('class' => 'form-control input-xs', 'id' => 'indicador', 'placeholder' => 'Ingrese indicador', $readonly =>'')) !!}
        		</div>
        	</div>
        </div>
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-12">
        	<div class="form-group">
        		{!! Form::label('objetivo', 'Objetivo:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::text('objetivo', null, array('class' => 'form-control input-xs', 'id' => 'objetivo','readonly'=>'true')) !!}
        		</div>
        	</div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-lg-4">
        	<div class="form-group">
        		{!! Form::label('formula', 'Fórmula:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::text('formula', null, array('class' => 'form-control input-xs', 'id' => 'formula','readonly'=>'true')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-5 col-lg-5">
        	<div class="form-group">
        		{!! Form::label('parametro', 'Parámetro de Aceptación:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<button style="display:none" class="btn btn-sm btn-info" type="button" onclick="agregarParametro();"><i class="fa fa-plus fa-fw"></i></button>
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			<table class="table table-sm text-center table-striped  table-hover">
        			    <thead>
        			        <tr>
        			            <th>Min</th>
        			            <th>Max</th>
        			            <th>Color</th>
        			        </tr>
        			    </thead>
        			    <tbody id='tbParametro'>
        			        <?php 
        			        if(!is_null($indicador)){
        			            $jscript="";
        			            $lista = explode("|",$indicador->parametro);
                                for($x=0;$x<count($lista);$x++){
                                    $data = explode("@",$lista[$x]);
                                    $iddia = rand()*10000;
        			                echo "<tr id='trParametro".$iddia."'>";
                                    echo "<td><input data='numero3' type='text' readonly='' name='lstMinimo[]' value='".$data[0]."' class='form-control input-xs' style='width:90px;' /></td>";
                                    echo "<td><input data='numero3' type='text' readonly='' name='lstMaximo[]' value='".$data[1]."' class='form-control input-xs' style='width:90px;' /></td>";
                                    echo "<td><input data='numero3' type='color' readonly='' name='lstColor[]' value='".$data[2]."'  class='form-control input-xs' /></td>";
                                    echo "<td style='display:none'><a href='#' onclick=\"quitarParametro('".$iddia."')\"><i class='fa fa-minus-circle' title='Quitar' width='20px' height='20px'></i></td></tr>";
                                    $jscript.="carroParametro.push(".$iddia.");";
        			            }
        			        }
        			        ?>
        			    </tbody>
        			</table>
        		</div>
        	</div>
        </div>
		<div class="col-md-3 col-lg-3">
        	<div class="form-group">
        		{!! Form::label('frecuencia', 'Frecuencia:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::select('frecuencia', $cboFrecuencia, null, array('class' => 'form-control input-xs', 'id' => 'frecuencia','onchange'=>'generarFrecuencia();','readonly'=>'true')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-12 col-lg-12" id="divFrecuencia">
            <?php
            if(!is_null($indicador)){
                echo "<label>Valores:</label><br /><table class='table table-sm text-center table-striped  table-hover'><tr>";
                $lista = explode("|",$indicador->valores);
                for($x=0;$x<count($lista);$x++){
                    if($x==6){
                        echo "</tr><tr>";
                    }
                    if(trim($lista[$x])==""){
                        $read = "";
                    }else{
                        $read = $readonly;
                    }
                    echo "<td>".($x+1)."° :</td><td><input data='numero3' type='text' class='form-control input-xs' style='width:90px;' name='txtFrecuencia[]' value='".$lista[$x]."' ".$read." /><td>";
                }
                echo"</tr></table>";
            }
            ?>
        </div>
        <div class="col-md-4 col-lg-4">
        	<div class="form-group">
        		{!! Form::label('responsable1', 'Resp. Generación de Data:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::text('responsable1', null, array('class' => 'form-control input-xs', 'id' => 'responsable1', $readonly =>'')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-4 col-lg-4">
        	<div class="form-group">
        		{!! Form::label('responsable2', 'Resp. Recopilar Información:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::text('responsable2', null, array('class' => 'form-control input-xs', 'id' => 'responsable2', $readonly =>'')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-4 col-lg-4">
        	<div class="form-group">
        		{!! Form::label('responsable3', 'Resp. de Análisis:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::text('responsable3', null, array('class' => 'form-control input-xs', 'id' => 'responsable3', $readonly =>'')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-6 col-lg-6">
        	<div class="form-group">
        		{!! Form::label('observaciones', 'Observacion:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::textarea('observaciones', null, array('class' => 'form-control input-xs', 'id' => 'observaciones','rows'=>'3', $readonly =>'')) !!}
        		</div>
        	</div>
        </div>
        <div class="col-md-6 col-lg-6">
        	<div class="form-group">
        		{!! Form::label('responsable', 'Responsable:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::text('responsable', null, array('class' => 'form-control input-xs', 'id' => 'responsable', $readonly =>'')) !!}
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
        <div class="col-md-8 col-lg-8" <?php if($user->usertype_id=="5"){ echo "style='display:none';";}?>>
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
    			        if(!is_null($indicador)){
    			            $jscript="";
    			            if($indicador->listaresponsable!=""){
        			            $lista = explode("|",$indicador->listaresponsable);
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
	configurarAnchoModal('1000');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
    $(':input[data="numero3"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: "", groupSize: 3, digits: 3 });
}); 

var carroParametro = new Array();
function agregarParametro(){
    var iddia = Math.round(Math.random()*10000000);
    $("#tbParametro").append("<tr id='trParametro"+iddia+"'>"+
        "<td><input type='text' name='lstMinimo[]' value='0' class='form-control input-xs' style='width:90px;' /></td>"+
        "<td><input type='text' name='lstMaximo[]' value='0' class='form-control input-xs' style='width:90px;' /></td>"+
        "<td><input type='color' name='lstColor[]' value=''  class='form-control input-xs' /></td>"+
        "<td><a href='#' onclick=\"quitarParametro('"+iddia+"')\"><i class='fa fa-minus-circle' title='Quitar' width='20px' height='20px'></i></td></tr>");
    carroParametro.push(iddia);
}
function quitarParametro(iddia){
    $("#trParametro"+iddia).remove();
    for(c=0; c < carroParametro.length; c++){
        if(carroParametro[c] == iddia) {
            carroParametro.splice(c,1);
        }
    }
}

var carroLista = new Array();
function agregarPersona(){
    var iddia = Math.round(Math.random()*10000000);
    $("#tbPersona").append("<tr id='trParametro"+iddia+"'>"+
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

function guardar2 (entidad, idboton) {
    var band=true;
	var msg="";
	var contador = 0;
	if($("#archivo").val()!=""){
	    $("#archivo2").val("");
	}else{
	    $("#archivo2").val("Sin sustento");
	}
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
    			        enviarArchivo(dat[0].indicador_id , dat[0].accion);
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
		url: '{{ url("/indicador/archivos") }}',
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
}

function generarFrecuencia(){
    if($("#frecuencia").val()=="Mensual"){
        var c = 12;
    }else if($("#frecuencia").val()=="Bimestral"){
        var c = 6;
    }else if($("#frecuencia").val()=="Trimestral"){
        var c = 4;
    }else if($("#frecuencia").val()=="Semestral"){
        var c = 2 ;
    }else if($("#frecuencia").val()=="Anual"){
        var c = 1;
    }
    var registro="<label>Valores:</label><br /><table class='table table-sm text-center table-striped  table-hover'><tr>";
    for(var x=0;x<c;x++){
        if(x==6){
            registro+="</tr><tr>";
        }
        registro+="<td>"+(x+1)+"° :</td><td><input data='numero3' type='text' class='form-control input-xs' style='width:90px;' name='txtFrecuencia[]' /><td>";
    }
    registro+="</tr></table>";
    $("#divFrecuencia").html(registro);
    $(':input[data="numero3"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: "", groupSize: 3, digits: 3 });
}

function buscarIndicador(){
    $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="indicador"]').typeahead('destroy');
    $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="indicador"]').val("");
    $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="indicador_id"]').val("0");
    var indicador = new Bloodhound({
    	datumTokenizer: function (d) {
    		return Bloodhound.tokenizers.whitespace(d.value);
    	},
    	queryTokenizer: Bloodhound.tokenizers.whitespace,
    	remote: {
    		url: 'indicador/indicadorbaseautocompletar/%QUERY/'+$("#proceso_id").val(),
    		filter: function (equipo) {
    			return $.map(equipo, function (movie) {
    				return {
    					value: movie.value,
    					id: movie.id,
    					objetivo: movie.objetivo,
    					formula: movie.formula,
    					frecuencia: movie.frecuencia,
    					parametro: movie.parametro,
    				};
    			});
    		}
    	}
    });
    indicador.initialize();
    $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="indicador"]').typeahead(null,{
	    displayKey: 'value',
    	source: indicador.ttAdapter()
    }).on('typeahead:selected', function (object, datum) {
    	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="indicador"]').val(datum.value);
        $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="indicadorbase_id"]').val(datum.id);
        $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="objetivo"]').val(datum.objetivo);
        $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="formula"]').val(datum.formula);
        $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="frecuencia"]').val(datum.frecuencia);
        
        generarFrecuencia();
        
        var paramet = datum.parametro.split("|");
        for(var x=0;x<paramet.length;x++){
            var iddia = Math.round(Math.random()*10000000);
            var val = paramet[x].split("@");
            $("#tbParametro").append("<tr id='trParametro"+iddia+"'>"+
                "<td><input data='numero3' type='text' readonly='' name='lstMinimo[]' value='"+val[0]+"' class='form-control input-xs' style='width:90px;' /></td>"+
                "<td><input data='numero3' type='text' readonly='' name='lstMaximo[]' value='"+val[1]+"' class='form-control input-xs' style='width:90px;' /></td>"+
                "<td><input type='color' readonly='' name='lstColor[]' value='"+val[2]+"'  class='form-control input-xs' /></td>"+
                "<td style='display:none;'><a href='#' onclick=\"quitarParametro('"+iddia+"')\"><i class='fa fa-minus-circle' title='Quitar' width='20px' height='20px'></i></td></tr>");
            carroParametro.push(iddia);
            $(':input[data="numero3"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: "", groupSize: 3, digits: 3 });
        }
    });
}

<?php
if(is_null($indicador)){
    echo "generarFrecuencia();";
}else{
    echo $jscript;
}
?>
</script>