<?php
if (!isset($_SESSION['conectado'])) header('location: ./..');
?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Reporte de ventas</h1>
</section>

<!-- Main content -->
<section class="content">

	<div class="row">
		<div class="col-md-12">
			<!-- Default box -->
			<div class="box box-primary" id="principal">
				<div class="box-header with-border">
					<h3 class="box-title">Listado</h3>
				</div>
				<div class="box-body">
					<table class="table table-hover display responsive no-wrap" width="100%" id="table">
					    <thead>
					        <tr>
					            <th data-priority="1">ID VENTA</th>
					            <th data-priority="2">ID EXPORTADOR</th>
					            <th data-priority="3">FECHA</th>
					            <th data-priority="4">TOTAL</th>
					        </tr>
					    </thead>
					    <tbody id="table-body"></tbody>
					    <tfoot>
					        <tr>
					            <th>ID VENTA</th>
					            <th>ID EXPORTADOR </th>
					            <th>FECHA</th>
					            <th>TOTAL</th>
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

<script>
$(document).ready(function() {

	var table = getTable();

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
				            "url": "modulo/reportedeventas.php?metodo=listado",
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
								"data": 3, "render": function ( data, type, row ) {
									return data;
								}
							}
				        ]
				    });
	};


});

</script>