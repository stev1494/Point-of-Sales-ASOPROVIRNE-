<?php
if (!isset($_SESSION['conectado'])) header('location: ./..');
?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Articulo</h1>
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
					            <th data-priority="3">PRECIO</th>
					            <th data-priority="4">CANTIDAD</th>
					            <th data-priority="5" class="text-right"></th>
					        </tr>
					    </thead>
					    <tbody id="table-body"></tbody>
					    <tfoot>
					        <tr>
					            <th>ID</th>
					            <th>NOMBRE</th>
					            <th>PRECIO</th>
					            <th>CANTIDAD</th>
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
					<h4 class="modal-title">Nuevo Articulo</h4>
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
								<label for="txt_precio" class="control-label">Precio</label>
								<input type="text" name="txt_precio" id="txt_precio" class="form-control" required="" min="0">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="txt_cantidad" class="control-label">Cantidad</label>
								<input type="number" name="txt_cantidad" id="txt_cantidad" class="form-control" required="" min="0">
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
					<h4 class="modal-title">Editar Articulo</h4>
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
								<label for="txt_precio" class="control-label">Precio</label>
								<input type="text" name="txt_precio" id="txt_precio" class="form-control" required="" min="0">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="txt_cantidad" class="control-label">Cantidad</label>
								<input type="number" name="txt_cantidad" id="txt_cantidad" class="form-control" required="" min="0">
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

<script>
$(document).ready(function() {

	var table = getTable();

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
		modal.find('.modal-body #txt_nombre').focus();
	});

    $('#formNuevo').submit(function(e) {
    	$('#cargarNuevo').show();
    	$('#errorNuevo').hide();

        e.preventDefault();

        $.ajax({
            url: 'modulo/articulo.php?metodo=guardar',
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
            url: 'modulo/articulo.php?metodo=obtener',
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
			modal.find('.modal-body #txt_nombre').val(data.nombreArticulo);
			modal.find('.modal-body #txt_precio').val(data.precio);
			modal.find('.modal-body #txt_cantidad').val(data.cantidad);
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
            url: 'modulo/articulo.php?metodo=editar',
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
				            "url": "modulo/articulo.php?metodo=listado",
				            "complete": function () {
				            	$('#table-body').show();
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
									return 'S/.'+data.toFixed(2);
								}
							},
							{
								"data": 3, "render": function ( data, type, row ) {
									return data.toFixed(2);
								}
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

});

</script>