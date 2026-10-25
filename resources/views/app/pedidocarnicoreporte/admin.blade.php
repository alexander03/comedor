<div class="content-wrapper p-2 ml-0 " id="container"  >
	<!-- Content Header (Page header) -->
	
	  <div class="content-header mb-none">
		<div class="container-fluid">
		  <div class="row mb-2">
			<div class="col-sm-6">
			  <h1 class="m-0 text-dark">{{$title}}</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
			  <ol class="breadcrumb float-sm-right">
				<li class="breadcrumb-item"><a href="#">Administracion</a></li>
				<li class="breadcrumb-item active">{{$entidad}}</li>
			  </ol>
			</div><!-- /.col -->
		  </div><!-- /.row -->
		</div><!-- /.container-fluid -->
	  </div>
	  <!-- /.content-header -->
  
	  <!-- Main content -->
	  <div class="content ">
		<div class="container-fluid">
		  <div class="">
			  <div class="row justify-content-center">
				  <div class="col-lg-12 col-md-12">
					  <div class="card mt-4">
						<div class="card-body">
							{!! Form::open(['route' => $ruta["search"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => '', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad]) !!}
							{!! Form::hidden('page', 1, array('id' => 'page')) !!}
							{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
							
						  <div class="row">
							<div class="col-lg-3 col-md-3  form-group">
								{!! Form::label('fechainicio', 'Fecha inicio') !!}
								{!! Form::date('fechainicio', date('Y-m-d'), array('class' => 'form-control input-xs', 'id' => 'fechainicio','onchange'=>'buscar(\''.$entidad.'\')')) !!}
							</div>
							<div class="col-lg-3 col-md-3  form-group">
								{!! Form::label('fechafin', 'Fecha Fin') !!}
								{!! Form::date('fechafin', date('Y-m-d'), array('class' => 'form-control input-xs', 'id' => 'fechafin','onchange'=>'buscar(\''.$entidad.'\')')) !!}
							</div>
							<div class="col-lg-3 col-md-3  form-group">
								{!! Form::label('concesionaria_id', 'Campamento:') !!}
								{!! Form::select('concesionaria_id',["1"=>"H2O","2"=>"CTO"], null, array('class' => 'form-control input-xs', 'id' => 'concesionaria_id','onchange' => 'buscar(\''.$entidad.'\')')) !!}
							</div>
							<div class="col-lg-3 col-md-3  form-group">
								{!! Form::label('desayuno', 'Desayuno:') !!}
								{!! Form::select('desayuno', $cboDesayuno,"-1", array('class' => 'form-control input-xs', 'id' => 'desayuno', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
							</div>
							<div class="col-lg-3 col-md-3  form-group">
								{!! Form::label('almuerzo', 'Almuerzo:') !!}
								{!! Form::select('almuerzo', $cboAlmuerzo,"-1", array('class' => 'form-control input-xs', 'id' => 'almuerzo', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
							</div>
							<div class="col-lg-3 col-md-3  form-group">
								{!! Form::label('cena', 'Cena:') !!}
								{!! Form::select('cena', $cboCena,"-1", array('class' => 'form-control input-xs', 'id' => 'cena', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
							</div>
							<div class="col-lg-3 col-md-3  form-group" hidden>
								{!! Form::label('refrigerio', 'Refrigerio:') !!}
								{!! Form::select('refrigerio', $cboRefrigerio,"-1", array('class' => 'form-control input-xs', 'id' => 'refrigerio', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
							</div>
							<div class="col-lg-3 col-md-3  form-group" hidden>
								{!! Form::label('frio', 'Frio:') !!}
								{!! Form::select('frio', $cboFrio,"-1", array('class' => 'form-control input-xs', 'id' => 'frio', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
							</div>
							<div class="col-lg-3 col-md-3  form-group">
								<br/>
								{!! Form::button('<i class="fa fa-file-excel"></i> EXCEL', array('class' => 'btn btn-success btn-sm  ', 'id' => 'btnDetalle', 'onclick' => 'excel();' ,'style'=>'width:200px;margin-top:10px;')) !!}   
							</div>
						  </div>
						{!! Form::close() !!}
						</div>
					  </div>
					  <div class="row mt-2" >
						<div class="col-md-12">
						  <div class="card">
							<div class="card-header">
							  <h3 class="card-title">{{$title}}</h3>
							  <div class="card-tools">
								
							  </div>
							</div>
							<!-- /.card-header -->
							<div class="card-body table-responsive px-3">
								<div id="listado{{ $entidad }}">
								</div>
							</div>
							<!-- /.card-body -->
							   
			  
						  </div>
						  <!-- /.card -->
						</div>
					  </div>
				  </div>
			  </div>
		  </div>
		  <!-- /.row -->
		</div><!-- /.container-fluid -->
	  </div>
	  <!-- /.content -->
	
  </div>

  
<script>
	$(document).ready(function () {
		init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
		buscar('{{ $entidad }}');
	});

    function imprimir(){
        window.open("comidareporte/excelDetalle?fechainicio="+$("#fechainicio").val()+"&fechafin="+$("#fechafin").val()+"&modalidad="+$("#modalidad").val(),"_blank");
    }
    function excel(){
    	window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#listado{{$entidad}}').html()));
    }
</script>