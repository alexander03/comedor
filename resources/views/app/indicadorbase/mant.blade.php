<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($indicador, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
    <div class="form-group">
		{!! Form::label('proceso_id', 'Proceso', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::select('proceso_id', $cboProceso, null, array('class' => 'form-control input-xs', 'id' => 'proceso_id')) !!}
		</div>
	</div>
    <div class="form-group">
		{!! Form::label('nombre', 'Indicador:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::text('nombre', null, array('class' => 'form-control input-xs', 'id' => 'nombre', 'placeholder' => 'Ingrese indicador')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('objetivo', 'Objetivo:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::text('objetivo', null, array('class' => 'form-control input-xs', 'id' => 'objetivo')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('formula', 'Fórmula:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::text('formula', null, array('class' => 'form-control input-xs', 'id' => 'formula')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('parametro', 'Parámetro de Aceptación:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
		<button class="btn btn-sm btn-info" type="button" onclick="agregarParametro();"><i class="fa fa-plus fa-fw"></i></button>
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
                            echo "<td><input type='text' name='lstMinimo[]' value='".$data[0]."' class='form-control input-xs' style='width:70px;' /></td>";
                            echo "<td><input type='text' name='lstMaximo[]' value='".$data[1]."' class='form-control input-xs' style='width:70px;' /></td>";
                            echo "<td><input type='color' name='lstColor[]' value='".$data[2]."'  class='form-control input-xs' /></td>";
                            echo "<td><a href='#' onclick=\"quitarParametro('".$iddia."')\"><i class='fa fa-minus-circle' title='Quitar' width='20px' height='20px'></i></td></tr>";
                            $jscript.="carroParametro.push(".$iddia.");";
			            }
			        }
			        ?>
			    </tbody>
			</table>
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('frecuencia', 'Frecuencia:', array('class' => 'col-lg-9 col-md-9 col-sm-9 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::select('frecuencia', $cboFrecuencia, null, array('class' => 'form-control input-xs', 'id' => 'frecuencia','onchange'=>'generarFrecuencia();')) !!}
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
var carroParametro = new Array();
function agregarParametro(){
    var iddia = Math.round(Math.random()*10000000);
    $("#tbParametro").append("<tr id='trParametro"+iddia+"'>"+
        "<td><input type='text' name='lstMinimo[]' value='0' class='form-control input-xs' style='width:70px;' /></td>"+
        "<td><input type='text' name='lstMaximo[]' value='0' class='form-control input-xs' style='width:70px;' /></td>"+
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

</script>