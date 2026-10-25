@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="dist/css/auto-complete.css">
<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="dist/css/toastr.min.css">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 col-sm-12 col-xs-12" style="padding-right: 20px !important;">
            <div class="card">
                <div class="card-header">Toma Pedido Grupal</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('webservices.guardarPedidoGrupal') }}" id="frmPedido">
                        <input type="hidden" name="saltar_validacion" value="S">
                        @csrf
                        <div class="form-group row">
                            <label for="fecha" class="col-md-4 col-form-label text-md-right">Fecha</label>
                            <div class="col-md-8">
                                 <input id="fecha" type="datetime-local" name="fecha" value="" class="form-control input-xs" onchange="consultarCiclico(this.value);" />
                            </div>
                        </div>
                        <div class="row">
                            @if(!is_null($desayuno))
                            <label for="" class="col-md-12 col-form-label text-md-right btn-primary" id="lblDesayuno">Desayuno: {{$desayuno}}</label>
                            @endif
                            @if(!is_null($almuerzo))
                            <label for="" class="col-md-12 col-form-label text-md-right btn-primary" id="lblAlmuerzo">Almuerzo: {{$almuerzo}}</label>
                            @endif
                            @if(!is_null($cena))
                            <label for="" class="col-md-12 col-form-label text-md-right btn-primary" id="lblCena">Cena: {{$cena}}</label>
                            @endif
                        </div>
                        <div class="form-group row">
                            <label for="concesionaria_id" class="col-md-4 col-form-label text-md-right">Campamento</label>
                            <div class="col-md-8">
                                 {!! Form::select('concesionaria_id',[""=>"Seleccione","1"=>"H2O","2"=>"CTO"], null, array('class' => 'form-control input-xs', 'id' => 'concesionaria_id')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="solicitante" class="col-md-4 col-form-label text-md-right">Solicitante</label>
                            <div class="col-md-8">
                                {!! Form::text('solicitante', null, array('class' => 'form-control input-xs js-solicitante', 'id' => 'solicitante', 'autocomplete'=>'off')) !!}
                                <small id="autoComplete_listSolicitante" class="text-danger"></small>
                                {!! Form::hidden('solicitante_id', 0, array('id' => 'solicitante_id')) !!}
                                {!! Form::hidden('solicitantewp_id', 0, array('id' => 'solicitantewp_id')) !!}
                                {!! Form::hidden('correo', null, array('id' => 'correo')) !!}
                            </div>
                        </div>
                        <div class="form-group row" style="display: none;">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Lider</label>
                            <div class="col-md-8">
                                {!! Form::text('lider', null, array('class' => 'form-control input-xs js-lider', 'id' => 'lider', 'autocomplete'=>'off')) !!}
                                <small id="autoComplete_listLider" class="text-danger"></small>
                                {!! Form::hidden('lider_id', 0, array('id' => 'lider_id')) !!}
                                {!! Form::hidden('liderwp_id', 0, array('id' => 'liderwp_id')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Lugar</label>
                            <div class="col-md-8">
                                {!! Form::text('lugar', null, array('class' => 'form-control input-xs js-lugar', 'id' => 'lugar', 'autocomplete'=>'off')) !!}
                                <small id="autoComplete_listLugar" class="text-danger"></small>
                                {!! Form::hidden('lugar_id', null, array('id' => 'lugar_id')) !!}
                                {!! Form::hidden('locacion_id', null, array('id' => 'locacion_id')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Observacion</label>
                            <div class="col-md-8">
                                {!! Form::textarea('observacion', null, array('class' => 'form-control input-xs', 'id' => 'observacion','rows'=>'5')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cantidad" class="col-md-4 col-form-label text-md-right">Cantidad</label>
                            <div class="col-md-8">
                                {!! Form::number('cantidad', null, array('class' => 'form-control input-xs', 'id' => 'cantidad', 'min'=>'1')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="servicio" class="col-md-4 col-form-label text-md-right">Servicio</label>
                            <div class="col-md-8">
                                {!! Form::select('servicio',$cboServicio, null, array('class' => 'form-control input-xs', 'id' => 'servicio','onchange'=>'cambiarCarnico(this.value);')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="carnico" class="col-md-4 col-form-label text-md-right">Opcion Carnico</label>
                            <div class="col-md-8">
                                {!! Form::select('carnico',$cboCarnico, null, array('class' => 'form-control input-xs', 'id' => 'carnico')) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="empresa" class="col-md-4 col-form-label text-md-right">Empresa</label>
                            <div class="col-md-8">
                                {!! Form::text('empresa', null, array('class' => 'form-control input-xs js-empresa', 'id' => 'empresa', 'autocomplete'=>'off')) !!}
                                <small id="autoComplete_listEmpresa" class="text-danger"></small>
                                {!! Form::hidden('empresa_id', 0, array('id' => 'empresa_id')) !!}
                                {!! Form::hidden('contratista_id', 0, array('id' => 'contratista_id')) !!}
                            </div>
                        </div>
                        <div class="form-group row" style="display:none;">
                            <label for="dieta" class="col-md-4 col-form-label text-md-right">Dieta</label>
                            <div class="col-md-7">
                                {!! Form::select('dieta',["NO"=>"NO","SI"=>"SI"], null, array('class' => 'form-control input-xs', 'id' => 'dieta')) !!}
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-12 text-right">
                                <button type="button" class="btn btn-primary" onclick='guardar();' id="btnGuardar">
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="dist/js/auto-complete.js"></script>
<script src="dist/js/funciones.js"></script>
<script src="dist/js/toastr.min.js"></script>

<script type="text/javascript">
    var a = <?php echo 1;//$contador;?> -1;
    $(document).ready(function() {
        const doSearchSolicitante = () => {

            var autoCompletejsSolicitante = new autoComplete({
                data: {
                    src: async () => {
                        // Loading placeholder text
                        document
                            .querySelector(".js-solicitante")
                            .setAttribute("placeholder", "Loading...");
               
                        // Fetch External Data Source
                        const headers = new Headers();
                        headers.append('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                        const config = {
                            headers,
                            method:'GET'
                        };
                          //Query
                        const query = document.querySelector('.js-solicitante').value;
                        const source = await fetch(
                            `searchAutocompletePersona/${query}` , config
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
                selector: ".js-solicitante",
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
                    destination: document.querySelector(".js-solicitante"),
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
                    document.querySelector("#autoComplete_listSolicitante").innerText = 'Sin resultados';
                    $('#solicitante_id').val("0");
                    $('#solicitantewp_id').val("0");
                    $('#correo').val("");
                },
                onSelection: feedback => { //VA NUESTRA LOGICA
                    document.querySelector("#autoComplete_listSolicitante").innerText = '';
                    const selection = feedback.selection.value;
                    document.querySelector(".js-solicitante").value = selection.persona;
                    document.querySelector("#solicitante_id").value = selection.persona_id;
                    document.querySelector("#solicitantewp_id").value = selection.personawp_id;
                    document.querySelector("#correo").value = selection.correo;
                    // console.log(feedback);
                }
            });
            
            // Toggle event for search input
            // showing & hidding results list onfocus / blur
            ["focus", "blur"].forEach(function(eventType) {
              const resultsList = document.querySelector("#autoComplete_listSolicitante");
            
              document.querySelector(".js-solicitante").addEventListener(eventType, function() {
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


        const doSearchLider = () => {

            var autoCompletejsLider = new autoComplete({
                data: {
                    src: async () => {
                        // Loading placeholder text
                        document
                            .querySelector(".js-lider")
                            .setAttribute("placeholder", "Loading...");
               
                        // Fetch External Data Source
                        const headers = new Headers();
                        headers.append('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                        const config = {
                            headers,
                            method:'GET'
                        };
                          //Query
                        const query = document.querySelector('.js-lider').value;
                        const source = await fetch(
                            `searchAutocompletePersona/${query}` , config
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
                selector: ".js-lider",
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
                    destination: document.querySelector(".js-lider"),
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
                    document.querySelector("#autoComplete_listLider").innerText = 'Sin resultados';
                    $('#persona_id').val("0");
                },
                onSelection: feedback => { //VA NUESTRA LOGICA
                    document.querySelector("#autoComplete_listLider").innerText = '';
                    const selection = feedback.selection.value;
                    document.querySelector(".js-lider").value = selection.persona;
                    document.querySelector("#lider_id").value = selection.id;
                    document.querySelector("#liderwp_id").value = 0;
                    // console.log(feedback);
                }
            });
            
            // Toggle event for search input
            // showing & hidding results list onfocus / blur
            ["focus", "blur"].forEach(function(eventType) {
              const resultsList = document.querySelector("#autoComplete_listLider");
            
              document.querySelector(".js-lider").addEventListener(eventType, function() {
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
                    document.querySelector("#contratista_id").value = selection.contratista_id;
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

        const doSearchLugar = () => {

            var autoCompletejsLugar = new autoComplete({
                data: {
                    src: async () => {
                        // Loading placeholder text
                        document
                            .querySelector(".js-lugar")
                            .setAttribute("placeholder", "Loading...");
               
                        // Fetch External Data Source
                        const headers = new Headers();
                        headers.append('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                        const config = {
                            headers,
                            method:'GET'
                        };
                          //Query
                        const query = document.querySelector('.js-lugar').value;
                        const source = await fetch(
                            `searchAutocompleteLugar/${query}` , config
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
                selector: ".js-lugar",
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
                    destination: document.querySelector(".js-lugar"),
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
                    document.querySelector("#autoComplete_listLugar").innerText = 'Sin resultados';
                    $("#lugar_id").val("0");
                    $("#locacion_id").val("0");
                },
                onSelection: feedback => { //VA NUESTRA LOGICA
                    document.querySelector("#autoComplete_listLugar").innerText = '';
                    const selection = feedback.selection.value;
                    document.querySelector(".js-lugar").value = selection.descripcion;
                    $("#lugar_id").val(selection.lugar_id);
                    $("#locacion_id").val(selection.locacion_id);
                    // console.log(feedback);
                }
            });
            
            // Toggle event for search input
            // showing & hidding results list onfocus / blur
            ["focus", "blur"].forEach(function(eventType) {
              const resultsList = document.querySelector("#autoComplete_listLugar");
            
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
        doSearchSolicitante();
        doSearchLider();
        doSearchEmpresa();
        doSearchLugar();
    });


var contador=0;
function guardar() {
    var band=true;
    var msg="";
    var btn = $("#btnGuardar");
    btn.attr('disabled', true);

    if($("#solicitante").val()==""){
        band = false;
        msg += " *No se selecciono un solicitante \n";
    }
    if($("#solicitante_id").val()=="0" && $("#solicitantewp_id").val()=="0"){
        band = false;
        msg += " *No se selecciono un solicitante \n";
    }
    if($("#lugar").val()==""){
        band = false;
        msg += " *No se selecciono un lugar \n";
    }
    if($("#lugar_id").val()=="0" && $("#locacion_id").val()=="0"){
        band = false;
        msg += " *No se selecciono un lugar \n";
    }
    if($('#fecha').val()==""){
        band = false;
        msg += " *Debe ingresar una fecha \n";
    }
    if($('#cantidad').val()==""){
        band = false;
        msg += " *Debe ingresar una cantidad \n";
    }
    if($('#concesionaria_id').val()==""){
        band = false;
        msg += " *Debe seleccionar una concesionaria \n";
    }
    if($('#concesionaria_id').val()=="1" && $("#locacion_id").val()=="2"){//h2o y garita cto
        band = false;
        msg += " *Combinacion de campamento y lugar no permitida \n";
    }
    if($('#concesionaria_id').val()=="1" && $("#locacion_id").val()=="3"){//h2o y sala cto
        band = false;
        msg += " *Combinacion de campamento y lugar no permitida \n";
    }
    if($('#concesionaria_id').val()=="2" && $("#locacion_id").val()=="1"){//cto y sala h2o
        band = false;
        msg += " *Combinacion de campamento y lugar no permitida \n";
    }
    if($("#empresa").val()==""){
        band = false;
        msg += " *Debe ingresar empresa \n";
    }
    /*if($("#lider").val()==""){
        band = false;
        msg += " *No se selecciono un lider \n";
    }*/
    var vmsg2 = "Desea guardar la siguiente información:\n";
    vmsg2+="*Fecha y Hora: "+$("#fecha").val()+"\n";
    vmsg2+="*Campamento: "+$("#concesionaria_id option:selected").text()+"\n";
    vmsg2+="*Solicitante: "+$("#solicitante").val()+"\n";
    vmsg2+="*Cantidad: "+$("#cantidad").val()+"\n";
    vmsg2+="*Lugar: "+$("#lugar").val()+"\n";
    vmsg2+="*Obs: "+$("#observacion").val()+"\n";
    vmsg2+="*Empresa: "+$("#empresa").val()+"\n";
    vmsg2+="*Detalle: "+$("#servicio option:selected").text()+" | " + $("#carnico option:selected").text()+ "\n";
    
    if(confirm(vmsg2)){
        if(band && contador==0){
            contador=1;
            var idformulario = "#frmPedido";
            var data         = submitForm(idformulario);
            var respuesta    = '';
            var error = '';
            data.done(function(msg) {
                respuesta = msg;
            }).fail(function(xhr, textStatus, errorThrown) {
                respuesta = 'ERROR PETICION';
                contador=0;
                btn.attr('disabled', false);
            }).always(function() {

                if(respuesta === 'ERROR PETICION'){
                    console.log(error);
                    btn.attr('disabled', false);
                    contador=0;
                }else{
                    resp = respuesta
                    if(respuesta!="OK"){
                        dat = JSON.parse(respuesta);
                    }
                    if (resp === 'OK') {
                        mostrarMensaje('Guardado correctamente', 'OK');
                        window.open('https://sistemasolmos.pe/comedorsystem/tomapedidoGrupal','_self');
                    } else if(dat.respuesta=="ERROR") {
                        alert(dat.msg);
                        btn.attr('disabled', false);
                        contador=0;
                    } else {
                        mostrarErrores(respuesta, idformulario, entidad);
                        btn.attr('disabled', false);
                        contador=0;
                    }
                }
            });
        }else{
            //toastr.error(msg , "Corrige los siguientes errores");
            alert(msg);
            btn.attr('disabled', false);
        }
    }else{
        btn.attr('disabled', false);
    }
}

function consultarCiclico(vfecha){
    $.ajax({
        type: "POST",
        url: "consultarCiclico",
        data: "fecha="+vfecha+"&_token="+$(':input[name="_token"]').val(),
        success: function(dat) {
            if(dat.desayuno!=null){
                $("#lblDesayuno").html("Desayuno: "+dat.desayuno["fondo"]);
            }else{
                $("#lblDesayuno").html("Desayuno: -");
            }
            if(dat.almuerzo!=null){
                $("#lblAlmuerzo").html("Almuerzo: "+dat.almuerzo["fondo"]);
            }else{
                $("#lblAlmuerzo").html("Almuerzo: -");
            }
            if(dat.cena!=null){
                $("#lblCena").html("Cena: "+dat.cena["fondo"]);
            }else{
                $("#lblCena").html("Cena: -");
            }
        }
    });
}

function cambiarCarnico(vidservicio){
    $.ajax({
        type: "POST",
        url: "consultarCarnico",
        data: "servicio_id="+vidservicio+"&_token="+$(':input[name="_token"]').val(),
        success: function(dat) {
            $("#carnico").html(dat);
        }
    });
}
</script>
@endsection
