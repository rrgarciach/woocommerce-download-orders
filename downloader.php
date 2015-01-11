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

function downloadTXT() {
	$orders = searchOrders();
	$parser = new txtParser();
	$parser->parseToAdminPAQtxt($orders);
}

function downloadTest($args = array()) {
	$orders = searchOrders($args);
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
	// Close the file
	fclose($fh);
	// Make sure nothing else is sent, our file is done
	exit;
}

function searchOrders( $args = array() ) {
	if ( isset($args['order_id']) ) {
		$order_id = $args['order_id'];
		$order = new WC_Order( $order_id );
		$order->customer = $order->get_user();
		$order->total = $order->calculate_totals();

		$ordersData = array();
		$ordersData[] = $order;

	} else {
		$args['post_type']		 = isset($args['post_type']) ? $args['post_type'] : 'shop_order';
		$args['post_status']	 = isset($args['post_status']) ? $args['post_status'] : 'publish';
		$args['meta_key']		 = isset($args['meta_key']) ? $args['meta_key'] : '_customer_user';
		// $args['posts_per_page']	 = isset($args['posts_per_page']) ? $args['posts_per_page'] : '-1';
		$query = new WP_Query( $args );

		$data = $query->posts;

		$ordersData = array();
		foreach ($data as $dataItem) {
			$order = new WC_Order();

			$order->populate($dataItem);
			$order->customer = $order->get_user();

			$order->total = $order->calculate_totals();
			$ordersData[] = $order;
		}
	}
	return $ordersData;
}