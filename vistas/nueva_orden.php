<?php
if (!isset($_SESSION['conectado'])) header('location: ./..');

function obtenerExportadores() {
    global $con;

    $stmt = $con->prepare("SELECT idExportador, razonSocial FROM exportador");
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
}
?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Nueva Orden</h1>
</section>

<!-- Main content -->
<section class="content">

	<div class="row">

		<form id="formNuevo">

		<div class="col-md-12">
			<!-- Default box -->
			<div class="box box-primary" id="principal">
				<div class="box-header with-border">
					<div class="pull-left">
						<a href="base.php?modulo=orden" class="btn btn-sm btn-default">
							<i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Regresar
						</a>
						<a href="base.php?modulo=nueva_orden" class="btn btn-sm btn-default">
							<i class="fa fa-refresh"></i>&nbsp;&nbsp;Nuevo
						</a>
						<button type="submit" class="btn btn-sm btn-success" id="btn-nuevo">
							<i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Guardar
						</button>
					</div>
				</div>
			</div>
			<!-- /.box -->
		</div>

		<div class="col-md-6">
			<!-- Default box -->
			<div class="box box-primary" id="principal">
				<div class="box-header with-border">
					<h3 class="box-title">Detalle</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse">
							<i class="fa fa-minus"></i>
						</button>
					</div>
				</div>
				<div class="box-body">
						<!--
						<div class="col-md-12">
							<div class="form-group">
								<label for="txt_fecha" class="control-label">Fecha</label>
								<input type="text" class="form-control" name="txt_fecha" id="txt_fecha" disabled="">
							</div>
						</div>
						-->
							<table class="table table-hover display responsive no-wrap" width="100%" id="table">
							    <thead>
							        <tr>
							            <th width="40%">ARTICULO</th>
							            <th width="15%" class="text-right">PRECIO</th>
							            <th width="20%" class="text-right">CANTIDAD</th>
							            <th width="20%" class="text-right">SUBTOTAL</th>
							            <th width="5%" class="text-right"></th>
							        </tr>
							    </thead>
							    <tbody id="table-body">
							    </tbody>
							    <tfoot>
							        <tr>
							        	<th colspan="3">SUBTOTAL</th>
							        	<th class="text-right" id="detalle-subtotal">S/.0.00</th>
							        </tr>
							    </tfoot>
							</table>
				</div>
			</div>
			<!-- /.box -->
		</div>

		</form>

		<div class="col-md-6">
			<!-- Default box -->
			<div class="box box-primary" id="principal">
				<div class="box-header with-border">
					<h3 class="box-title">Articulos</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse">
							<i class="fa fa-minus"></i>
						</button>
					</div>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="fp_buscar" class="control-label">Buscar</label>
								<div class="input-group">
									<input type="text" class="form-control" name="fp_buscar" id="fp_buscar" placeholder="Buscar....">
									<span class="input-group-btn">
										<button class="btn btn-danger" type="button" id="fp_btn-limpiar-buscar">
											<i class="fa fa-times" aria-hidden="true"></i>
										</button>
									</span>
								</div>
							</div>
						</div>
					</div>
					<table class="table table-hover display responsive no-wrap" width="100%" id="table-buscar">
					    <thead>
					        <tr>
					            <th data-priority="1">ID</th>
					            <th data-priority="3">NOMBRE</th>
					            <th data-priority="4">PRECIO</th>
					            <th data-priority="5">CANTIDAD</th>
					            <th data-priority="2" class="text-right"></th>
					        </tr>
					    </thead>
					    <tbody id="table-body"></tbody>
					    <tfoot>
					        <tr>
					            <th>ID</th>
					            <th>NOMBRE</th>
					            <th>PRECIO</th>
					            <th>CANTIDAD</th>
					            <th class="text-right"><i class="fa fa-wrench"></i></th>
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


<script type="text/javascript">
$(document).ready(function() {

	var fecha = new Date();
	$('#txt_fecha').val(fecha.getFullYear()+' / '+(fecha.getMonth()+1)+' / '+fecha.getDate());
	
	var table_productos_buscar = getTableProductos();

	$('#cbo_exportador').on('change', function(event) {
		$('#formNuevo #cbo_exportador').val($(this).val());
	});

    $('#formNuevo').submit(function(e) {
    	e.preventDefault();

    	if ($('#table #table-body').find("tr").length > 0) {

	        $.ajax({
	            url: 'modulo/orden.php?metodo=guardar',
	            type : "POST",
	            dataType : 'json',
	            data : $(this).serialize()
	        })
			.done(function( data, textStatus, jqXHR ) {
				window.location.href = 'base.php?modulo=orden';
			})
			.fail(function( jqXHR, textStatus, errorThrown ) {
				console.log(jqXHR, textStatus, errorThrown);
				var json = jqXHR['responseJSON'];
				if($.isEmptyObject(json) == false){
					alert(jqXHR['responseJSON'].error);
				}
			});

    	} else {
		    alert("¡ No ingreso articulos !");
		    return false;
		}


    });

	addAEvent();

	function addAEvent(){ 
		var icant = $('.input_cantidad');
		var ibtne = $('.btn-eliminar-fila');
		var ibtns = $('.btn-seleccionar');

		icant.unbind();
		ibtne.unbind();
		ibtns.unbind();

		icant.numeric(false);

		icant.on('keyup change paste', function(e) {
			var input = $(this);
			calcularSubTotal(input);
		});

		ibtne.on('click', function(e) {
			var btn = $(this);
			eliminar_fila(btn);
		});

		ibtns.on('click', function(e) {
			var btn = $(this);
			var fila_buscar_producto = btn.parent().parent().parent().find('td');
			agregar_fila(
				fila_buscar_producto.eq(0).text(),
				fila_buscar_producto.eq(1).text(), 
				fila_buscar_producto.eq(2).text(), 
				fila_buscar_producto.eq(3).text()
			);
		});		
	}

	function calcularSubTotal(input)
	{
		var precio = parseFloat(input.parent().parent().find('td').eq(1).text());

		var cant = parseFloat(input.val());
		var sta = precio * cant;
		input.parent().parent().find('td').eq(3).text(sta.toFixed(2));
		var st = 0;
		$('#table #table-body').find("tr").each(function() {
			st += parseFloat($(this).find('td').eq(3).text());
		});
		$('#detalle-subtotal').text(st.toFixed(2));
	}

	function eliminar_fila(btn)
	{
	    if (confirm("¿ Desea eliminar la fila seleccionada ?")) {
    		btn.parent().parent().remove();
			addAEvent();
			calcularSubTotal(btn.parent().parent().find('td').eq(2).find('#cantidades'));
	    }
	}

	$('#fp_buscar').on( 'keyup', function () {
		table_productos_buscar.search( this.value ).draw();
	});

	$('#fp_btn-limpiar-buscar').click(function(event) {
		var buscar = $('#fp_buscar');
		buscar.val('');
		table_productos_buscar.search(buscar.val()).draw();
		buscar.focus();
	});	

    function refreshTableProductos() {
		table_productos_buscar.destroy();
		$('#table-buscar #table-body').hide();
		table_productos_buscar = getTableProductos();
    };
	
	function getTableProductos() {
	    return  $('#table-buscar')
				    .DataTable({
				    	"dom": 'tpr',
				    	"lengthChange": false,
				    	"language":{
				    		"processing": '<i class="fa fa-spinner fa-spin fa-pulse fa-3x fa-fw" style="color: #3c8dbc;"></i>',
				    		"url": "assets/datatables/i18n/spanish.lang"
				    	},
						"processing": true,
						"serverSide": true,
				        "destroy": true,
				        "responsive": true,
						"select": {
						    style: 'single'
						},
				        "keys": {
							keys: [ 13 /* ENTER */, 38 /* UP */, 40 /* DOWN */ ]
						},
				        "ajax":{
				            "method": "POST",
				            "url": "modulo/articulo.php?metodo=listado",
				            "complete": function () {
				            	$('#table-buscar #table-body').show();
				            	addAEvent();
				            },
				            "error": function (xhr, error, thrown) {
				            	console.log(xhr, error, thrown);
				            	//$('#principal').hide();
				            	if (xhr['status'] == '404') {
				            		//$('#recursoNoEncontrado').show();
				            	} else if (xhr['status'] == '500') {
				            		//$('#errorServidor').show();
				            	}
				            }
				        },
				        "order": [],
				        "columns":[
				        {
				        	"data": 0, "render": function ( data, type, row ) {
				        		return data;
				        	},
				        	"orderable": false
				        },
				        {
				        	"data": 1, "render": function ( data, type, row ) {
				        		return data;
				        	},
				        	"orderable": false
				        },				        				        
				        {
				        	"data": 2, "render": function ( data, type, row ) {
				        		return data;
				        	},
				        	"searchable": false,
				        	"orderable": false
				        },				        				        
				        {
				        	"data": 3, "render": function ( data, type, row ) {
				        		return data;
				        	},
				        	"searchable": false,
				        	"orderable": false
				        },
				        {
				        	"data": null, "render": function ( data ) {
				        		var html = '<div class="text-right">' +
				        		'<button type="button" class="btn btn-sm btn-info btn-seleccionar" data-id="'+ 
				        		data[6] + '"><i class="fa fa-check" aria-hidden="true"></i></button>' +
				        		'</div>';
				        		return html;
				        	},
				        	"searchable": false,
				        	"orderable": false
				        }
				        ]

				    });
	};

    // Handle event when cell gains focus
    $('#table-buscar').on('key-focus.dt', function(e, datatable, cell){
        // Select highlighted row
        table_productos_buscar.row(cell.index().row).select();
    });
    
    // Handle click on table cell
    $('#table-buscar').on('click', 'tbody td', function(e){
        e.stopPropagation();
        
        // Get index of the clicked row
        var rowIdx = table_productos_buscar.cell(this).index().row;
        
        // Select row
        table_productos_buscar.row(rowIdx).select();
    });
    
    // Handle key event that hasn't been handled by KeyTable
    $('#table-buscar').on('key.dt', function(e, datatable, key, cell, originalEvent){
        // If ENTER key is pressed
        if(key === 13){
            // Get highlighted row data
            var data = table_productos_buscar.row(cell.index().row).data();
			agregar_fila(data[0], data[1], data[2], data[3]);

        }
    });    

	function agregar_fila(id, nombre, precio, st)
	{
        if ($('#table #table-body').find("tr").length == 0) {
        	fila(id, nombre, precio, st);
        	calcularSubTotal($(this).find('td').eq(2).find('#cantidades'));
        } else {
        	var agregar = true;
	        $('#table #table-body').find("tr").each(function() {
	        	if (parseInt($(this).attr('data-id')) == parseInt(id)) {
	        		agregar = false;
	        		var cant = parseInt($(this).find('td').eq(2).find('#cantidades').val()) + 1;
	        		$(this).find('td').find('#cantidades').val(cant);
	        		calcularSubTotal($(this).find('td').eq(2).find('#cantidades'));
	        		return false;
	        	}
	        });
	        if (agregar) {
	        	fila(id, nombre, precio, st);
	        	calcularSubTotal($('#table #table-body').find('tr').last().find('#cantidades'));
	        }
        }
	}

	function fila(id, nombre, precio, st)
	{
		var html_fila = '<tr data-id="'+parseInt(id)+'">'+
						'<td>'+
						'<input type="hidden" name="articulos[]" id="articulos" value="'+id+'">'+
						'<span name="nombre-articulo" id="nombre-articulo">'+nombre+'</span>'+
						'</td>'+
						'<td class="text-right">'+
						'<input type="hidden" name="precios[]" id="precios" value="'+precio+'">'+
						'<span name="precio-articulo" id="precio-articulo">'+parseFloat(precio).toFixed(2)+'</span>'+
						'</td>'+
						'<td class="text-right">'+
						'<input type="number" class="form-control input_cantidad" name="cantidades[]" id="cantidades" min="0" value="1" style="text-align: right;">'+
						'</td>'+
						'<td class="text-right">'+
						'<span name="subtotal-articulo" id="subtotal-articulo">'+parseFloat(st).toFixed(2)+'</span>'+
						'</td>'+
						'<td class="text-right">'+
						'<a href="#" class="btn btn-small btn-danger btn-eliminar-fila" id="btn-eliminar-fila"><i class="fa fa-trash" aria-hidden="true"></i></button>'+
						'</td>'+
						'</tr>';
		var body = $('#table #table-body');
		body.append(html_fila);
		addAEvent();
	}

});
</script>
