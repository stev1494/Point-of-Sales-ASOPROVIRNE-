<?php
if (!isset($_SESSION['conectado'])) header('location: ./..');

function obtenerEmpleados() {
    global $con;

    $stmt = $con->prepare("SELECT idEmpleado, nombre, apellido FROM empleado");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function obtenerOrdenes() {
    global $con;

    $stmt = $con->prepare("SELECT idOrden FROM orden_despacho_cabecera WHERE estado=1 AND vendida=0");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function obtenerExportadores() {
    global $con;

    $stmt = $con->prepare("SELECT idExportador, razonSocial FROM exportador");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function obtenerDestinos() {
    global $con;

    $stmt = $con->prepare("SELECT idDestExp, destino FROM destino_exporta");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}
?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Venta</h1>
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
						<button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#mdlNuevo">
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
					            <th data-priority="2">ORDEN</th>
					            <th data-priority="3">EMPLEADO</th>
					            <th data-priority="4">EXPORTADOR</th>
					            <th data-priority="5">DESTINO</th>
					            <th data-priority="6">FECHA</th>
					            <th data-priority="20">HORA</th>
					            <th data-priority="9">TOTAL</th>
					            <th data-priority="15">OBSERVACION</th>
					            <th data-priority="7">ESTADO</th>
					            <th data-priority="8" class="text-right"></th>
					        </tr>
					    </thead>
					    <tbody id="table-body"></tbody>
					    <tfoot>
					        <tr>
					            <th>ID</th>
					            <th>ORDEN</th>
					            <th>EMPLEADO</th>
					            <th>EXPORTADOR</th>
					            <th>DESTINO</th>
					            <th>FECHA</th>
					            <th>HORA</th>
					            <th>TOTAL</th>
					            <th>OBSERVACION</th>
					            <th>ESTADO</th>
					            <th class="text-right"></th>
					        </tr>
					    </tfoot>
					</table>
				</div>
				<div class="box-footer">
					<div class="pull right">
						<div class="col-md-2 col-md-offset-8">
							<span class="badge bg-green"><i class="fa fa-check" aria-hidden="true"></i></span>&nbsp;&nbsp;Confirmada
						</div>
						<div class="col-md-2">
							<span class="badge bg-red"><i class="fa fa-ban" aria-hidden="true"></i></span>&nbsp;&nbsp;Anulada
						</div>
					</div>
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
				<input type="hidden" name="total" id="total">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Nueva Venta</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="cbo_orden" class="control-label">Orden</label>
								<select name="cbo_orden" id="cbo_orden" class="form-control" required="">
									<option value="">Seleccione</option>
									<?php foreach(obtenerOrdenes() as $orden): ?>
										<option value="<?= $orden->idOrden; ?>"><?= $orden->idOrden; ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<label for="cbo_exportador" class="control-label">Exportador</label>
								<select name="cbo_exportador" id="cbo_exportador" class="form-control" required="">
									<option value="">Seleccione</option>
									<?php foreach(obtenerExportadores() as $exportador): ?>
										<option value="<?= $exportador->idExportador; ?>"><?= $exportador->razonSocial; ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="cbo_destino" class="control-label">Destino</label>
								<select name="cbo_destino" id="cbo_destino" class="form-control" required="">
									<option value="">Seleccione</option>
									<?php foreach(obtenerDestinos() as $destino): ?>
										<option value="<?= $destino->idDestExp; ?>"><?= $destino->destino; ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="col-md-12">
						<table class="table table-hover display responsive no-wrap" width="100%" id="table-articulos">
						    <thead>
						        <tr>
						        	<th width="5%">#</th>
						            <th width="45%">ARTICULO</th>
						            <th width="10%" class="text-right">PRECIO</th>
						            <th width="20%" class="text-right">CANTIDAD</th>
						            <th width="20%" class="text-right">SUBTOTAL</th>
						        </tr>
						    </thead>
						    <tbody id="table-body"></tbody>
						    <tfoot>
						        <tr>
						        	<th colspan="3">TOTAL</th>
						        	<th colspan="2" class="text-right"><span id="detalle-subtotal"></span></th>
						        </tr>
						    </tfoot>
						</table>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="txt_observacion" class="control-label">Observacion</label>
								<input type="text" name="txt_observacion" id="txt_observacion" class="form-control" maxlength="30">
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
						<i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp;Guardar
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- modal ver -->
<div class="modal fade" id="mdlVer" tabindex="-1" role="dialog" aria-labelledby="mdlVer" data-backdrop="static">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<form id="formVer">
				<input type="hidden" name="total" id="total">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Nueva Venta</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="cbo_empleado" class="control-label">Empleado</label>
								<select name="cbo_empleado" id="cbo_empleado" class="form-control" required="" disabled="">
									<option value="">Seleccione</option>
									<?php foreach(obtenerEmpleados() as $empleado): ?>
										<option value="<?= $empleado->idEmpleado; ?>"><?= $empleado->nombre.' '.$empleado->apellido; ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="txt_orden" class="control-label">Orden</label>
								<input type="text" name="txt_orden" id="txt_orden" class="form-control" maxlength="30" disabled="">
							</div>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<label for="cbo_exportador" class="control-label">Exportador</label>
								<select name="cbo_exportador" id="cbo_exportador" class="form-control" required="" disabled="">
									<option value="">Seleccione</option>
									<?php foreach(obtenerExportadores() as $exportador): ?>
										<option value="<?= $exportador->idExportador; ?>"><?= $exportador->razonSocial; ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="cbo_destino" class="control-label">Destino</label>
								<select name="cbo_destino" id="cbo_destino" class="form-control" required="" disabled="">
									<option value="">Seleccione</option>
									<?php foreach(obtenerDestinos() as $destino): ?>
										<option value="<?= $destino->idDestExp; ?>"><?= $destino->destino; ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="col-md-12">
						<table class="table table-hover display responsive no-wrap" width="100%" id="table-articulos">
						    <thead>
						        <tr>
						        	<th width="5%">#</th>
						            <th width="45%">ARTICULO</th>
						            <th width="10%" class="text-right">PRECIO</th>
						            <th width="20%" class="text-right">CANTIDAD</th>
						            <th width="20%" class="text-right">SUBTOTAL</th>
						        </tr>
						    </thead>
						    <tbody id="table-body"></tbody>
						    <tfoot>
						        <tr>
						        	<th colspan="3">TOTAL</th>
						        	<th colspan="2" class="text-right"><span id="detalle-subtotal"></span></th>
						        </tr>
						    </tfoot>
						</table>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="txt_observacion" class="control-label">Observacion</label>
								<input type="text" name="txt_observacion" id="txt_observacion" class="form-control" maxlength="30" disabled="">
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
						<i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp;Guardar
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {

	var table = getTable();

	$('#btn-actualizar').click(function(event) {
		refreshTable();
	});

	$('#cbo_orden').on('change', function(event) {
		var orden = $(this).val();
		var modal = $('#mdlNuevo');
		$.ajax({
            url: 'modulo/orden.php?metodo=obtener',
            type : "POST",
            dataType : 'json',
            data : {
            	id: orden
            }
		})
		.done(function( data, textStatus, jqXHR ) {
			var table = modal.find('.modal-body #table-articulos #table-body');
			var html;
			var total = 0;
			$.each( data.detalle, function( key, val ) {
				var precio = parseFloat(val.precio);
				var cantidad = parseFloat(val.cantidad);
				var st = precio * cantidad;
				html += '<tr>'+
				'<td>'+(key+1)+'</td>'+
				'<td>'+val.nombreArticulo+'</td>'+
				'<td class="text-right">'+val.precio+'</td>'+
				'<td class="text-right">'+val.cantidad+'</td>'+
				'<td class="text-right">'+st.toFixed(2)+'</td>'+
				'</tr>';
				total += st;
			});
			table.html(html);
			modal.find('.modal-body #table-articulos #detalle-subtotal').text(total.toFixed(2));
			modal.find('#total').val(total);
			$('#cargando').hide();
			$('#formulario').show();
		})
		.fail(function( jqXHR, textStatus, errorThrown ) {
			console.log("error");
		});
	});

    $('#formNuevo').submit(function(e) {
    	$('#cargarNuevo').show();
    	$('#errorNuevo').hide();

        e.preventDefault();

        $.ajax({
            url: 'modulo/venta.php?metodo=guardar',
            type : "POST",
            dataType : 'json',
            data : $(this).serialize()
        })
		.done(function( data, textStatus, jqXHR ) {
			window.location.href = 'base.php?modulo=venta';
		})
		.fail(function( jqXHR, textStatus, errorThrown ) {
			console.log(jqXHR, textStatus, errorThrown);
			$('#cargarNuevo').hide();
			$('#errorNuevo').show();
			$('#errorNuevoDetalle').html(jqXHR['responseJSON'].error);
		});
    });

	$('#mdlVer').on('show.bs.modal', function (event) {
		$('#cargando').show();
		$('#formulario').hide();
		$('#cargarEditar').hide();
		$('#errorEditar').hide();
		var button = $(event.relatedTarget);
		var id = button.data('id');
		var modal = $(this);

		$.ajax({
            url: 'modulo/venta.php?metodo=obtener',
            type : "POST",
            dataType : 'json',
            data : {
            	id: id
            }
		})
		.done(function( data, textStatus, jqXHR ) {
			modal.find('.modal-body #cbo_empleado').val(data.cabecera.idEmpleado);
			modal.find('.modal-body #txt_orden').val(data.cabecera.idOrden);
			modal.find('.modal-body #cbo_exportador').val(data.cabecera.idExportador);
			modal.find('.modal-body #cbo_destino').val(data.cabecera.idDestExp);
			modal.find('.modal-body #txt_fecha').val(data.cabecera.fecha);
			modal.find('.modal-body #txt_hora').val(data.cabecera.hora);
			var table = modal.find('.modal-body #table-articulos #table-body');
			var html;
			var total = 0;
			$.each( data.detalle, function( key, val ) {
				var precio = parseFloat(val.precio);
				var cantidad = parseFloat(val.cantidad);
				var st = precio * cantidad;
				html += '<tr>'+
				'<td>'+(key+1)+'</td>'+
				'<td>'+val.nombreArticulo+'</td>'+
				'<td class="text-right">'+val.precio+'</td>'+
				'<td class="text-right">'+val.cantidad+'</td>'+
				'<td class="text-right">'+st.toFixed(2)+'</td>'+
				'</tr>';
				total += st;
			});
			table.html(html);
			modal.find('.modal-body #txt_observacion').val(data.cabecera.observacion);
			modal.find('.modal-body #table-articulos #detalle-subtotal').text(total.toFixed(2));
			$('#cargando').hide();
			$('#formulario').show();
		})
		.fail(function( jqXHR, textStatus, errorThrown ) {
			console.log("error");
		});
	});	

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
				            "url": "modulo/venta.php?metodo=listado",
				            "complete": function () {
				            	$('#table-body').show();
				            	addAEvent();
				            },
				            "error": function (xhr, error, thrown) {
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
								"data": 2, "render": function ( data, type, row ) {
									return data;
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
								"data": 6, "render": function ( data, type, row ) {
									return data;
								}
							},				        				        
							{
								"data": 7, "render": function ( data, type, row ) {
									return data;
								}
							},
							{
								"data": 8, "render": function ( data, type, row ) {
									return data;
								}
							},
							{
								"data": 9, "render": function ( data, type, row ) {
									switch(data) {
									    case 0:
									        return '<span class="badge bg-red"><i class="fa fa-ban" aria-hidden="true"></i></span>';
									        break;
									    case 1:
									        return '<span class="badge bg-green"><i class="fa fa-check" aria-hidden="true"></i></span>';
									        break;
									    case 2:
									        return '<span class="badge bg-yellow"><i class="fa fa-file-o" aria-hidden="true"></i></span>';
									        break;
									}
								}
							},
							{
								"data": null, "render": function ( data ) {
									var html = '<div class="text-right">' +
									'<button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#mdlVer"'+
									'data-id="' + data[1] + '"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;&nbsp;Ver</button>'+
									'&nbsp;&nbsp;';
									var estado = (data[9] != 0) ? '' : 'disabled="true"';
									switch(data[9]) {
									    case 1:
									        html += '<button type="button" class="btn btn-sm btn-danger btnAnular"'+
											'data-id="' + data[0] + '" '+estado+'>'+
											'<i class="fa fa-ban" aria-hidden="true"></i>&nbsp;&nbsp;Anular</button>';
									        break;
									}
									html += '</div>';
									return html;
								},
								"searchable": false,
								"orderable": false
							}
				        ]

				    });
	};

	function addAEvent(){ 
		var btnA = $('.btnAnular');

		btnA.unbind();

		btnA.on('click', function(e) {
			if (confirm('¿ Desea ANULAR la venta ?')) {
				$.ajax({
		            url: 'modulo/venta.php?metodo=anular',
		            type : "POST",
		            dataType : 'json',
		            data : {
		            	id: $(this).attr('data-id')
		            }
				})
				.done(function( data, textStatus, jqXHR ) {
					window.location.href = 'base.php?modulo=venta';
				})
				.fail(function( jqXHR, textStatus, errorThrown ) {
					alert('¡ Error al anular orden !');
				});
			}
		});
	}

});

</script>