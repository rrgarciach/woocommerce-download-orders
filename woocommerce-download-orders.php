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
	wp_enqueue_script("admin_print_scripts-$mypage", plugins_url( '/js/download-orders.js' , __FILE__ ));
}

// Add this admin page to the WP site:
add_action('admin_menu', 'add_to_admin_menu');
function add_to_admin_menu() {
	add_management_page('TRUPER Download Orders', 'DISTRIBUIDORA GC Download Orders', 'manage_options', __FILE__, 'display_page');
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
		<input type="submit" name="search_draft_posts" value="Buscar ordenes" class="button-primary">
	</form>
	<table class="widefat">
		<thead>
			<tr>
				<th>Post Title</th>
				<th>Post ID</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Post Title</th>
				<th>Post ID</th>
			</tr>
		</tfoot>
		<tbody>
<?php
	global $wpdb;
	if( isset($_POST['search_draft_posts']) ) {
		$searchDate = isset($_POST['datefrom']) ? $_POST['datefrom'] : 'NULL';
		$searchToDate = isset($_POST['dateto']) ? $_POST['dateto'] : 'NULL';

		$mytestdrafts = $wpdb->get_results(
			"SELECT ID, post_title
			FROM $wpdb->posts
			WHERE post_date BETWEEN '$searchFromDate' AND '$searchToDate'"
			);
		echo "<h1>$searchDate - $searchToDate</h1>";

		foreach ($mytestdrafts as $mytestdraft) {
?>
			<tr>
<?php
			echo "<td>$mytestdraft->post_title</td>";
			echo "<td>$mytestdraft->ID</td>";
?>
			</tr>
<?php
		}
	}
?>
		</tbody>
	</table>
</div>
<?php
	$args = array(
		'post_type' => 'shop_order',
		'post_status' => 'publish',
		'meta_key' => '_customer_user',
		'posts_per_page' => '-1'
	);
	$my_query = new WP_Query($args);

	$customer_orders = $my_query->posts;

	echo '<h1>testing...</h1>';
	foreach ($customer_orders as $customer_order) {
		$order = new WC_Order();

		$order->populate($customer_order);
		// echo "<h1>Order ID: ".var_dump($order)."</h1>";
		echo "<h1>Order ID: ".$order->id."</h1>";
		$orderdata = (array) $order;

		// $orderdata Array will have Information. for e.g Shippin firstname, Lastname, Address ... and MUCH more.... Just enjoy!
	}
}
?>