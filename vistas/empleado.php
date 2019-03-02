<?php
if (!isset($_SESSION['conectado'])) header('location: ./..');

function obtenerCargos() {
    global $con;

    $stmt = $con->prepare("SELECT idCargo, nombre FROM cargo");
    $stmt->execute();
    $cargos = $stmt->fetchAll(PDO::FETCH_OBJ);

    return $cargos;
}
?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Empleado</h1>
</section>

<!-- Main content -->
<section class="content">

	<div class="row">
		<div class="col-md-12">
			<!-- Default box -->
			<div class="box box-primary" id="principal">
				<div class="box-header with-border">
					<h3 class="box-title">Listado</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-sm btn-default" id="btn-actualizar">
							<i class="fa fa-refresh"></i>
							&nbsp;&nbsp;Actualizar
						</button>
						<button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#mdlNuevo" id="btn-nuevo">
							<i class="fa fa-file"></i>
							&nbsp;&nbsp;Nuevo
						</button>
					</div>
				</div>
				<div class="box-body">
					<table class="table table-hover display responsive no-wrap" width="100%" id="table">
					    <thead>
					        <tr>
					            <th data-priority="1">ID</th>
					            <th data-priority="2">NOMBRE</th>
					            <th data-priority="3">APELLIDO</th>
					            <th data-priority="2">CEDULA</th>
					            <th data-priority="3">FEC.NACIMIENTO</th>
					            <th data-priority="4">EST.CIVIL</th>
					            <th data-priority="4">GENERO</th>
					            <th data-priority="4">CARGO</th>
					            <th data-priority="4">TELEFONO</th>
					            <th data-priority="5" class="text-right"></th>
					        </tr>
					    </thead>
					    <tbody id="table-body"></tbody>
					    <tfoot>
					        <tr>
					            <th>ID</th>
					            <th>NOMBRE</th>
					            <th>APELLIDO</th>
					            <th>CEDULA</th>
					            <th>FEC.NACIMIENTO</th>
					            <th>EST.CIVIL</th>
					            <th>GENERO</th>
					            <th>CARGO</th>
					            <th>TELEFONO</th>
					            <th class="text-right"></th>
					        </tr>
					    </tfoot>
					</table>
				</div>
			</div>
			<!-- /.box -->
		</div>
	</div>

</section>
<!-- /.content -->

<!-- modal nuevo -->
<div class="modal fade" id="mdlNuevo" tabindex="-1" role="dialog" aria-labelledby="mdlNuevo" data-backdrop="static">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<form id="formNuevo">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Nuevo Empleado</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="txt_nombre" class="control-label">Nombre</label>
								<input type="text" name="txt_nombre" id="txt_nombre" class="form-control" required="" maxlength="30">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="txt_apellido" class="control-label">Apellido</label>
								<input type="text" name="txt_apellido" id="txt_apellido" class="form-control" required="" maxlength="30">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="txt_cedula" class="control-label">Cedula</label>
								<input type="text" name="txt_cedula" id="txt_cedula" class="form-control" required="" minlength="10" maxlength="10" pattern="\d+">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="txt_fec_nac" class="control-label">Fecha Nacimiento</label>
								<input type="date" name="txt_fec_nac" id="txt_fec_nac" class="form-control" required="">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="cbo_estado_civil" class="control-label">Estado Civil</label>
								<select name="cbo_estado_civil" id="cbo_estado_civil" class="form-control" required="">
									<option value="">Seleccione</option>
									<option value="soltero">Soltero</option>
									<option value="casado">Casado</option>
									<option value="viudo">Viudo</option>
									<option value="divorciado">Divorciado</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="cbo_genero" class="control-label">Genero</label>
								<select name="cbo_genero" id="cbo_genero" class="form-control" required="">
									<option value="">Seleccione</option>
									<option value="masculino">Masculino</option>
									<option value="femenino">Femenino</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="cbo_cargo" class="control-label">Cargo</label>
								<select name="cbo_cargo" id="cbo_cargo" class="form-control" required="">
									<option value="">Seleccione</option>
									<?php foreach(obtenerCargos() as $cargo): ?>
										<option value="<?= $cargo->idCargo; ?>"><?= $cargo->nombre; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-md-12" id="cargarNuevo" style="display: none;">
							<div class="progress">
								<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
									<span class="sr-only">100% Complete</span>
								</div>
							</div>
						</div>
						<div class="col-md-12" id="errorNuevo" style="display: none;">
							<div class="callout callout-danger">
								<h4>¡Error al guardar!</h4>
								<p id="errorNuevoDetalle"></p>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">
						<i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp;Cancelar
					</button>
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;&nbsp;Guardar
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- modal editar -->
<div class="modal fade" id="mdlEditar" tabindex="-1" role="dialog" aria-labelledby="mdlEditar" data-backdrop="static">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<form id="formEditar">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Editar Empleado</h4>
				</div>
				<div class="modal-body">
					<input type="hidden" name="id" id="id">
					<div class="row" id="cargando">
						<div class="col-md-12 text-center">
							<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
							<span class="sr-only">Loading...</span>
						</div>
					</div>
					<div class="row" id="formulario">
						<div class="col-md-12">
							<div class="form-group">
								<label for="txt_nombre" class="control-label">Nombre</label>
								<input type="text" name="txt_nombre" id="txt_nombre" class="form-control" required="" maxlength="30">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="txt_apellido" class="control-label">Apellido</label>
								<input type="text" name="txt_apellido" id="txt_apellido" class="form-control" required="" maxlength="30">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="txt_cedula" class="control-label">Cedula</label>
								<input type="text" name="txt_cedula" id="txt_cedula" class="form-control" required="" minlength="10" maxlength="10" pattern="\d+">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="txt_fec_nac" class="control-label">Fecha Nacimiento</label>
								<input type="date" name="txt_fec_nac" id="txt_fec_nac" class="form-control" required="">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="cbo_est_civil" class="control-label">Estado Civil</label>
								<select name="cbo_est_civil" id="cbo_est_civil" class="form-control" required="">
									<option value="">Seleccione</option>
									<option value="soltero">Soltero</option>
									<option value="casado">Casado</option>
									<option value="viudo">Viudo</option>
									<option value="divorciado">Divorciado</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="cbo_genero" class="control-label">Genero</label>
								<select name="cbo_genero" id="cbo_genero" class="form-control" required="">
									<option value="">Seleccione</option>
									<option value="masculino">Masculino</option>
									<option value="femenino">Femenino</option>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="cbo_cargo" class="control-label">Cargo</label>
								<select name="cbo_cargo" id="cbo_cargo" class="form-control" required="">
									<option value="">Seleccione</option>
									<?php foreach(obtenerCargos() as $cargo): ?>
										<option value="<?= $cargo->idCargo; ?>"><?= $cargo->nombre; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-md-12" id="cargarEditar" style="display: none;">
							<div class="progress">
								<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
									<span class="sr-only">100% Complete</span>
								</div>
							</div>
						</div>
						<div class="col-md-12" id="errorEditar" style="display: none;">
							<div class="callout callout-danger">
								<h4>¡Error al actualizar!</h4>
								<p id="errorEditarDetalle"></p>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">
						<i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp;Cancelar
					</button>
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;&nbsp;Actualizar
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- modal telefono -->
<div class="modal fade" id="mdlTelefono" tabindex="-1" role="dialog" aria-labelledby="mdlTelefono" data-backdrop="static">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<form id="formTelefono">
				<input type="hidden" name="idEmpleadoTelefono" id="idEmpleadoTelefono">
				<input type="hidden" name="idTelefono" id="idTelefono" value="0">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Telefono(s) del empleado</h4>
				</div>
				<div class="modal-body">
					
					<div class="row" id="cargando">
						<div class="col-md-8 col-sm-8 col-xs-8">
							<div class="form-group">
								<label for="txt_numero" class="control-label">Numero</label>
								<input type="text" name="txt_numero" id="txt_numero" class="form-control" required="" minlength="7" maxlength="15" pattern="\d+">
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="col-md-4 col-sm-4 col-xs-4 pull-right">
							<div style="position: relative;">
								<div style="position: absolute; bottom: 15px; left: 0px; right: 0px;">
									<button class="btn btn-primary btn-block" id="btn-agregar-telefono">Agregar</button>
								</div>
							</div>
						</div>						
					</div>
					<div class="row" id="formulario">

		<div class="col-md-12">
			<!-- Default box -->
			<div class="box box-primary" id="principal">
				<div class="box-header with-border">
					<h3 class="box-title">Listado</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-sm btn-default" id="btn-actualizar-telefonos">
							<i class="fa fa-refresh"></i>
							&nbsp;&nbsp;Actualizar
						</button>
					</div>
				</div>
				<div class="box-body">
					<table class="table table-hover display responsive no-wrap" width="100%" id="tableTelefonos">
					    <thead>
					        <tr>
					            <th data-priority="1">ID</th>
					            <th data-priority="2">NUMERO</th>
					            <th data-priority="5" class="text-right"></th>
					        </tr>
					    </thead>
					    <tbody id="tableTelefonos-body"></tbody>
					    <tfoot>
					        <tr>
					            <th>ID</th>
					            <th>NUMERO</th>
					            <th class="text-right"></th>
					        </tr>
					    </tfoot>
					</table>
				</div>
			</div>
			<!-- /.box -->
		</div>


						<div class="col-md-12" id="errorEditar" style="display: none;">
							<div class="callout callout-danger">
								<h4>¡Error al actualizar!</h4>
								<p id="errorEditarDetalle"></p>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">
						<i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp;Cancelar
					</button>
				</div>
			</form>
		</div>
	</div>
</div>




<script>
$(document).ready(function() {

	var table = getTable();
	var tableTelefono = null;

	$('#btn-actualizar').click(function(event) {
		refreshTable();
	});

	$('#btn-actualizar-telefonos').click(function(event) {
		refreshTableTelefonos();
	});

	$('#mdlNuevo').on('show.bs.modal', function (event) {
		$("#formNuevo")[0].reset();
		var modal = $(this);
		modal.find('.modal-body #cargarNuevo').hide();
		modal.find('.modal-body #errorNuevo').hide();
	});

	$('#mdlNuevo').on('shown.bs.modal', function (event) {
		var modal = $(this);
		modal.find('.modal-body #txt_nombre').focus();
	});

    $('#formNuevo').submit(function(e) {
    	$('#cargarNuevo').show();
    	$('#errorNuevo').hide();

        e.preventDefault();

        $.ajax({
            url: 'modulo/empleado.php?metodo=guardar',
            type : "POST",
            dataType : 'json',
            data : $(this).serialize()
        })
		.done(function( data, textStatus, jqXHR ) {
			refreshTable();
			$('#mdlNuevo').modal('hide');
		})
		.fail(function( jqXHR, textStatus, errorThrown ) {
			$('#cargarNuevo').hide();
			$('#errorNuevo').show();
			$('#errorNuevoDetalle').html(jqXHR['responseJSON'].error);
		});
    });

	$('#mdlEditar').on('show.bs.modal', function (event) {
		$('#cargando').show();
		$('#formulario').hide();
		$('#cargarEditar').hide();
		$('#errorEditar').hide();
		$("#formEditar")[0].reset();
		var button = $(event.relatedTarget);
		var id = button.data('id');
		var modal = $(this);

		$.ajax({
            url: 'modulo/empleado.php?metodo=obtener',
            type : "POST",
            dataType : 'json',
            data : {
            	id: id
            }
		})
		.done(function( data, textStatus, jqXHR ) {
			modal.find('.modal-body #id').val(id);
			modal.find('.modal-body #txt_nombre').val(data.nombre);
			modal.find('.modal-body #txt_apellido').val(data.apellido);
			modal.find('.modal-body #txt_cedula').val(data.cedula);
			modal.find('.modal-body #txt_fec_nac').val(data.fechaNacimiento);
			modal.find('.modal-body #cbo_est_civil').val(data.estadoCivil);
			modal.find('.modal-body #cbo_genero').val(data.genero);
			modal.find('.modal-body #cbo_cargo').val(data.idCargo);
			$('#cargando').hide();
			$('#formulario').show();
			modal.find('.modal-body #txt_nombre').focus();
		})
		.fail(function( jqXHR, textStatus, errorThrown ) {
			console.log("error");
		});
	});

    $('#formEditar').submit(function(e) {
    	$('#cargarEditar').show();
    	$('#errorEditar').hide();

        e.preventDefault();

        $.ajax({
            url: 'modulo/empleado.php?metodo=editar',
            type : "POST",
            dataType : 'json',
            data : $(this).serialize()
        })
		.done(function( data, textStatus, jqXHR ) {
			refreshTable();
			$('#mdlEditar').modal('hide');
		})
		.fail(function( jqXHR, textStatus, errorThrown ) {
			console.log(jqXHR, textStatus, errorThrown);
			$('#cargarEditar').hide();
			$('#errorEditar').show();
			$('#errorEditarDetalle').html(jqXHR['responseJSON'].error);
		});
    });

	$('#mdlTelefono').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var id = button.data('id');
		$('#idEmpleadoTelefono').val(id);
		tableTelefono = getTableTelefono();
		var modal = $(this);
		modal.find('.modal-body #txt_numero').val('');
		modal.find('.modal-body #cargarNuevo').hide();
		modal.find('.modal-body #errorNuevo').hide();
	});

	$('#mdlTelefono').on('shown.bs.modal', function (event) {
		var modal = $(this);
		modal.find('.modal-body #txt_numero').focus();
	});

    $('#formTelefono').submit(function(e) {
    	$('#mdlTelefono #cargarEditar').show();
    	$('#mdlTelefono #errorEditar').hide();

        e.preventDefault();

        var metodo = ($('#idTelefono').val() == 0) ? 'guardar' : 'editar';

        $.ajax({
            url: 'modulo/telefono.php?metodo='+metodo,
            type : "POST",
            dataType : 'json',
            data : $(this).serialize()
        })
		.done(function( data, textStatus, jqXHR ) {
			refreshTableTelefonos();
			$('#idTelefono').val(0);
			$('#btn-agregar-telefono').text('Agregar');
			$('#mdlTelefono #txt_numero').val('');
			$('#mdlTelefono #txt_numero').focus();
		})
		.fail(function( jqXHR, textStatus, errorThrown ) {
			console.log(jqXHR, textStatus, errorThrown);
			$('#mdlTelefono #cargarEditar').hide();
			$('#mdlTelefono #errorEditar').show();
			$('#mdlTelefono #errorEditarDetalle').html(jqXHR['responseJSON'].error);
		});
    });	

	function addAEvent(){
		var btn_editar_telefono = $('.btn-editar-telefono');
		var btn_eliminar_telefono = $('.btn-eliminar-telefono');

		btn_editar_telefono.unbind();
		btn_eliminar_telefono.unbind();

		btn_editar_telefono.on('click', function(e) {
			var btn = $(this);
			var id = btn.attr('data-id');
			var telefono = btn.attr('data-telefono');
			$('#btn-agregar-telefono').text('Editar')
			$('#mdlTelefono #idTelefono').val(id);
			$('#mdlTelefono #txt_numero').val(telefono);
			$('#mdlTelefono #txt_numero').focus();
		});

		btn_eliminar_telefono.on('click', function(e) {
			var btn = $(this);
			eliminar_fila(btn, btn.attr('data-id'));
		});	
	}

	function eliminar_fila(btn, id)
	{
	    if (confirm("¿ Desea eliminar la fila seleccionada ?")) {
			$('#mdlTelefono #cargarEditar').show();
			$('#mdlTelefono #errorEditar').hide();
	        $.ajax({
	            url: 'modulo/telefono.php?metodo=eliminar',
	            type : "POST",
	            dataType : 'json',
	            data : {
	            	idTelefono: id
	            }
	        })
			.done(function( data, textStatus, jqXHR ) {
				refreshTableTelefonos();
			})
			.fail(function( jqXHR, textStatus, errorThrown ) {
				console.log(jqXHR, textStatus, errorThrown);
				$('#mdlTelefono #cargarEditar').hide();
				$('#mdlTelefono #errorEditar').show();
				$('#mdlTelefono #errorEditarDetalle').html(jqXHR['responseJSON'].error);
			});
	    	
	    }
	}

    function refreshTable() {
		table.destroy();
		$('#table-body').hide();
		table = getTable();
    };
	
	function getTable() {
	    return  $('#table')
				    .DataTable({
				    	"language":{
				    		"processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>',
				    		"url": "assets/datatables/i18n/spanish.lang"
				    	},
						"processing": true,
						"serverSide": true,
				        "destroy": true,
				        "responsive": true,
				        "ajax":{
				            "method": "POST",
				            "url": "modulo/empleado.php?metodo=listado",
				            "complete": function () {
				            	$('#table-body').show();
				            },
				            "error": function (xhr, error, thrown) {
				            	console.log(xhr, error, thrown);
				            }
				        },
				        "order": 
				        [
				        	[0, "asc"]
				        ],
				        "columns":
				        [
							{				        	
								"data": 0, "render": function ( data, type, row ) {
									return data;
								}
							},
							{
								"data": 1, "render": function ( data, type, row ) {
									return data;
								}
							},			        				        
							{
								"data": 2, "render": function ( data, type, row ) {
									return data;
								}
							},
							{
								"data": null, "render": function ( data, type, row ) {
									return data[6];
								}
							},	
							{
								"data": 3, "render": function ( data, type, row ) {
									return data;
								}
							},
							{
								"data": 4, "render": function ( data, type, row ) {
									return data;
								}
							},
							{
								"data": 5, "render": function ( data, type, row ) {
									return data;
								}
							},
							{
								"data": 7, "render": function ( data, type, row ) {
									return data;
								}
							},
							{
								"data": null, "render": function ( data ) {
									var html = '<div class="text-right">' +
									'<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#mdlTelefono"'+
									'data-id="' + data[0] + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;&nbsp;Telefono</button>' +
									'</div>';
									return html;
								},
								"searchable": false,
								"orderable": false
							},							
							{
								"data": null, "render": function ( data ) {
									var html = '<div class="text-right">' +
									'<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#mdlEditar"'+
									'data-id="' + data[0] + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;&nbsp;Editar</button>' +
									'</div>';
									return html;
								},
								"searchable": false,
								"orderable": false
							}
				        ]
				    });
	};

    function refreshTableTelefonos() {
		tableTelefono.ajax.reload();
    };

	function getTableTelefono() {
	    return  $('#tableTelefonos')
				    .DataTable({
				    	"dom": 'tr',
				    	"language":{
				    		"processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>',
				    		"url": "assets/datatables/i18n/spanish.lang"
				    	},
						"processing": true,
						"serverSide": true,
				        "destroy": true,
				        "responsive": true,
				        "ajax":{
				            "method": "POST",
				            "url": "modulo/telefono.php?metodo=listado",
				            "data": {
				            	idEmpleadoTelefono: $('#idEmpleadoTelefono').val()
				            },
				            "complete": function () {
				            	$('#tableTelefonos-body').show();
				            	addAEvent();
				            },
				            "error": function (xhr, error, thrown) {
				            	console.log(xhr, error, thrown);
				            	$('#principal').hide();
				            	if (xhr['status'] == '404') {
				            		$('#recursoNoEncontrado').show();
				            	} else if (xhr['status'] == '500') {
				            		$('#errorServidor').show();
				            	}
				            }
				        },
				        "order": 
				        [
				        	[0, "asc"]
				        ],
				        "columns":
				        [
							{				        	
								"data": 0, "render": function ( data, type, row ) {
									return data;
								}
							},
							{
								"data": 1, "render": function ( data, type, row ) {
									return data;
								}
							},							
							{
								"data": null, "render": function ( data ) {
									var html = '<div class="text-right">' +
									'<button type="button" class="btn btn-sm btn-primary btn-editar-telefono" '+
									'data-id="' + data[0] + ' "data-telefono="' + data[1] + '">'+
									'<i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;&nbsp;Editar</button>' +
									'&nbsp;&nbsp;'+
									'<button type="button" class="btn btn-sm btn-danger btn-eliminar-telefono" '+
									'data-id="' + data[0] + '"><i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;&nbsp;Eliminar</button>' +
									'</div>';
									return html;
								},
								"searchable": false,
								"orderable": false
							}
				        ]

				    });
	};	

});

</script>