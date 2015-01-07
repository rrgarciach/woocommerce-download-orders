<?php
/*
Plugin Name: DISTRIBUIDORA GC - Download Orders
Plugin URI: http://developercats.com
Description: Plugin for WooCommerce to download the store orders as CSV, XLS or custom TXT format.
Author: Ruy R. Garcia
Version: 0.0.1
Author URI: http://developercats.com
*/

// inclure the download script:
include dirname(__FILE__).'/downloader.php';
// Attach tne download function to the WP script flow in order to be triggered:
add_action('admin_menu', 'attachDownloadTrigger');
function attachDownloadTrigger() {
	if( isset($_GET['download']) ) {
		downloadTest();
	}
}

// Add this admin page to the WC admin menu:
add_action('admin_menu', 'add_to_admin_menu');
function add_to_admin_menu() {
	add_submenu_page( 'woocommerce', 'DISTRIBUIDORA GC Download Orders', 'DISTRIBUIDORA GC Download Orders', 'manage_options', 'woocommerce-download-orders', 'display_page'); 
}

function display_page() {
?>
<div class="wrap">
	<h4>Descarga de Ordenes</h4>
	<h3>En esta seccion podra buscar ordenes para descargar.</h3>
	<p>Seleccione abajo los parametros de busqueda y luego de click en el boton "Buscar ordenes"</p>
	<form action="" method="post">
		<fieldset>
			<legend>Parametros de busqueda:</legend>
			Fecha:<br/> del <input type="text" name="datefrom" id="datefrom" size="8"> al <input type="text" name="dateto" id="dateto" size="8"><br/>
			Codigo de cliente:<br/> <input type="text" name="customer" size="6"><br/>
		</fieldset>
		<input type="submit" name="search_orders" id="search_orders" value="Buscar ordenes" class="button-primary">
	</form>
	<table class="widefat">
		<thead>
			<tr>
				<th>ID Pedido:</th>
				<th>ID Cliente:</th>
				<th>Nombre Cliente:</th>
				<th>Fecha</th>
				<th>Detalle</th>
				<th>Total</th>
				<th>Descarga</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>ID Pedido:</th>
				<th>ID Cliente:</th>
				<th>Nombre Cliente:</th>
				<th>Fecha</th>
				<th>Detalle</th>
				<th>Total</th>
				<th>Descarga</th>
			</tr>
		</tfoot>
		<tbody>
<?php

	$orders = searchOrders();
	foreach ($orders as $order) {
?>
			<tr>
				<td><?php echo $order->id ?></td>
				<td><?php echo $order->customer->user_login ?></td>
				<td><?php echo $order->customer->display_name ?></td>
				<td><?php echo $order->order_date ?></td>
				<td></td>
				<td><?php echo number_format($order->total,2) ?></td>
				<td><a href="" id="download-<?php echo $order->id ?>">DESCARGAR</a></td>
				<script type="text/javascript">
					$('#download-<?php echo $order->id ?>').on("click", function(e){
					    e.preventDefault();
					    alert('clicked');
					    // $('#search_orders').attr('action', "?download=1").submit();
					    $('#search_orders').submit();
					});
				</script>
			</tr>
<?php
		}

?>
		</tbody>
	</table>
</div>
<?php

}
?>