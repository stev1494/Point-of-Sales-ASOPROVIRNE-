<?php
if (!isset($_SESSION['conectado'])) header('location: ./..');

function obtenerProductores() {
    global $con;

    $stmt = $con->prepare("SELECT idProductor, nombre, apellido FROM productor");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function obtenerArticulos() {
    global $con;

    $stmt = $con->prepare("SELECT idArt, nombreArticulo, precio FROM articulo");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}
?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Compra</h1>
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
					            <th data-priority="2">EMPLEADO</th>
					            <th data-priority="3">PRODUCTOR</th>
					            <th data-priority="4">FECHA</th>
					            <th data-priority="40">HORA</th>
					            <th data-priority="30">ARTICULO</th>
					            <th data-priority="3">PRECIO</th>
					            <th data-priority="31">CANTIDAD</th>
					            <th data-priority="6">TOTAL</th>
					            <th data-priority="50">OBSERVACION</th>
					            <th data-priority="5">ESTADO</th>
					            <th data-priority="7" class="text-right"></th>
					        </tr>
					    </thead>
					    <tbody id="table-body"></tbody>
					    <tfoot>
					        <tr>
					            <th>ID</th>
					            <th>EMPLEADO</th>
					            <th>PRODUCTOR</th>
					            <th>FECHA</th>
					            <th>HORA</th>
					            <th>ARTICULO</th>
					            <th>PRECIO</th>
					            <th>CANTIDAD</th>
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
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Nueva Compra</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="cbo_productor" class="control-label">Productor</label>
								<select name="cbo_productor" id="cbo_productor" class="form-control" required="">
									<option value="">Seleccione</option>
									<?php foreach(obtenerProductores() as $productor): ?>
										<option value="<?= $productor->idProductor; ?>"><?= $productor->nombre.' '.$productor->apellido; ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="cbo_articulo" class="control-label">Articulo</label>
								<select name="cbo_articulo" id="cbo_articulo" class="form-control" required="">
									<option value="">Seleccione</option>
									<?php foreach(obtenerArticulos() as $articulo): ?>
										<option value="<?= $articulo->idArt; ?>"><?= $articulo->nombreArticulo; ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="txt_precio" class="control-label">Precio</label>
								<input type="text" name="txt_precio" id="txt_precio" class="form-control" required="" min="0">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="txt_cantidad" class="control-label">Cantidad</label>
								<input type="number" name="txt_cantidad" id="txt_cantidad" class="form-control" required="" min="0">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="txt_total" class="control-label">Total</label>
								<input type="number" name="txt_total" id="txt_total" class="form-control" required="" disabled="">
							</div>
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
						<i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;&nbsp;Guardar
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {

	var table = getTable();

	$('#mdlNuevo #txt_precio, #mdlNuevo #txt_cantidad').on('keyup', function(event) {
		var precio = $('#mdlNuevo #txt_precio').val();
		var cantidad = $('#mdlNuevo #txt_cantidad').val();
		var total = parseFloat(precio) * parseFloat(cantidad);
		$('#mdlNuevo #txt_total').val(total.toFixed(2));
	});

	$('#mdlEditar #txt_precio, #mdlEditar #txt_cantidad').on('keyup', function(event) {
		var precio = $('#mdlEditar #txt_precio').val();
		var cantidad = $('#mdlEditar #txt_cantidad').val();
		var total = parseFloat(precio) * parseFloat(cantidad);
		$('#mdlEditar #txt_total').val(total.toFixed(2));
	});

	$('#btn-actualizar').click(function(event) {
		refreshTable();
	});

	$('#mdlNuevo').on('show.bs.modal', function (event) {
		$("#formNuevo")[0].reset();
		var modal = $(this);
		modal.find('.modal-body #cargarNuevo').hide();
		modal.find('.modal-body #errorNuevo').hide();
	});

	$('#mdlNuevo').on('shown.bs.modal', function (event) {
		var modal = $(this);
		modal.find('.modal-body #cbo_productor').focus();
	});

    $('#formNuevo').submit(function(e) {
    	$('#cargarNuevo').show();
    	$('#errorNuevo').hide();

        e.preventDefault();
        $('#mdlNuevo #txt_total').prop('disabled', false);

        $.ajax({
            url: 'modulo/compra.php?metodo=guardar',
            type : "POST",
            dataType : 'json',
            data : $(this).serialize()
        })
		.done(function( data, textStatus, jqXHR ) {
			refreshTable();
			$('#mdlNuevo').modal('hide');
		})
		.fail(function( jqXHR, textStatus, errorThrown ) {
			console.log(jqXHR, textStatus, errorThrown);
			$('#cargarNuevo').hide();
			$('#errorNuevo').show();
			$('#errorNuevoDetalle').html(jqXHR['responseJSON'].error);
		});
		$('#mdlNuevo #txt_total').prop('disabled', true);
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
            url: 'modulo/compra.php?metodo=obtener',
            type : "POST",
            dataType : 'json',
            data : {
            	id: id
            }
		})
		.done(function( data, textStatus, jqXHR ) {
			console.log(data);
			console.log("done");
			modal.find('.modal-body #id').val(id);
			modal.find('.modal-body #cbo_productor').val(data.idProductor);
			modal.find('.modal-body #cbo_articulo').val(data.idArt);
			modal.find('.modal-body #txt_precio').val(data.precio);
			modal.find('.modal-body #txt_cantidad').val(data.cantidad);
			modal.find('.modal-body #txt_total').val(data.total);
			modal.find('.modal-body #txt_observacion').val(data.observacion);
			modal.find('.modal-body #txt_cantidad').val(data.cantidad);
			$('#cargando').hide();
			$('#formulario').show();
			modal.find('.modal-body #cbo_productor').focus();
		})
		.fail(function( jqXHR, textStatus, errorThrown ) {
			console.log("error");
		});
	});

    $('#formEditar').submit(function(e) {
    	$('#cargarEditar').show();
    	$('#errorEditar').hide();

        e.preventDefault();
        $('#mdl #txt_total').prop('disabled', false);

        $.ajax({
            url: 'modulo/compra.php?metodo=editar',
            type : "POST",
            dataType : 'json',
            data : $(this).serialize()
        })
		.done(function( data, textStatus, jqXHR ) {
			refreshTable();
			$('#mdlAnular').modal('hide');
		})
		.fail(function( jqXHR, textStatus, errorThrown ) {
			console.log(jqXHR, textStatus, errorThrown);
			$('#cargarEditar').hide();
			$('#errorEditar').show();
			$('#errorEditarDetalle').html(jqXHR['responseJSON'].error);
		});
		$('#mdlAnular #txt_total').prop('disabled', true);
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
				            "url": "modulo/compra.php?metodo=listado",
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
									return 'S/.'+data.toFixed(2);
								}
							},				        				        
							{
								"data": 7, "render": function ( data, type, row ) {
									return data.toFixed(2);
								}
							},
							{
								"data": 8, "render": function ( data, type, row ) {
									return 'S/.'+data.toFixed(2);
								}
							},
							{
								"data": 9, "render": function ( data, type, row ) {
									return data;
								}
							},
							{
								"data": 10, "render": function ( data, type, row ) {
									if (data > 0) {
										return '<span class="badge bg-green"><i class="fa fa-check" aria-hidden="true"></i></span>';
									} else {
										return '<span class="badge bg-red"><i class="fa fa-ban" aria-hidden="true"></i></span>';
									}
								}
							},
							{
								"data": null, "render": function ( data ) {
									var estado = (data[10] > 0) ? '' : 'disabled="true"';
									var html = '<div class="text-right">';
									switch(data[10]) {
									    case 1:
									        html += '<button type="button" class="btn btn-sm btn-danger btnAnular"'+
											'data-id="' + data[0] + '" >'+
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
			if (confirm('¿ Desea ANULAR la compra ?')) {
				$.ajax({
		            url: 'modulo/compra.php?metodo=anular',
		            type : "POST",
		            dataType : 'json',
		            data : {
		            	id: $(this).attr('data-id')
		            }
				})
				.done(function( data, textStatus, jqXHR ) {
					refreshTable();
				})
				.fail(function( jqXHR, textStatus, errorThrown ) {
					alert('¡ Error al anular compra !');
				});
			}
		});
	}

});

</script>