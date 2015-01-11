<?php
/*
Plugin Name: DISTRIBUIDORA GC - Download Orders
Plugin URI: http://developercats.com
Description: Plugin for WooCommerce to download the store orders as CSV, XLS or custom TXT format.
Author: Ruy R. Garcia
Version: 0.0.1
Author URI: http://developercats.com
*/

// Add jQuery UI date picker to this page:
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

// Add custom JS script to admin page:
add_action( 'admin_enqueue_scripts', 'add_js_script_init' );
function add_js_script_init() {
	wp_enqueue_script("admin_print_scripts-download-orders", plugins_url( '/js/download-orders.js' , __FILE__ ));
}
// include the txt Perser script;
include dirname(__FILE__).'/txtParser.php';
// include the download script:
include dirname(__FILE__).'/downloader.php';
// Attach tne download function to the WP script flow in order to be triggered:
add_action('admin_menu', 'attachDownloadTrigger');
function attachDownloadTrigger() {
	if( isset($_GET['download']) ) {
		if ( isset($_GET['customer_id']) ) {
			$customer = $_GET['customer_id'];
			downloadTest( array('meta_key' => '_customer_user', 'meta_value' => $customer) );
		} else if ( isset($_GET['order_id']) ) {
			$order = $_GET['order_id'];
			downloadTest( array('order_id' => $order) );
			// downloadTest( array('p' => $order) );
		} else {
			downloadTest();
		}
	}
}

// Add this admin page to the WC admin menu:
add_action('admin_menu', 'add_to_admin_menu');
function add_to_admin_menu() {
	add_submenu_page( 'woocommerce', 'DISTRIBUIDORA GC Descarga de Pedidos', 'DISTRIBUIDORA GC Descarga de Pedidos', 'manage_options', 'woocommerce-download-orders', 'display_page'); 
}

function display_page() {
$search_params = getParamsOptions();
?>
<div class="wrap">
	<h1>Descarga de Pedidos</h1>
	<p>En esta sección podrá buscar pedidos para descargar.</p>
	<p>Seleccione abajo los parámetros de búsqueda y luego de clíck en el botón "Buscar Pedidos".</p>
		<h3>Parametros de busqueda:</h3>
		<table class="widefat">
			<thead>
				<tr>
					<th>Codigo de cliente:</th>
					<th>Fecha del Pedido:</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<form action="" method="post" id="search_form">
					<td>
						<input type="text" name="customer_id_from" id="customer_id_from" placeholder="del" size="4" value="<?php echo $search_params['customer_id_from']; ?>" class="search_form_class">
			 			<input type="text" name="customer_id_to" id="customer_id_to" placeholder="al" size="4" value="<?php echo $search_params['customer_id_to']; ?>" class="search_form_class">
			 		</td>
					<td>
						<input type="text" name="date_from" id="date_from" placeholder="del" size="8" value="<?php echo $search_params['date_from']; ?>" class="search_form_class">
						<input type="text" name="date_to" id="date_to" placeholder="al" size="8" value="<?php echo $search_params['date_to']; ?>" class="search_form_class">
					<td></td>
					<td>
						<input type="submit" name="search_orders" id="search_orders" value="Buscar Pedidos" class="button-primary">
						<input type="button" value="Borrar Campos" id="reset_form">
					</td>
					</form>
				</tr>
			</tbody>
		</table>
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
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th>Descargar Todo</th>
				<th>
					<button href="" id="download-<?php echo $order->id ?>">CSV</button> 
					<button href="" id="download-<?php echo $order->id ?>">TXT</button>
				</th>
			</tr>
		</tfoot>
		<tbody>
<?php
	$orders = searchOrders();
	foreach ($orders as $order) {
?>
			<tr>
				<td><?php echo $order->id; ?></td>
				<td><?php echo $order->customer->user_login; ?></td>
				<td><?php echo $order->customer->display_name; ?></td>
				<?php 
				setlocale(LC_TIME, 'es_ES.UTF-8');
				// strftime("%A, %d de %B de %Y", $miFecha);
				// date('d / m / Y', strtotime($order->order_date) );
				?>
				<td><?php echo strftime("%d/%B/%Y", strtotime($order->order_date)); ?></td>
				<td>
					<button href="" id="details-<?php echo $order->id ?>">VER</button>
				</td>
				<td align="right">$ <?php echo number_format($order->total,2); ?></td>
				<td>
					<button href="" id="download-<?php echo $order->id ?>">CSV</button> 
					<button href="" id="download-<?php echo $order->id ?>">TXT</button>
				</td>
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