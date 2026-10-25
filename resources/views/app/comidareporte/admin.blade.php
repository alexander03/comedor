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
				<li class="breadcrumb-item"><a href="#">Usuarios</a></li>
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
						{!! Form::open(['route' => $ruta["search"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'w-100', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad]) !!}
						 <div class="row">
							{!! Form::hidden('page', 1, array('id' => 'page')) !!}
							{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
							<div class="col-lg-2 col-md-2  form-group">
								{!! Form::label('fechainicio', 'Fecha Inicio:') !!}
								{!! Form::date('fechainicio', date("Y-m-d"), array('class' => 'form-control input-xs', 'id' => 'fechainicio', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
							</div>
							<div class="col-lg-2 col-md-2  form-group">
								{!! Form::label('fechafin', 'Fecha Fin:') !!}
								{!! Form::date('fechafin', date("Y-m-d"), array('class' => 'form-control input-xs', 'id' => 'fechafin', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
							</div>
							<div class="col-lg-2 col-md-2  form-group">
								{!! Form::label('persona', 'Persona:') !!}
								{!! Form::text('persona', '', array('class' => 'form-control input-xs', 'id' => 'persona')) !!}
							</div>
							<div class="col-lg-2 col-md-2  form-group">
								{!! Form::label('servicio', 'Servicio:') !!}
								{!! Form::select('servicio', $cboServicio,null, array('class' => 'form-control input-xs', 'id' => 'servicio', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
							</div>
							<div class="col-lg-2 col-md-2  form-group">
								{!! Form::label('comedor', 'Comedor:') !!}
								{!! Form::select('comedor', $cboComedor, null, array('class' => 'form-control input-xs', 'id' => 'comedor', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
							</div>
							<div class="col-lg-2 col-md-2  form-group" style="min-width: 150px;">
								{!! Form::label('filas', 'Filas a mostrar') !!}
								{!! Form::selectRange('filas', 1, 30, 10, array('class' => 'form-control input-xs', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
							</div>
							{!! Form::close() !!}
						  </div>
						</div>
					  </div>
					  <div class="row mt-2" >
						<div class="col-md-12">
						  <div class="card">
							<div class="card-header">
							  <h3 class="card-title">{{$title}}</h3>
							  <div class="card-tools">
							  	@if($user->usertype_id!="34")
							  	{!! Form::button(' <i class="fa fa-plus fa-fw"></i> Regularizar', array('class' => 'btn  btn-outline-primary', 'id' => 'btnNuevo', 'onclick' => 'modal (\''.URL::route($ruta["create"], array('listar'=>'SI')).'\', \''.$titulo_registrar.'\', this);')) !!}
								{!! Form::button(' <i class="fa fa-plus fa-fw"></i> Agregar H2O', array('class' => 'btn  btn-outline-primary', 'id' => 'btnNuevo', 'onclick' => 'window.open(\'alimentacionh2o\',\'_blank\')')) !!}
								{!! Form::button(' <i class="fa fa-plus fa-fw"></i> Agregar CTO', array('class' => 'btn  btn-outline-primary', 'id' => 'btnNuevo', 'onclick' => 'window.open(\'alimentacioncto\',\'_blank\')')) !!}
								@endif
								{!! Form::button(' <i class="fa fa-file fa-fw"></i> Excel', array('class' => 'btn  btn-outline-success', 'id' => 'btnNuevo', 'onclick' => 'exportarLista()')) !!}
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
		buscar('{{ $entidad }}');
		init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="solicitante"]').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				buscar('{{ $entidad }}');
			}
		});
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="persona"]').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				buscar('{{ $entidad }}');
			}
		});
	});
	function exportarLista(){
		window.open('comidareporte/excel?'+$("#formBusqueda{{$entidad}}").serialize(),'_blank');
	}
</script>