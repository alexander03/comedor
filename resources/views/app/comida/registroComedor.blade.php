@extends('layouts.appComedor')

@section('content')
<link rel="stylesheet" href="dist/css/auto-complete.css">
<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="dist/css/toastr.min.css">
<div class="card text-white bg-primary">
	<form method="POST" action="{{ route('webservices.guardarComedor') }}" id="frmPedido" class="form-horizontal">
        @csrf
        {!! Form::hidden('comedor_id', '1', array('id' => 'comedor_id')) !!}
    	<div class="card-header row" style='font-weight: bold;font-size: 45px;'>
    		<div class="col-6">COMEDOR PALO VERDE - <label id="countdown" style="font-size:45px">[--:--:--]</label></div>
    		<div class="col-6" style="text-align: right;">
				<label class="form-label" id="divContador" style="font-size:30px;">{!!$divContador!!}</label>
			</div>
    	</div>
	    	<div class="card-body row">
	    		<div class="col-lg-2 col-md-2  form-group">
	    		</div>
				<div class="col-lg-4 col-md-4  form-group">
					{!! Form::label('name', 'DNI:',array('style'=>'font-size: 50px')) !!}
					{!! Form::text('name', '', array('class' => 'form-control input-xs', 'id' => 'name','maxlength'=>'8','style'=>'height: 80px;font-size: 50px','onkeypress'=>'return valideKey(event);')) !!}
				</div>
				<div class="col-lg-4 col-md-4  form-group">
					{!! Form::label('otros', 'Extranjero:',array('style'=>'font-size: 50px')) !!}
					{!! Form::text('otros', '', array('class' => 'form-control input-xs', 'id' => 'otros','style'=>'height: 80px;font-size: 50px')) !!}
				</div>
				<div class="col-lg-1 col-md-1  form-group">
	    		</div>
				<!--div class="col-lg-2 col-md-2 form-group">
					<label for="btnRegistrar"></label>
					<br />
					<button type="button" class="btn btn-success" onclick='buscar();' id="btnRegistrar" style="margin-top:7px;">
						<i class="fa fa-plus"></i>
                        Aceptar
                    </button>
				</div-->
				<div class="col-lg-3 col-md-3  form-group">
	    		</div>
				<div class="col-lg-6 col-md-6" style="text-align:center">
					<label class="text-center" style="font-size: 30px;">Para guardar presione enter</label>
				</div>
			</div>
		</div>
		<div class="row mt-2" >
			<div class="col-md-12">
			  <div class="card">
				<div class="card-header">
				</div>
				<!-- /.card-header -->
				<div class="card-body table-responsive px-3">
					<div id="listado">
					</div>
				</div>
				<!-- /.card-body -->
				   

			  </div>
			  <!-- /.card -->
			</div>
		</div>
	</form>
</div>
<!-- jQuery -->
<!--script src="plugins/jquery/jquery.min.js"></script-->
<script src="dist/js/auto-complete.js"></script>
<script src="dist/js/funciones.js"></script>
<script src="dist/js/toastr.min.js"></script>
<script src="dist/js/signature_pad.js"></script>
<script>
	$(document).ready(function () {
		$("#name").focus();
		$(':input[id="name"]').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				var dni = $(':input[id="name"]').val();
				if(dni.trim=="" || dni.length<8 || dni.length>8){
					alert("Debe ingresar un DNI valido");
				}else{
					buscar();
					$("#name").val("");
					$("#name").focus();
				}
			}
		});
		$(':input[id="otros"]').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				buscar();
				$("#otros").val("");
				$("#otros").focus();
			}
		});
	});

	function valideKey(evt){		
		// code is the decimal ASCII representation of the pressed key.
		var code = (evt.which) ? evt.which : evt.keyCode;
		
		if(code==8) { // backspace.
		  return true;
		} else if(code>=48 && code<=57) { // is a number.
		  return true;
		} else{ // other keys.
		  return false;
		}
	}

	function buscar(){
		var dni = $(':input[id="name"]').val();
		var otros = $(':input[id="otros"]').val();
		if(dni.trim()=="" && otros.trim()==""){
			alert("Debe ingresar un DNI/Extranjero valido");
			$("#name").focus();
			return false;
		}
		if(otros.trim()=="" && (dni.trim()=="" || dni.length<8 || dni.length>8)){
			alert("Debe ingresar un DNI valido");
			return false;
		}

		var contenedor  = 'listado';
		$(':input[id = "page"]').val(1);
		cargarSubmitForm('#frmPedido', contenedor);
		if(dni.trim()!=""){
			$("#name").val("");
			$("#name").focus();
		}else{
			$("#otros").val("");
			$("#otros").focus();
		}
	}

	function imprimirComida(id,fecha,dni,persona,servicio,empresa,mensaje){
	    $.ajax({
	        type: "POST",
	        url: "http://localhost/comedor/imprimirComida.php",
	        data: "id="+id+"&fecha="+fecha+"&dni="+dni+"&persona="+persona+"&servicio="+servicio+"&mensaje="+mensaje+"&empresa="+empresa,
	        success: function(a) {
	            console.log(a);
		    }
	    });
	}

	var seconds = {{$segundos}}; //número de segundos a contar
	function secondPassed() {

	  var minutes = Math.round((seconds - 30)/60); //calcula el número de minutos
	  var hour = Math.floor(minutes/60);
	  minutes = minutes%60;
	  var remainingSeconds = seconds % 60; //calcula los segundos
	  //si los segundos usan sólo un dígito, añadimos un cero a la izq
	  if (remainingSeconds < 10) { 
	    remainingSeconds = "0" + remainingSeconds; 
	  } 
	  if (minutes < 1 && hour==0){
	  	minutes = '--';
	  }else if(minutes < 10){
	  	minutes = "0" + minutes;
	  }
	  document.getElementById('countdown').innerHTML = "[0" + hour + ":" + minutes + ":" +     remainingSeconds + "]"; 
	  if (seconds == 0) { 
	    clearInterval(countdownTimer); 
	    document.getElementById('countdown').innerHTML = "[--:--:--]"; 
	  } else { 
	    seconds--; 
	  } 
	} 
	@if($segundos!="0")
	var countdownTimer = setInterval(secondPassed, 1000);
	@endif
</script>
@endsection