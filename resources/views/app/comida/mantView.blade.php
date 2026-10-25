<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($comida, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	{!! Form::hidden('id', !is_null($comida)?$comida->id:0, array('id' => 'id')) !!}
	<div class="row ">
		<div class='col-lg-2 col-md-2 col-sm-2'>
			<div class="form-group">
				{!! Form::label('fecha', 'Fecha:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::text('fecha', $fecha, array('class' => 'form-control input-xs', 'id' => 'persona', 'placeholder' => 'fecha','readonly'=>'true')) !!}
				</div>
			</div>
		</div>
		<div class='col-lg-2 col-md-2 col-sm-2'>
			<div class="form-group">
				{!! Form::label('dni', 'DNI / Extranjero:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::text('dni', $dni, array('class' => 'form-control input-xs', 'id' => 'dni', 'placeholder' => 'tipo','readonly'=>'true')) !!}
				</div>
			</div>
		</div>
		<div class='col-lg-6 col-md-6 col-sm-6'>
			<div class="form-group">
				{!! Form::label('persona', 'Persona:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::text('persona', $persona, array('class' => 'form-control input-xs', 'id' => 'persona', 'placeholder' => 'persona','readonly'=>'true')) !!}
				</div>
			</div>
		</div>
		<div class='col-lg-2 col-md-2 col-sm-2'>
			<div class="form-group">
				{!! Form::label('tipo', 'Servicio:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::text('tipo', $tipo, array('class' => 'form-control input-xs', 'id' => 'tipo', 'placeholder' => 'tipo','readonly'=>'true')) !!}
				</div>
			</div>
		</div>
	</div>
	<div class="row ">
		<div class='col-lg-6 col-md-6 col-sm-6'>
			<div class="form-group">
				{!! Form::label('mensaje', 'Mensaje:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label','style'=>'font-size:20px')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::text('mensaje', $mensaje, array('class' => 'form-control input-xs text-'.$color, 'id' => 'mensaje', 'placeholder' => 'mensaje','disabled'=>'true','style'=>'font-weight: bold;background-color:white !important;font-size:20px !important')) !!}
				</div>
			</div>
		</div>
		@if(!is_null($comida) && $color!="danger")
		<div class='col-lg-6 col-md-6 col-sm-6'>
			<div class="form-group row">
				{!! Form::label('firma', 'Firma:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label','style'=>'font-size:20px')) !!}
				<div class="col-lg-9 col-md-9 col-sm-9">
			        <div class="wrapper2" id="divCanvas">
                        <canvas id="signature-pad" width="620" height="600" class="signature-pad" id="canvas"></canvas>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <button type="submit" class="btn btn-lg btn-success" onclick='guardar();' id="btnRegistrar" style="font-size:35px !important;">
						<i class="fa fa-plus"></i> Guardar
                    </button>
                    <button id="clear" class="btn btn-lg btn-danger" onclick="signaturePad.clear();"  style="font-size:35px !important;margin-top: 10px;"><div class="fas fa-trash-alt"></div> Eliminar</button>
				</div>
			</div>
		</div>
		@endif
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
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('400');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
	
}); 
@if(!is_null($comida))
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
@endif

var vcontador=0;
var audioElement = document.createElement('audio');
audioElement.setAttribute('src', 'https://sistemasolmos.pe/comedor/dist/notificacion.mpeg');

function guardar(){
	var data = new FormData();
    var ctx = document.getElementById("signature-pad");
    var image = ctx.toDataURL(); // data:image/png....
    if (signaturePad.isEmpty()) {
	    alert("Debe ingresar su firma");
	    return false;
	}
    data.append('signed',image);
    data.append("id",$("#id").val());
    var btn = $("#btnRegistrar");
    btn.attr('disabled', true);
    if(vcontador==0){
    	vcontador = 1;
	    $.ajax({
	        url: '{{ route('webservices.uploadFirma') }}',
	        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
	        type: 'POST',
	        enctype: 'multipart/form-data',
	        data: data,
	        processData: false,
	        contentType: false,
	        cache: false,
	        timeout: 600000,
	        success: function(a){
	        	vcontador=0;
	        	btn.attr('disabled', false);
	        }
	    });
	    $("#name").val("");
	    $("#name").focus();
	    $("#listado").html("");
	    toastr.options.positionClass = "toast-top-center";
	    toastr.options.timeOut = 1750;
	    toastr.options.hideDuration = 1750;
	    mostrarMensajeComedor("{{ $persona }}, registrado con éxito", "OK");
	    $('.toast-success').css('height','150px');
	    $('.toast-success').css('font-size','30px');
	    $('.toast-success').css('top','20px');
	    $('.toast-success').css('width','1000px');
	    audioElement.play();
	    calcularContador();
	    @if(!is_null($comida))
	    imprimirComida('{{$comida->id}}','{{$fecha}}','{{$dni}}','{{$persona}}','{{$tipo}}','{{$empresa}}','{{$mensaje}}');
	    @endif
	}else{
		alert("Guardando espere por favor ...");
		return false;
	}
}
function calcularContador(){
    $.ajax({
        type: "POST",
        url: "{{ url("/webservices/calcularContador") }}",
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        data: "_token={{csrf_token()}}&comedor_id="+$("#comedor_id").val(),
        success: function(a) {
            $("#divContador").html(a);
	    }
    });
}
@if(!is_null($comida))
//imprimirComida({{$comida->id}});
@endif
</script>