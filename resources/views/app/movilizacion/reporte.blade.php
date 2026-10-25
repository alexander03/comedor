<link rel="stylesheet" href="dist/css/auto-complete.css">
<div class="content-wrapper p-2 ml-0 " id="container"  >
	<!-- Content Header (Page header) -->

	  <div class="row justify-content-center">
		  <div class="col-lg-12 col-md-12">
			  <div class="card mt-4">
				<div class="card-body">
				{!! Form::open(['route' => 'movilizacion.reporte', 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'w-100', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad]) !!}
				 <div class="row">
					{!! Form::hidden('page', 1, array('id' => 'page')) !!}
					{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
					<div class="col-lg-4 col-md-4  form-group">
						{!! Form::label('fechainicio', 'Fecha Inicio:') !!}
						{!! Form::date('fechainicio', date("Y-m-d"), array('class' => 'form-control input-xs', 'id' => 'fechainicio')) !!}
					</div>
					<div class="col-lg-4 col-md-4  form-group">
						{!! Form::label('fechafin', 'Fecha Fin:') !!}
						{!! Form::date('fechafin', date("Y-m-d"), array('class' => 'form-control input-xs', 'id' => 'fechafin')) !!}
					</div>
					<div class="col-lg-4 col-md-4  form-group">
						{!! Form::label('periodo', 'Periodo:') !!}
						{!! Form::text('periodo', '', array('class' => 'form-control input-xs', 'id' => 'periodo')) !!}
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-12  form-group">
						{!! Form::label('empresa', 'Empresa:') !!}
						{!! Form::text('empresa', 'INVERSIONES Y NEGOCIOS PAREDES SAC', array('class' => 'form-control input-xs js-empresa', 'id' => 'empresa')) !!}
						<small id="autoComplete_listEmpresa" class="text-danger"></small>
            			{!! Form::hidden('empresa_id', 0, array('id' => 'empresa_id')) !!}
            			{!! Form::hidden('contratista_id', 30, array('id' => 'contratista_id')) !!}
					</div>
				</div>
                <div class="row">
                    <div class="col-lg-4 col-md-4  form-group">
                        {!! Form::label('campamento_id', 'Campamento:') !!}
                        {!! Form::select('campamento_id', $cboCampamento,null, array('class' => 'form-control input-xs', 'id' => 'campamento_id')) !!}
                    </div>
                    <div class="col-lg-8 col-md-8  form-group">
                        {!! Form::label('person_id', 'Encargado:') !!}
                        {!! Form::select('person_id', $cboPersona,null, array('class' => 'form-control input-xs', 'id' => 'person_id')) !!}
                    </div>
                </div>
				<div class="row">
					<div class="col-lg-12 col-md-12  form-group text-right">
						{!! Form::button('<i class="fa fa-file-pdf"></i> VALORIZACION', array('class' => 'btn btn-outline-info', 'id' => 'btnPDFValorizacion', 'onclick' => 'pdfValorizacion();')) !!}
						{!! Form::button('<i class="fa fa-file-pdf"></i> MANIFIESTO', array('class' => 'btn btn-outline-info', 'id' => 'btnPDFReporte', 'onclick' => 'pdfReporte();')) !!}
					</div>
				</div>
			  </div>
		  </div>
	  <!-- /.content -->
	</div>
</div>

 <script src="dist/js/auto-complete.js"></script>
<script>
	$(document).ready(function() {
		configurarAnchoModal('700');
		init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
		const doSearchEmpresa = () => {

            var autoCompletejsEmpresa = new autoComplete({
                data: {
                    src: async () => {
                        // Loading placeholder text
                        document
                            .querySelector(".js-empresa")
                            .setAttribute("placeholder", "Loading...");
               
                        // Fetch External Data Source
                        const headers = new Headers();
                        headers.append('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                        const config = {
                            headers,
                            method:'GET'
                        };
                          //Query
                        const query = document.querySelector('.js-empresa').value;
                        const source = await fetch(
                            `searchAutocompleteEmpresa/${query}` , config
                        );
                        let data = await source.json();
                        // Post loading placeholder text
                        // Returns Fetched data
                        return data;
                    },
                    key: ["descripcion"],
                    cache: false
                },
                sort: (a, b) => {
                    if (a.match < b.match) return -1;
                    if (a.match > b.match) return 1;
                    return 0;
                },
                selector: ".js-empresa",
                threshold: 1,
                debounce: 0,
                searchEngine: "strict",
                highlight: true,
                maxResults: 10,
                resultsList: {
                    render: true,
                    container: source => {
                        source.setAttribute("id", "autoComplete_list");
                        source.setAttribute("class", "u-search-ua__result");
                    },
                    destination: document.querySelector(".js-empresa"),
                    position: "afterend",
                    element: "ul"
                },
                resultItem: {
                    content: (data, source) => {
                        source.innerHTML = data.match;
                    },
                    element: "li"
                },
                noResults: () => {
                    document.querySelector("#autoComplete_listEmpresa").innerText = 'Sin resultados';
                },
                onSelection: feedback => { //VA NUESTRA LOGICA
                    document.querySelector("#autoComplete_listEmpresa").innerText = '';
                    const selection = feedback.selection.value;
                    document.querySelector(".js-empresa").value = selection.razonsocial;
                    $("#empresa_id").val(selection.empresa_id);
                    $("#contratista_id").val(selection.contratista_id);
                    // console.log(feedback);
                }
            });
            
            // Toggle event for search input
            // showing & hidding results list onfocus / blur
            ["focus", "blur"].forEach(function(eventType) {
              const resultsList = document.querySelector("#autoComplete_listEmpresa");
            
              document.querySelector(".js-empresa").addEventListener(eventType, function() {
                // Hide results list & show other elemennts
                if (eventType === "blur") {
                  resultsList.style.display = "none";
                } else if (eventType === "focus") {
                  // Show results list & hide other elemennts
                  resultsList.style.display = "block";
                }
              });
            });
        };

        doSearchEmpresa();
	});
    function pdfReporte(){
        if($("#periodo").val()==""){
            alert("Debe ingresar el periodo");
            return false;    
        }
        if($("#person_id").val()==""){
            alert("Debe elegir un encargado");
            return false;    
        }
        window.open("movilizacion/pdfReporte?fechainicio="+$(IDFORMBUSQUEDA + '{!! $entidad !!} :input[id="fechainicio"]').val()+"&fechafin="+$(IDFORMBUSQUEDA + '{!! $entidad !!} :input[id="fechafin"]').val()+"&periodo="+$(IDFORMBUSQUEDA + '{!! $entidad !!} :input[id="periodo"]').val()+"&empresa="+$(IDFORMBUSQUEDA + '{!! $entidad !!} :input[id="empresa"]').val()+"&campamento_id="+$(IDFORMBUSQUEDA + '{!! $entidad !!} :input[id="campamento_id"]').val()+"&person_id="+$(IDFORMBUSQUEDA + '{!! $entidad !!} :input[id="person_id"]').val(),"_blank");
    }
    function pdfValorizacion(){
        if($("#person_id").val()==""){
            alert("Debe elegir un encargado");
            return false;    
        }
        if($(IDFORMBUSQUEDA + '{!! $entidad !!} :input[id="empresa_id"]').val()!="" || $(IDFORMBUSQUEDA + '{!! $entidad !!} :input[id="contratista_id"]').val()!=""){
            window.open("movilizacion/pdfValorizacion?fechainicio="+$(IDFORMBUSQUEDA + '{!! $entidad !!} :input[id="fechainicio"]').val()+"&fechafin="+$(IDFORMBUSQUEDA + '{!! $entidad !!} :input[id="fechafin"]').val()+"&periodo="+$(IDFORMBUSQUEDA + '{!! $entidad !!} :input[id="periodo"]').val()+"&empresa_id="+$(IDFORMBUSQUEDA + '{!! $entidad !!} :input[id="empresa_id"]').val()+"&contratista_id="+$(IDFORMBUSQUEDA + '{!! $entidad !!} :input[id="contratista_id"]').val()+"&campamento_id="+$(IDFORMBUSQUEDA + '{!! $entidad !!} :input[id="campamento_id"]').val()+"&person_id="+$(IDFORMBUSQUEDA + '{!! $entidad !!} :input[id="person_id"]').val(),"_blank");
        }else{
            alert("Debe elegir una empresa");
        }
    }
</script>