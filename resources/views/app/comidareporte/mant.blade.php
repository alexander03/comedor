<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($comida, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	{!! Form::hidden('id', !is_null($comida)?$comida->id:0, array('id' => 'id')) !!}
	<div class="row ">
		<div class='col-lg-6 col-md-6 col-sm-6'>
			<div class="form-group">
				{!! Form::label('fecha', 'Fecha:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::datetimelocal('fecha', null, array('class' => 'form-control input-xs', 'id' => 'fecha', 'placeholder' => 'fecha')) !!}
				</div>
			</div>
		</div>
	</div>
	<div class="row ">
		<div class='col-lg-3 col-md-3 col-sm-3'>
			<div class="form-group">
				{!! Form::label('dni', 'DNI / Extranjero:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12 row">
					<div class="col-lg-10 col-md-10 col-sm-10">
						{!! Form::text('dni', null, array('class' => 'form-control input-xs', 'id' => 'dni')) !!}
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2">
    			    	<button class="btn btn-sm btn-info" type="button" onclick="buscarPersona();"><i class="fa fa-search fa-fw"></i></button>
    			    </div>
				</div>
			</div>
		</div>
		<div class='col-lg-9 col-md-9 col-sm-9'>
			<div class="form-group">
				{!! Form::label('persona', 'Persona (autocompletar):', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::hidden('ua_id', null, array('id' => 'ua_id')) !!}
					{!! Form::hidden('ua', null, array('id' => 'ua')) !!}
					{!! Form::hidden('persona_id', null, array('id' => 'persona_id')) !!}
					{!! Form::hidden('personawp_id', null, array('id' => 'personawp_id')) !!}
					{!! Form::text('persona', null, array('class' => 'form-control input-xs', 'id' => 'persona', 'placeholder' => 'persona')) !!}
    			</div>
			</div>
		</div>
		<div class='col-lg-3 col-md-3 col-sm-3'>
			<div class="form-group">
				{!! Form::label('servicio_id', 'Servicio:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::select('servicio_id', $cboServicio, null, array('class' => 'form-control input-xs', 'id' => 'servicio_id')) !!}
				</div>
			</div>
		</div>
		<div class='col-lg-3 col-md-3 col-sm-3'>
			<div class="form-group">
				{!! Form::label('comedor_id', 'Comedor:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::select('comedor_id', $cboComedor, null, array('class' => 'form-control input-xs', 'id' => 'comedor_id')) !!}
				</div>
			</div>
		</div>
	</div>
	<div class="row ">
		<div class='col-lg-6 col-md-6 col-sm-6'>
			<div class="form-group">
				{!! Form::label('firma', 'Firma:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label','style'=>'font-size:20px')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
			        <div class="wrapper2" id="divCanvas">
                        <canvas id="signature-pad" width="620" height="600" class="signature-pad" id="canvas"></canvas>
                    </div>
                    <br/>
                    {!! Form::button('<i class="fa fa-check "></i> '.$boton, array('class' => 'btn btn-success', 'id' => 'btnGuardar', 'onclick' => 'guardar2(\''.$entidad.'\', this)')) !!}
                    <button id="clear" class="btn btn-danger" onclick="signaturePad.clear();"><div class="fas fa-trash-alt"></div> Borrar</button>
				</div>
			</div>
		</div>
	</div>
	</form>
{!! Form::close() !!}
<style type="text/css">
	.wrapper2 {
      position: relative;
      width: 620px;
      height: 200px;
      -moz-user-select: none;
      -webkit-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }
	.signature-pad {
		position: absolute;
		left: 0;
		top: 0;
		width:620px;
		height:200px;
		background-color: white;
		border: dotted;
    }
</style>
<script src="dist/js/signature_pad.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('800');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
	var personas = new Bloodhound({
		datumTokenizer: function (d) {
			return Bloodhound.tokenizers.whitespace(d.value);
		},
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {
			url: 'comidareporte/personautocompletar/%QUERY',
			filter: function (personas) {
				return $.map(personas, function (movie) {
					return {
						dni: movie.dni,
						persona_id: movie.persona_id,
						ua: movie.ua,
						ua_id: movie.ua_id,
						personawp_id: movie.personawp_id,
						value: movie.nombres,
					};
				});
			}
		}
	});
	personas.initialize();
	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="persona"]').typeahead(null,{
		displayKey: 'value',
		source: personas.ttAdapter()
	}).on('typeahead:selected', function (object, datum) {
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="dni"]').val(datum.dni);
    	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="ua_id"]').val(datum.ua_id);
    	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="ua"]').val(datum.ua);
    	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="persona_id"]').val(datum.persona_id);
    	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="personawp_id"]').val(datum.personawp_id);
	});
}); 
var canvas = document.getElementById("signature-pad");

var signaturePad = new SignaturePad(canvas, {
    //backgroundColor: 'rgb(255, 255, 255)'
    penColor: "rgb(66, 133, 244)"
});

function resizeCanvas() {
    // When zoomed out to less than 100%, for some very strange reason,
    // some browsers report devicePixelRatio as less than 1
    // and only part of the canvas is cleared then.
    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
}

window.onresize = resizeCanvas;
resizeCanvas();

var contador=0;

function guardar2 (entidad, idboton) {
    var band=true;
	var msg="";
	var contador = 0;
	if (signaturePad.isEmpty()) {
	    alert("Debe ingresar su firma");
	    return false;
	}
	if($(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="persona"]').val()==""){
		alert("Debe buscar una persona");
	    return false;
	}
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
    			    enviarArchivo(dat[0].comida_id);
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

function enviarArchivo(comida_id){
	var data = new FormData();
    var ctx = document.getElementById("signature-pad");
    var image = ctx.toDataURL(); // data:image/png....
    data.append('signed',image);
    data.append("id",comida_id);
    $.ajax({
        url: '{{ route('webservices.uploadFirma') }}',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        type: 'POST',
        enctype: 'multipart/form-data',
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000
    });
}

function buscarPersona(){
	if($(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="dni"]').val()!=""){
		$.ajax({
	        type: "GET",
	        url: "comidareporte/buscarPersona",
	        data: "dni="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="dni"]').val()+"&_token="+$(':input[name="_token"]').val(),
	        success: function(dat) {
	            var data = JSON.parse(dat);
	            if(data.nombres!="null" && data.nombres!=""){
	            	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="persona"]').val(data.nombres);
	            	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="ua_id"]').val(data.ua_id);
	            	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="ua"]').val(data.ua);
	            	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="persona_id"]').val(data.persona_id);
	            	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="personawp_id"]').val(data.personawp_id);
	            }else{
	            	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="persona"]').val("");
	            	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="ua_id"]').val("");
	            	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="ua"]').val("");
	            	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="persona_id"]').val("");
	            	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="personawp_id"]').val("");
	            }
	        }
	    });
	}else{
		alert("Debe ingresar un dni para buscar");
	}
}
</script>