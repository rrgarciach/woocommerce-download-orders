<?php
class txtParser {
function parseToAdminPAQtxt($order) {
	$id_pedido = $_GET['id_pedido'];
	$folio = $_GET['folio'];
	$connection = new Connection();
	$connection->connRead('gcadmin');
	//$folio = $_SESSION['order']->id_pedido;
	//$id_pedido = 10;
	//$folio = 2576;
	$renglon = 0;
	$productos = "";
	$result = mysql_query("
	      SELECT 
	      t1.id_pedido, 
	      t1.id_cliente, 
	      t1.id_vendedor, 
	      t1.fecha, 
	      t1.estatus, 
	      t2.id_pedido, 
	      t2.id_codigo, 
	      t2.pu, 
	      t2.iva, 
	      t2.descuento, 
	      t2.cantidad, 
	      t2.stl, 
	      t2.estatus 
	      FROM pedidos AS t1, pedidos_detalle AS t2 
	      WHERE t1.id_pedido='$id_pedido' AND t2.id_pedido='$id_pedido'", 
	      $connection->readDB_read);
	if (mysql_num_rows($result)==0 || mysql_num_rows($result)==false)
	  {
	    return false;
	  }
	  else {
	    while ( $row = mysql_fetch_array($result) ) {
	    	$id_pedido = $row['id_pedido'];
			$id_cliente = $row['id_cliente'];
			$result2 = mysql_query("
	      SELECT 
	      nombre, rfc, calle, numero, colonia, cp, estado, localidad, telefono1, telefono2, descuento, id_zona
	      FROM clientes 
	      WHERE id_cliente='$id_cliente' LIMIT 1", 
	      $connection->readDB_read);
			if ( mysql_num_rows($result)==0 || mysql_num_rows($result)==false ){return false;}
			else {
				while ( $row2 = mysql_fetch_array($result2) ) {
					$nombre = $row2['nombre'];
					$rfc = $row2['rfc'];
					$calle = $row2['calle'];
					$numero = $row2['numero'];
					$colonia = $row2['colonia'];
					$cp = $row2['cp'];
					$telefono1 = $row2['telefono1'];
					$telefono2 = $row2['telefono2'];
					$localidad = $row2['localidad'];
					$estado = $row2['estado'];
					$descuento_cliente = $row2['descuento'];
				}
			$fecha = $row['fecha'];
			$fechaPedido = date("m/d/Y", strtotime($fecha));
			$date = strtotime("+15 day", strtotime($fecha));
			$fecha15dias = date("m/d/Y", $date);
			$date = strtotime("+1 day", strtotime($fecha));
			$fecha1dia = date("m/d/Y", $date);
			$renglon = $renglon + 100;
			$codigo = $row['id_codigo'];
			$cantidad = number_format($row['cantidad'], 5, '.', '');
			/*if ($descuento_cliente > 0) {
				$precio = $row['pu'] - ($row['pu'] * (0.1379310344827586 / 100) );
			}
			else {
				$precio = $row['pu']; // BORRAME CUANDO HAYAS CORREGIDO LA CAPTURA DE LOS DESCUENTOS
			}*/
			if ($descuento_cliente > 0) {
				$precio = $row['pu'] * 1.159964;
			}
			else {
				$precio = $row['pu'];
			}
			//$stl = $row['stl'];
			$iva = $row['iva'];
			$precio = number_format($precio, 5, '.', '');
			$stl_sin_iva = $precio * $cantidad;
			$stl_sin_iva = number_format($stl_sin_iva, 5, '.', '');
			
			//$iva_importe = number_format($row['iva'], 5, '.', '');
			//$iva_importe = $row['iva'] * $cantidad;
			$iva_porc = 16; //15.9964;
			$iva_porc = number_format($iva_porc, 5, '.', '');
			//$iva_importe = $stl_sin_iva * 0.159964;
			$iva_importe = number_format($iva, 5, '.', '');
			$desc_importe = $stl_sin_iva * ($descuento_cliente * 0.01);
			$desc_importe = number_format($desc_importe, 5, '.', '');
			$desc_porc = number_format($descuento_cliente, 5, '.', '');
			//$stl_con_iva = $stl_sin_iva + $iva_importe - $desc_importe;
			$stl_con_iva = $stl_sin_iva + $iva_importe - $desc_importe;
			//$stl_con_iva = $stl_sin_iva + ($iva_importe * $cantidad) - ($desc_importe * $cantidad);
			$stl_con_iva = number_format($stl_con_iva, 5, '.', '');

			// DETALLES DEL PEDIDO------------------------------------------------------------------------------>>>>  RENGLONAJE
$productos .= "MGW10010".PHP_EOL;
$productos .= $renglon."".PHP_EOL; // NO. DE RENGLON DEL PEDIDO
$productos .= $codigo."".PHP_EOL;
$productos .= "1".PHP_EOL; // ALMACEN
$productos .= $cantidad."".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= $cantidad."".PHP_EOL;
$productos .= "".PHP_EOL;
$productos .= "".PHP_EOL;
$productos .= $precio."".PHP_EOL; // PRECIO DE CATALOGO
$productos .= $precio."".PHP_EOL; // PRECIO CAPTURADO
$productos .= "0.00000".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= $stl_sin_iva."".PHP_EOL; // STL SIN IVA
$productos .= $iva_importe."".PHP_EOL;
$productos .= $iva_porc."".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= $desc_importe."".PHP_EOL;
$productos .= $desc_porc."".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= $stl_con_iva."".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= "".PHP_EOL;
$productos .= "3".PHP_EOL;
$productos .= "0".PHP_EOL;
$productos .= "0".PHP_EOL;
$productos .= $fechaPedido."".PHP_EOL;
$productos .= "0".PHP_EOL;
$productos .= "".PHP_EOL;
$productos .= "$cantidad".PHP_EOL;	// CANTIDAD PENDIENTE DE SURTIR
$productos .= "0.00000".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= "0.00000".PHP_EOL; 
$productos .= "1".PHP_EOL; // TIPO DE PAGO
$productos .= "".PHP_EOL;
$productos .= "".PHP_EOL;
$productos .= "".PHP_EOL;
$productos .= "".PHP_EOL;
$productos .= "12/30/1899".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= "{".PHP_EOL;
$productos .= "".PHP_EOL;
$productos .= "}".PHP_EOL;
$productos .= "0.00000".PHP_EOL;
$productos .= "".PHP_EOL;

			}
		}
	}

$txt = "DOCUMENTOS{PHP_EOL}
MGW10008{PHP_EOL}
cCodigoConcepto{PHP_EOL}
CSERIEDOCUMENTO{PHP_EOL}
CFOLIO{PHP_EOL}
CFECHA{PHP_EOL}
cCodigoCteProv{PHP_EOL}
CRAZONSOCIAL{PHP_EOL}
CRFC{PHP_EOL}
cCodigoAgente{PHP_EOL}
CFECHAVENCIMIENTO{PHP_EOL}
CFECHAPRONTOPAGO{PHP_EOL}
CFECHAENTREGARECEPCION{PHP_EOL}
CFECHAULTIMOINTERES{PHP_EOL}
CIDMONEDA{PHP_EOL}
CTIPOCAMBIO{PHP_EOL}
CREFERENCIA{PHP_EOL}
cCodigoDocumentoOrigen{PHP_EOL}{PHP_EOL}
CAFECTADO{PHP_EOL}
CIMPRESO{PHP_EOL}
CCANCELADO{PHP_EOL}
CDEVUELTO{PHP_EOL}
CIDPREPOLIZA{PHP_EOL}
CIDPREPOLIZACANCELACION{PHP_EOL}
CESTADOCONTABLE{PHP_EOL}
CGASTO1{PHP_EOL}
CGASTO2{PHP_EOL}
CGASTO3{PHP_EOL}
CDESCUENTOPRONTOPAGO{PHP_EOL}
CPORCENTAJEIMPUESTO1{PHP_EOL}
CPORCENTAJEIMPUESTO2{PHP_EOL}
CPORCENTAJEIMPUESTO3{PHP_EOL}
CPORCENTAJERETENCION1{PHP_EOL}
CPORCENTAJERETENCION2{PHP_EOL}
CPORCENTAJEINTERES{PHP_EOL}
CTEXTOEXTRA1{PHP_EOL}
CTEXTOEXTRA2{PHP_EOL}
CTEXTOEXTRA3{PHP_EOL}
CFECHAEXTRA{PHP_EOL}
CIMPORTEEXTRA1{PHP_EOL}
CIMPORTEEXTRA2{PHP_EOL}
CIMPORTEEXTRA3{PHP_EOL}
CIMPORTEEXTRA4{PHP_EOL}
CDESTINATARIO{PHP_EOL}
CNUMEROGUIA{PHP_EOL}
CMENSAJERIA{PHP_EOL}
CCUENTAMENSAJERIA{PHP_EOL}
CNUMEROCAJAS{PHP_EOL}
CPESO{PHP_EOL}
COBSERVACIONES{PHP_EOL}
CBANOBSERVACIONES{PHP_EOL}
CBANDATOSENVIO{PHP_EOL}
CBANCONDICIONESCREDITO{PHP_EOL}
CBANGASTOS{PHP_EOL}
CIMPCHEQPAQ{PHP_EOL}
CSISTORIG{PHP_EOL}
CIDMONEDCA{PHP_EOL}
CTIPOCAMCA{PHP_EOL}
/MGW10008{PHP_EOL}
MGW10011{PHP_EOL}
CTIPOCATALOGO{PHP_EOL}
CTIPODIRECCION{PHP_EOL}
CNOMBRECALLE{PHP_EOL}
CNUMEROEXTERIOR{PHP_EOL}
CNUMEROINTERIOR{PHP_EOL}
CCOLONIA{PHP_EOL}
CCODIGOPOSTAL{PHP_EOL}
CTELEFONO1{PHP_EOL}
CTELEFONO2{PHP_EOL}
CTELEFONO3{PHP_EOL}
CTELEFONO4{PHP_EOL}
CEMAIL{PHP_EOL}
CDIRECCIONWEB{PHP_EOL}
CPAIS{PHP_EOL}
CESTADO{PHP_EOL}
CCIUDAD{PHP_EOL}
CTEXTOEXTRA{PHP_EOL}
CMUNICIPIO{PHP_EOL}
/MGW10011{PHP_EOL}
MGW10010{PHP_EOL}
CNUMEROMOVIMIENTO{PHP_EOL}
cCodigoProducto{PHP_EOL}
cCodigoAlmacen{PHP_EOL}
CUNIDADES{PHP_EOL}
CUNIDADESNC{PHP_EOL}
CUNIDADESCAPTURADAS{PHP_EOL}
cNombreUnidad{PHP_EOL}
cNombreUnidadNC{PHP_EOL}
CPRECIO{PHP_EOL}
CPRECIOCAPTURADO{PHP_EOL}
CCOSTOCAPTURADO{PHP_EOL}
CCOSTOESPECIFICO{PHP_EOL}
CNETO{PHP_EOL}
CIMPUESTO1{PHP_EOL}
CPORCENTAJEIMPUESTO1{PHP_EOL}
CIMPUESTO2{PHP_EOL}
CPORCENTAJEIMPUESTO2{PHP_EOL}
CIMPUESTO3{PHP_EOL}
CPORCENTAJEIMPUESTO3{PHP_EOL}
CRETENCION1{PHP_EOL}
CPORCENTAJERETENCION1{PHP_EOL}
CRETENCION2{PHP_EOL}
CPORCENTAJERETENCION2{PHP_EOL}
CDESCUENTO1{PHP_EOL}
CPORCENTAJEDESCUENTO1{PHP_EOL}
CDESCUENTO2{PHP_EOL}
CPORCENTAJEDESCUENTO2{PHP_EOL}
CDESCUENTO3{PHP_EOL}
CPORCENTAJEDESCUENTO3{PHP_EOL}
CDESCUENTO4{PHP_EOL}
CPORCENTAJEDESCUENTO4{PHP_EOL}
CDESCUENTO5{PHP_EOL}
CPORCENTAJEDESCUENTO5{PHP_EOL}
CTOTAL{PHP_EOL}
CPORCENTAJECOMISION{PHP_EOL}
CREFERENCIA{PHP_EOL}
CAFECTAEXISTENCIA{PHP_EOL}
CAFECTADOSALDOS{PHP_EOL}
CAFECTADOINVENTARIO{PHP_EOL}
CFECHA{PHP_EOL}
CMOVTOOCULTO{PHP_EOL}
cCodigoMovtoOrigen{PHP_EOL}
CUNIDADESPENDIENTES{PHP_EOL}
CUNIDADESNCPENDIENTES{PHP_EOL}
CUNIDADESORIGEN{PHP_EOL}
CUNIDADESNCORIGEN{PHP_EOL}
CTIPOTRASPASO{PHP_EOL}
cCodigoValorClasificacion{PHP_EOL}
CTEXTOEXTRA1{PHP_EOL}
CTEXTOEXTRA2{PHP_EOL}
CTEXTOEXTRA3{PHP_EOL}
CFECHAEXTRA{PHP_EOL}
CIMPORTEEXTRA1{PHP_EOL}
CIMPORTEEXTRA2{PHP_EOL}
CIMPORTEEXTRA3{PHP_EOL}
CIMPORTEEXTRA4{PHP_EOL}
CGTOMOVTO{PHP_EOL}
COBSERVAMOV{PHP_EOL}
CCOMVENTA{PHP_EOL}
CSCMOVTO{PHP_EOL}
/MGW10010{PHP_EOL}
MGW10025{PHP_EOL}
cCodigoAlmacen{PHP_EOL}
cCodigoProducto{PHP_EOL}
CFECHA{PHP_EOL}
CTIPOCAPA{PHP_EOL}
CNUMEROLOTE{PHP_EOL}
CFECHACADUCIDAD{PHP_EOL}
CFECHAFABRICACION{PHP_EOL}
CPEDIMENTO{PHP_EOL}
CADUANA{PHP_EOL}
CFECHAPEDIMENTO{PHP_EOL}
CTIPOCAMBIO{PHP_EOL}
CEXISTENCIA{PHP_EOL}
CCOSTO{PHP_EOL}
CCODIGOCAPAORIGEN{PHP_EOL}
CNUMADUANA{PHP_EOL}
/MGW10025{PHP_EOL}
MGW10028{PHP_EOL}
cCodigoCapa{PHP_EOL}
CFECHA{PHP_EOL}
CUNIDADES{PHP_EOL}
CTIPOCAPA{PHP_EOL}
CNOMBREUNIDADCAPTURA{PHP_EOL}
/MGW10028{PHP_EOL}
MGW10032{PHP_EOL}
cCodigoProducto{PHP_EOL}
CNUMEROSERIE{PHP_EOL}
cCodigoAlmacen{PHP_EOL}
CNUMEROLOTE{PHP_EOL}
CFECHACADUCIDAD{PHP_EOL}
CFECHAFABRICACION{PHP_EOL}
CPEDIMENTO{PHP_EOL}
CADUANA{PHP_EOL}
CFECHAPEDIMENTO{PHP_EOL}
CTIPOCAMBIO{PHP_EOL}
CCOSTO{PHP_EOL}
CNUMADUANA{PHP_EOL}
/MGW10032{PHP_EOL}
MGW10009{PHP_EOL}
CLLAVEABONO{PHP_EOL}
cMonedaAbono{PHP_EOL}
CLLAVECARGO{PHP_EOL}
CIDMONEDA{PHP_EOL}
CIMPORTEABONO{PHP_EOL}
CIMPORTECARGO{PHP_EOL}
CFECHAABONOCARGO{PHP_EOL}
CLLAVEDESCUENTO{PHP_EOL}
cCodigoCteProv{PHP_EOL}
/MGW10009{PHP_EOL}
"; // INICIO DE LA ESTRUCTURA DEL TXT--------------

$txt .= "".PHP_EOL;
$txt .= "MGW10008".PHP_EOL; //INFO DEL PEDIDO----------
$txt .= "2".PHP_EOL;
$txt .= "".PHP_EOL;
$txt .= $folio."".PHP_EOL;
$txt .= $fechaPedido."".PHP_EOL;
$txt .= $id_cliente."".PHP_EOL;
$txt .= $nombre."".PHP_EOL;
$txt .= $rfc."".PHP_EOL;
$txt .= "1".PHP_EOL;
$txt .= $fecha15dias."".PHP_EOL; // AUMENTADA 15 DIAS
$txt .= $fechaPedido."".PHP_EOL;
$txt .= $fecha1dia."".PHP_EOL; // AUMENTADA 1 DIA
$txt .= $fecha15dias."".PHP_EOL; // AUMENTADA 15 DIAS
$txt .= "1".PHP_EOL;
$txt .= "1.00000".PHP_EOL;
$txt .= "".PHP_EOL;
$txt .= "".PHP_EOL;
$txt .= "1".PHP_EOL;
$txt .= "0".PHP_EOL; // DOCUMENTO IMPRESO?
$txt .= "0".PHP_EOL;
$txt .= "0".PHP_EOL;
$txt .= "0".PHP_EOL;
$txt .= "0".PHP_EOL;
$txt .= "1".PHP_EOL;
$txt .= "0.00000".PHP_EOL;
$txt .= "0.00000".PHP_EOL;
$txt .= "0.00000".PHP_EOL;
$txt .= "0.00000".PHP_EOL;
$txt .= "0.00000".PHP_EOL;
$txt .= "0.00000".PHP_EOL;
$txt .= "0.00000".PHP_EOL;
$txt .= "0.00000".PHP_EOL;
$txt .= "0.00000".PHP_EOL;
$txt .= "0.00000".PHP_EOL;
$txt .= "".PHP_EOL;
$txt .= "".PHP_EOL;
$txt .= "".PHP_EOL;
$txt .= "12/30/1899".PHP_EOL;
$txt .= "0.00000".PHP_EOL;
$txt .= "0.00000".PHP_EOL;
$txt .= "0.00000".PHP_EOL;
$txt .= "0.00000".PHP_EOL;
$txt .= $nombre."".PHP_EOL;
$txt .= "0".PHP_EOL;
$txt .= "".PHP_EOL;
$txt .= "".PHP_EOL;
$txt .= "0.00000".PHP_EOL;
$txt .= "0.00000".PHP_EOL;
$txt .= "{".PHP_EOL;
$txt .= "".PHP_EOL;
$txt .= "".PHP_EOL;
$txt .= "}".PHP_EOL;
$txt .= "0".PHP_EOL;
$txt .= "1".PHP_EOL;
$txt .= "0".PHP_EOL;
$txt .= "0".PHP_EOL;
$txt .= "0.00000".PHP_EOL;
$txt .= "0".PHP_EOL;
$txt .= "0".PHP_EOL;
$txt .= "0.00000".PHP_EOL;
$txt .= "MGW10011".PHP_EOL; // DIRECCION DEL CLIENTE-------------
$txt .= "3".PHP_EOL; // TIPO DE CATALOGO
$txt .= "0".PHP_EOL; // NUMERO DE DIRACCION
$txt .= $calle."".PHP_EOL;
$txt .= $numero."".PHP_EOL; // NUMERO EXTERIOR
$txt .= "".PHP_EOL; // NUMERO INTERIOR
$txt .= $colonia."".PHP_EOL;
$txt .= $cp."".PHP_EOL;
$txt .= $telefono1."".PHP_EOL;
$txt .= $telefono2."".PHP_EOL;
$txt .= "".PHP_EOL;
$txt .= "".PHP_EOL;
$txt .= "".PHP_EOL;  //******************************************
$txt .= "".PHP_EOL;
$txt .= "MEXICO".PHP_EOL;
$txt .= $localidad."".PHP_EOL;
$txt .= $localidad."".PHP_EOL;
$txt .= "".PHP_EOL;
$txt .= $estado."".PHP_EOL;
$txt .= "MGW10011".PHP_EOL; // DIRECCION DEL CLIENTE-------------
$txt .= "3".PHP_EOL; // TIPO DE CATALOGO
$txt .= "1".PHP_EOL; // NUMERO DE DIRACCION
$txt .= $calle."".PHP_EOL;
$txt .= $numero."".PHP_EOL; // NUMERO EXTERIOR
$txt .= "".PHP_EOL; // NUMERO INTERIOR
$txt .= $colonia."".PHP_EOL;
$txt .= $cp."".PHP_EOL;
$txt .= $telefono1."".PHP_EOL;
$txt .= $telefono2."".PHP_EOL;
$txt .= "".PHP_EOL;
$txt .= "".PHP_EOL;
$txt .= "".PHP_EOL;  //******************************************
$txt .= "".PHP_EOL;
$txt .= "MEXICO".PHP_EOL;
$txt .= $localidad."".PHP_EOL;
$txt .= $localidad."".PHP_EOL;
$txt .= "".PHP_EOL;
$txt .= $estado."".PHP_EOL;
$txt .= $productos;

$pedidoFormatted = str_pad($folio, 15, '0', STR_PAD_LEFT); // SETS FORMAT WITH LEADING ZEROS

}

}

?>