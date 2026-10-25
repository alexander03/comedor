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
								{!! Form::date('fechafin', date("Y-m-d",strtotime("-1 day",strtotime("+1 month",strtotime(date("Y-m-")."01")))), array('class' => 'form-control input-xs', 'id' => 'fechafin', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
							</div>
							<div class="col-lg-2 col-md-2  form-group">
								{!! Form::label('solicitante', 'Solicitante:') !!}
								{!! Form::text('solicitante', '', array('class' => 'form-control input-xs', 'id' => 'solicitante')) !!}
							</div>
							<div class="col-lg-2 col-md-2  form-group">
								{!! Form::label('lider', 'Lider:') !!}
								{!! Form::text('lider', '', array('class' => 'form-control input-xs', 'id' => 'lider')) !!}
							</div>
							<div class="col-lg-2 col-md-2  form-group">
								{!! Form::label('lugar', 'Lugar:') !!}
								{!! Form::select('lugar', $cboLugar,null, array('class' => 'form-control input-xs', 'id' => 'lugar', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
							</div>
							<div class="col-lg-2 col-md-2  form-group">
								{!! Form::label('concesionaria_id', 'Campamento:') !!}
								{!! Form::select('concesionaria_id',["0"=>"Ninguno","1"=>"H2O","2"=>"CTO"], 0, array('class' => 'form-control input-xs', 'id' => 'concesionaria_id','onchange' => 'buscar(\''.$entidad.'\')')) !!}
							</div>
						</div>
						<div class="row">
							<div class="col-lg-2 col-md-2  form-group">
								{!! Form::label('desayuno', 'Desayuno:') !!}
								{!! Form::select('desayuno', $cboDesayuno,"-1", array('class' => 'form-control input-xs', 'id' => 'desayuno', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
							</div>
							<div class="col-lg-2 col-md-2  form-group">
								{!! Form::label('almuerzo', 'Almuerzo:') !!}
								{!! Form::select('almuerzo', $cboAlmuerzo,"-1", array('class' => 'form-control input-xs', 'id' => 'almuerzo', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
							</div>
							<div class="col-lg-2 col-md-2  form-group">
								{!! Form::label('cena', 'Cena:') !!}
								{!! Form::select('cena', $cboCena,"-1", array('class' => 'form-control input-xs', 'id' => 'cena', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
							</div>
							<div class="col-lg-2 col-md-2  form-group">
								{!! Form::label('refrigerio', 'Refrigerio:') !!}
								{!! Form::select('refrigerio', $cboRefrigerio,"-1", array('class' => 'form-control input-xs', 'id' => 'refrigerio', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
							</div>
							<div class="col-lg-2 col-md-2  form-group">
								{!! Form::label('frio', 'Frio:') !!}
								{!! Form::select('frio', $cboFrio,"-1", array('class' => 'form-control input-xs', 'id' => 'frio', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
							</div>
							<div class="col-lg-2 col-md-2  form-group">
								{!! Form::label('carnico', 'Op. Carnico:') !!}
								{!! Form::select('carnico', $cboCarnico,null, array('class' => 'form-control input-xs', 'id' => 'carnico', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
							</div>
							<div class="col-lg-2 col-md-2  form-group">
								{!! Form::label('empresa', 'Empresa:') !!}
								{!! Form::text('empresa', '', array('class' => 'form-control input-xs', 'id' => 'empresa')) !!}
							</div>
							<div class="col-lg-2 col-md-2  form-group" style="display: none;">
								{!! Form::label('dieta', 'Dieta:') !!}
								{!! Form::select('dieta', $cboDieta,null, array('class' => 'form-control input-xs', 'id' => 'dieta', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
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
							  	{!! Form::button(' <i class="fa fa-search fa-fw"></i> Buscar', array('class' => 'btn  btn-outline-primary', 'id' => 'btnBuscar', 'onclick' => 'buscar(\''.$entidad.'\')')) !!}
							  	@if($user->usertype_id!="34")
								{!! Form::button(' <i class="fa fa-plus fa-fw"></i> Agregar', array('class' => 'btn  btn-outline-primary', 'id' => 'btnNuevo', 'onclick' => 'window.open(\'tomapedidoGrupal\',\'_blank\')')) !!}
								{!! Form::button(' <i class="fa fa-file fa-fw"></i> Excel', array('class' => 'btn  btn-outline-success', 'id' => 'btnNuevo', 'onclick' => 'exportarLista()')) !!}
								@endif
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
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="lider"]').keyup(function (e) {
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
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="empresa"]').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				buscar('{{ $entidad }}');
			}
		});
	});
	function exportarLista(){
		window.open('pedidogrupal/excelLista?'+$("#formBusqueda{{$entidad}}").serialize(),'_blank');
	}
</script>