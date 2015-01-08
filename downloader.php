<?php
// Function to grab the search params from POST and save it.
function getParamsOptions() {
	$search_params = array();
	if ( isset($_POST['search_orders']) ) {
		$search_params['date_from'] 		= $_POST['date_from'];
		$search_params['date_to']			= $_POST['date_to'];
		$search_params['customer_id_from'] 	= $_POST['customer_id_from'];
		$search_params['customer_id_to'] 	= $_POST['customer_id_to'];
		update_option('download_orders_search_params', $search_params);
	} else if ( $storedOptions = get_option('download_orders_search_params') ) {
		$search_params = $storedOptions;
	}
	return $search_params;
}

function downloadTest() {
	$orders = searchOrders();
	// die(var_dump($orders));
	$fileName = 'somefile.csv';
 
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header('Content-Description: File Transfer');
	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename={$fileName}");
	header("Expires: 0");
	header("Pragma: public");
	 
	$fh = fopen( 'php://output', 'w' );
	// $fh = fopen( $fileName, 'w' );

	$headerDisplayed = false;
	foreach ($orders as $order) {
		// Add a header row if it hasn't been added yet
	    if ( !$headerDisplayed ) {
	        // Use the keys from $data as the titles
	        $headers = array('ID Pedido', 'ID Cliente', 'Nombre Cliente', 'Fecha', 'Detalle', 'Total', 'Descarga');
	        fputcsv($fh, $headers);
	        $headerDisplayed = true;
	    }
		// die(var_dump($order));
	    $data = array();
		$data[] = $order->id;
		$data[] = $order->customer->user_login;
		$data[] = $order->customer->display_name;
		$data[] = $order->order_date;
		$data[] = '';
		$data[] = number_format($order->total, 2);
		$data[] = '';
		// Put the data into the stream
	    fputcsv($fh, $data);
	}
	 
	// global $wpdb;
	// $query = "SELECT * FROM `{$wpdb->prefix}my_table`";
	// $results = $wpdb->get_results( $query, ARRAY_A );
	 
	// $headerDisplayed = false;
	 
	// foreach ( $results as $data ) {
	//     // Add a header row if it hasn't been added yet
	//     if ( !$headerDisplayed ) {
	//         // Use the keys from $data as the titles
	//         fputcsv($fh, array_keys($data));
	//         $headerDisplayed = true;
	//     }
	 
	//     // Put the data into the stream
	//     fputcsv($fh, $data);
	// }
	// Close the file
	fclose($fh);
	// Make sure nothing else is sent, our file is done
	exit;
}

function searchOrders( $args = array() ) {
	$args['post_type'] = 'shop_order';
	$args['post_status'] = 'publish';
	$args['meta_key'] = '_customer_user';
	$args['posts_per_page'] = '-1';

	$query = new WP_Query($args);

	$data = $query->posts;

	$ordersData = array();
	foreach ($data as $dataItem) {
		$order = new WC_Order();

		$order->populate($dataItem);

		// $order->customer = get_user_by( 'id', $order->user_id );
		$order->customer = $order->get_user();
		// die(var_dump($order->customer->display_name));

		$order->total = $order->calculate_totals();
		// die(var_dump($order->total));

		/*
		global $wpdb;
		// $result = (array) $wpdb->get_results("SELECT t2.meta_value FROM wp_woocommerce_order_items as t1 JOIN wp_woocommerce_order_itemmeta as t2 ON t1.order_item_id=t2.order_item_id WHERE t1.order_id=45 AND (t2.meta_key='_line_subtotal' OR t2.meta_key='_line_subtotal_tax')");
		// $result = (array) $wpdb->get_results("SELECT t2.* FROM wp_woocommerce_order_items as t1 JOIN wp_woocommerce_order_itemmeta as t2 ON t1.order_item_id=t2.order_item_id WHERE t1.order_id=45");
		// echo gettype($result);
		// die(var_dump($result));
		$order->total = 0.0;
		foreach ($result as $amount) {
			// die( var_dump($amount) );
			// die( var_dump($result[0]->meta_value) );
			$order->total += $amount->meta_value;
		}
		*/
		$ordersData[] = $order;
	}
		// die(var_dump($ordersData[3]->get_items()));
		// var_dump($ordersData[0]);
		// echo var_dump($ordersData[0]->customer);
		// var_dump($ordersData[0]->customer->user_login);
		// var_dump($ordersData[0]->id);
		// echo var_dump($ordersData[0]->customer->ID);
		// var_dump($ordersData[0]->products);
		// var_dump($ordersData[0]->products[6]);
	return $ordersData;
}