<?php
if (!isset($_SESSION['conectado'])) header('location: ./..');
?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Reporte</h1>
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
					            <th data-priority="1">ID</th>
					            <th data-priority="2">NOMBRE</th>
					            <th data-priority="3">APELLIDO</th>
					            <th data-priority="4">CEDULA</th>
					            <th data-priority="5">FEC.NACIMIENTO</th>
					            <th data-priority="6">EST.CIVIL</th>
					            <th data-priority="7">GENERO</th>
					            <th data-priority="8">CARGO</th>
					            <th data-priority="9">SUELDO</th>
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
					            <th>SUELDO</th>
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
				            "url": "modulo/reporte.php?metodo=listado",
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
							}
				        ]
				    });
	};


});

</script>