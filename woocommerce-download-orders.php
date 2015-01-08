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
	<h1>Descarga de Pedidos</h1>
	<p>En esta sección podrá buscar pedidos para descargar.</p>
	<p>Seleccione abajo los parámetros de búsqueda y luego de clíck en el botón "Buscar Pedidos".</p>
	<form action="" method="post">
		<h3>Parametros de busqueda:</h3>
		<table class="widefat">
			<thead>
				<tr>
					<th>Fecha del Pedido:</th>
					<th>Codigo de cliente:</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<input type="text" name="date_from" id="date_from" placeholder="del" size="8">
						<input type="text" name="date_to" id="date_to" placeholder="al" size="8">
					<td>
						<input type="text" name="customer_id_from" id="customer_id_from" placeholder="del" size="4">
			 			<input type="text" name="customer_id_to" id="customer_id_to" placeholder="al" size="4">
			 		</td>
					<td></td>
					<td>
						<input type="submit" name="search_orders" id="search_orders" value="Buscar Pedidos" class="button-primary">
						<input type="reset" name="reset_button" id="reset_button" value="Limpiar Campos" class="button-secundary">
					</td>
				</tr>
			</tbody>
		</table>
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
				<td align="right">$ <?php echo number_format($order->total,2) ?></td>
				<td><button href="" id="download-<?php echo $order->id ?>">CSV</button> <button href="" id="download-<?php echo $order->id ?>">TXT</button></td>
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