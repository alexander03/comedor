<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($persona, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	{!! Form::hidden('roles', null, array('id' => 'roles')) !!}
	{!! Form::hidden('remoto_id', null, array('id' => 'remoto_id')) !!}

	<div class="row">
		<div class="col-md-6 col-lg-6" hidden="">
			<div class="form-group pb-1">
				{!! Form::label('dni', 'DNI:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12 input-group pl-0">
                        <div class="col-lg-10 col-sm-10 col-md-10 pr-0">
							{!! Form::text('dni', null, array('class' => 'form-control input-xs', 'id' => 'dni')) !!}
                        </div>
                        <div class="col-lg-1 col-sm-1 col-md-1 pl-1">
                            <span class="input-group-append">
                                <? /*{!! Form::button('<i class="fa fa-search "></i>', array('class' => 'btn btn-primary', 'onclick' => 'buscarDNI();', 'title' => 'Buscar DNI')) !!} */ ?>
                                {!! Form::button('<i class="fa fa-search "></i>', array('class' => 'btn btn-primary', 'onclick' => 'modal (\''.URL::route('persona.wp', array('listar'=>'SI','page'=>'1','filas'=>'1000')).'\', \'Buscar WP\', this);', 'title' => 'Buscar WP')) !!}
                            </span>
                        </div>
        		</div>
        	</div>
			
		</div>
		<div class="col-md-6 col-lg-6" style="display:none">
			<div class="form-group pb-1">
				{!! Form::label('ruc', 'RUC:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12 input-group pl-0">
                        <div class="col-lg-10 col-sm-10 col-md-10 pr-0">
							{!! Form::text('ruc', null, array('class' => 'form-control input-xs', 'id' => 'ruc')) !!}
                        </div>
                        <div class="col-lg-1 col-sm-1 col-md-1 pl-1">
                            <span class="input-group-append">
                                {!! Form::button('<i class="fa fa-search "></i>', array('class' => 'btn btn-primary', 'onclick' => 'buscarRUC();', 'title' => 'Buscar RUC')) !!}
                            </span>
                        </div>
        		</div>
        	</div>
		</div>
	</div>
	<div class="row" hidden="">
		<div class="col-md-6 col-lg-6">
			<div class="form-group">
				{!! Form::label('apellidopaterno', 'Apellido Paterno:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::text('apellidopaterno', null, array('class' => 'form-control input-xs', 'id' => 'apellidopaterno', 'placeholder' => 'Ingrese apellido paterno')) !!}
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-6">
			<div class="form-group">
				{!! Form::label('apellidomaterno', 'Apellido Materno:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::text('apellidomaterno', null, array('class' => 'form-control input-xs', 'id' => 'apellidomaterno', 'placeholder' => 'Ingrese apellido materno')) !!}
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-11 col-lg-11">
			{!! Form::label('nombres', 'Nombres:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
			<div class="col-lg-12 col-md-12 col-sm-12">
				{!! Form::text('nombres', null, array('class' => 'form-control input-xs', 'id' => 'nombres', 'placeholder' => 'Ingrese nombres')) !!}
			</div>
		</div>
		<div class="col-lg-1 col-sm-1 col-md-1 pl-1">
                <span class="input-group-append">
                    {!! Form::button('<i class="fa fa-search "></i>', array('class' => 'btn btn-primary', 'onclick' => 'modal (\''.URL::route('persona.wp', array('listar'=>'SI','page'=>'1','filas'=>'1000')).'\', \'Buscar WP\', this);', 'title' => 'Buscar WP')) !!}
                </span>
            </div>
		<div class="col-md-6 col-lg-6" hidden="">
			{!! Form::label('direccion', 'Direccion:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
			<div class="col-lg-12 col-md-12 col-sm-12">
				{!! Form::text('direccion', null, array('class' => 'form-control input-xs', 'id' => 'direccion', 'placeholder' => 'Ingrese direccion')) !!}
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 col-lg-6" hidden="">
			<div class="form-group">
				{!! Form::label('telefono', 'Telefono:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::text('telefono', null, array('class' => 'form-control input-xs', 'id' => 'telefono', 'placeholder' => 'Ingrese telefono')) !!}
				</div>
			</div>
		</div>
		<div class="col-md-12 col-lg-12">
			<div class="form-group">
				{!! Form::label('email', 'Correo:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::text('email', null, array('class' => 'form-control input-xs', 'id' => 'email', 'placeholder' => 'Ingrese correo')) !!}
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4 col-lg-4">
			<div class="form-group">
				{!! Form::label('rolpersona', 'Roles:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					<?php foreach($cboRol as $k=>$value){ 
						if(!is_null($cboRp) && count($cboRp)>0){
							if(isset($cboRp[$k]) && !is_null($cboRp[$k])){
								$check = "checked";
							}else{
								$check = "";
							}
						}else{
							$check = "";
						}
					?>
						<input type="checkbox" {{ $check }} onclick='agregarRol(this.checked,{{ $k }})'/>{{ $value }} <br />
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-6">
            <div class="form-group">
        		{!! Form::label('archivo', 'Firma:'.(!is_null($persona)?("(".$persona->archivo.")"):""), array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
    		    <div class="col-lg-12 col-md-12 col-sm-12">
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
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('600');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
    $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="dni"]').inputmask("99999999");
    //$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="ruc"]').inputmask("99999999999");
}); 
var carroRol = new Array();
function agregarRol(check,id){
	if(check){
		carroRol.push(id);
	}else{
		for(c=0; c < carroRol.length; c++){
	        if(carroRol[c] == id) {
	            carroRol.splice(c,1);
	        }
	    }
	}
	$('#roles').val(carroRol.toString());
}

function buscarDNI(){
	var reg = new RegExp('^[0-9]+$');
    if($(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="dni"]').val() == ""){
        toastr.warning("Debe ingresar un DNI.", 'Error:');
    }else if(!reg.test($(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="dni"]').val())){
        toastr.warning("El DNI es incorrecto.", 'Error:');
	}else{
        $.ajax({
            type: "POST",
            url: "persona/buscarDNI",
            data: "dni="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="dni"]').val()+"&_token="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="_token"]').val(),
            success: function(a) {
                datos=JSON.parse(a);
				if(datos.code != 0){
        			toastr.warning(datos.mensaje, 'Error:');
				}else{
					$("#apellidopaterno").val(datos.apepat);
					$("#apellidomaterno").val(datos.apemat);
					$("#nombres").val(datos.nombres);
				}
            }
        });
    }
}

function buscarRUC(){
	var reg = new RegExp('^[0-9]+$');
    if($(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="ruc"]').val() == ""){
        toastr.warning("Debe ingresar un RUC.", 'Error:');
    }else if(!reg.test($(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="ruc"]').val())){
        toastr.warning("El RUC ingresado es incorrecto.", 'Error:');
	}else{
        $.ajax({
            type: "POST",
            url: "persona/buscarRUC",
            data: "ruc="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="ruc"]').val()+"&_token="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="_token"]').val(),
            success: function(a) {
                datos=JSON.parse(a);
				if(datos.length == 0){
        			toastr.warning("El RUC ingresado es incorrecto.", 'Error:');
				}else{
					$("#nombres").val(datos.RazonSocial);
					$("#direccion").val(datos.Direccion);
				}
            }
        });
    }
}

function seleccionarWP(idusuario,personal,usuario,email){
	if(idusuario==""){
		alert("Falta crear usuario en WorkPeople");
		return false;
	}
    $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="nombres"]').val(personal);
    $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="email"]').val(email);
    $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="remoto_id"]').val(idusuario);
    cerrarModal();
}

var contador = 0;
function guardar2 (entidad, idboton) {
    var band=true;
	var msg="";
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
    			        enviarArchivo(dat[0].person_id , dat[0].accion);
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
		url: '{{ url("/persona/archivos") }}',
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
        buscarCompaginado('', 'Accion realizada correctamente', '{{ $entidad }}', 'OK');
    });
}



@php
foreach ($cboRp as $key => $value) {
	echo "agregarRol(true,".$key.");";
}
@endphp
</script>