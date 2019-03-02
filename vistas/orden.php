<?php
if (!isset($_SESSION['conectado'])) header('location: ./..');
?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Orden</h1>
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
						<a href="base.php?modulo=nueva_orden" class="btn btn-sm btn-default">
							<i class="fa fa-file"></i>
							&nbsp;&nbsp;Nuevo
						</a>
					</div>
				</div>
				<div class="box-body">
					<table class="table table-hover display responsive no-wrap" width="100%" id="table">
					    <thead>
					        <tr>
					            <th data-priority="1">ID</th>
					            <th data-priority="2">EMPLEADO</th>
					            <th data-priority="5">FECHA</th>
					            <th data-priority="6">HORA</th>
					            <th data-priority="20">OBSERVACION</th>
					            <th data-priority="7">ESTADO</th>
					            <th data-priority="9" class="text-right"></th>
					        </tr>
					    </thead>
					    <tbody id="table-body"></tbody>
					    <tfoot>
					        <tr>
					            <th>ID</th>
					            <th>EMPLEADO</th>
					            <th>FECHA</th>
					            <th>HORA</th>
					            <th>OBSERVACION</th>
					            <th>ESTADO</th>
					            <th class="text-right"></th>
					        </tr>
					    </tfoot>
					</table>
				</div>
				<div class="box-footer">
					<div class="pull right">
						<div class="col-md-2 col-md-offset-6">
							<span class="badge bg-green"><i class="fa fa-check" aria-hidden="true"></i></span>&nbsp;&nbsp;Confirmada
						</div>
						<div class="col-md-2">
							<span class="badge bg-yellow"><i class="fa fa-file-o" aria-hidden="true"></i></span>&nbsp;&nbsp;Pendiente
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

<!-- modal ver -->
<div class="modal fade" id="mdlVer" tabindex="-1" role="dialog" aria-labelledby="mdlVer" data-backdrop="static">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Ver Orden</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="txt_id" class="control-label">ID</label>
								<input type="text" name="txt_id" id="txt_id" class="form-control" required="" disabled="">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="txt_fecha" class="control-label">Fecha</label>
								<input type="text" name="txt_fecha" id="txt_fecha" class="form-control" required="" disabled="">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="txt_hora" class="control-label">Hora</label>
								<input type="text" name="txt_hora" id="txt_hora" class="form-control" required="" disabled="">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label for="txt_empleado" class="control-label">Empleado</label>
								<input type="text" name="txt_empleado" id="txt_empleado" class="form-control" required="" min="0" disabled="">
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
						        	<th colspan="3">SUBTOTAL</th>
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
				</div>
		</div>
	</div>
</div>



<script>
$(document).ready(function() {

	var table = getTable();

	$('#btn-actualizar').click(function(event) {
		refreshTable();
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
            url: 'modulo/orden.php?metodo=obtener',
            type : "POST",
            dataType : 'json',
            data : {
            	id: id
            }
		})
		.done(function( data, textStatus, jqXHR ) {
			modal.find('.modal-body #id').val(id);
			modal.find('.modal-body #txt_id').val(data.cabecera.idOrden);
			modal.find('.modal-body #txt_fecha').val(data.cabecera.fecha);
			modal.find('.modal-body #txt_hora').val(data.cabecera.hora);
			modal.find('.modal-body #txt_empleado').val(data.cabecera.empleado);
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
				            "url": "modulo/orden.php?metodo=listado",
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
									'data-id="' + data[0] + '"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;&nbsp;Ver</button>'+
									'&nbsp;&nbsp;';
									var estado = (data[5] != 0) ? '' : 'disabled="true"';
									switch(data[5]) {
									    case 1:
									        html += '<button type="button" class="btn btn-sm btn-warning btnPendiente"'+
											'data-id="' + data[0] + '" '+estado+'>'+
											'<i class="fa fa-check" aria-hidden="true"></i>&nbsp;&nbsp;Pendiente</button>';
									        break;
									    case 2:
									        html += '<button type="button" class="btn btn-sm btn-success btnConfirmar"'+
											'data-id="' + data[0] + '" '+estado+'>'+
											'<i class="fa fa-check" aria-hidden="true"></i>&nbsp;&nbsp;Confirmar</button>'+
											'&nbsp;&nbsp;'+
											'<button type="button" class="btn btn-sm btn-danger btnAnular"'+
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
		var btnC = $('.btnConfirmar');
		var btnP = $('.btnPendiente');
		var btnA = $('.btnAnular');

		btnC.unbind();
		btnP.unbind();
		btnA.unbind();

		btnC.on('click', function(e) {
			if (confirm('¿ Desea cambiar el estado a CONFIRMADO ?')) {
				$.ajax({
		            url: 'modulo/orden.php?metodo=confirmar',
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
					alert('¡ Error cambiar el estado !');
				});
			}
		});

		btnP.on('click', function(e) {
			if (confirm('¿ Desea cambiar el estado a PENDIENTE ?')) {
				$.ajax({
		            url: 'modulo/orden.php?metodo=pendiente',
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
					alert('¡ Error al cambiar el estado !');
				});
			}
		});

		btnA.on('click', function(e) {
			if (confirm('¿ Desea anular la orden ?')) {
				$.ajax({
		            url: 'modulo/orden.php?metodo=anular',
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
					alert('¡ Error al anular orden !');
				});
			}
		});
	}

});

</script>