<?php
// VARIABLES PARA DEBUG
/*$id_pedido = "2489";
$fecha = "01/16/2013";
$id_cliente = "5699";
$apellido_p = "GARCIA";
$apellido_m = "CHAVEZ";
$nombre = "RUY RODRIGO";
$rfc = "GACR8502201W3";
$fecha = "01/16/2013";
$fecha15dias = "01/31/2013";
$fecha1dia = "01/17/2013";
$calle = "CALLE";
$numero = "NUMERO";
$colonia = "COLONIA";
$telefono1 = "614777777";
$telefono2 = "614555555";
$localidad = "CHIHUAHUA";
$estado = "CHIHUAHUA";
$renglon = "100";
$codigo = "22606";
$cantidad = "2";
$precio = "25.00000";
$stl_sin_iva = "50.00000";
$iva_importe = "8";
$iva_porc = "16";
$desc_importe = "0.00000";
$desc_porc = "0.00000";
$stl_con_iva = "58.00000";*/
// VARIABLES DEL PEDIDO
//function adminPAQtxt ( $id_pedido, $folio ) 
function adminPAQtxt () {
	$id_pedido = $_GET['id_pedido'];
	$folio = $_GET['folio'];
	require_once "../classes/mysql.class.php";
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
$productos .= "MGW10010\n";
$productos .= $renglon."\n"; // NO. DE RENGLON DEL PEDIDO
$productos .= $codigo."\n";
$productos .= "1\n"; // ALMACEN
$productos .= $cantidad."\n";
$productos .= "0.00000\n";
$productos .= $cantidad."\n";
$productos .= "\n";
$productos .= "\n";
$productos .= $precio."\n"; // PRECIO DE CATALOGO
$productos .= $precio."\n"; // PRECIO CAPTURADO
$productos .= "0.00000\n";
$productos .= "0.00000\n";
$productos .= $stl_sin_iva."\n"; // STL SIN IVA
$productos .= $iva_importe."\n";
$productos .= $iva_porc."\n";
$productos .= "0.00000\n";
$productos .= "0.00000\n";
$productos .= "0.00000\n";
$productos .= "0.00000\n";
$productos .= "0.00000\n";
$productos .= "0.00000\n";
$productos .= "0.00000\n";
$productos .= "0.00000\n";
$productos .= $desc_importe."\n";
$productos .= $desc_porc."\n";
$productos .= "0.00000\n";
$productos .= "0.00000\n";
$productos .= "0.00000\n";
$productos .= "0.00000\n";
$productos .= "0.00000\n";
$productos .= "0.00000\n";
$productos .= "0.00000\n";
$productos .= "0.00000\n";
$productos .= $stl_con_iva."\n";
$productos .= "0.00000\n";
$productos .= "\n";
$productos .= "3\n";
$productos .= "0\n";
$productos .= "0\n";
$productos .= $fechaPedido."\n";
$productos .= "0\n";
$productos .= "\n";
$productos .= "$cantidad\n";	// CANTIDAD PENDIENTE DE SURTIR
$productos .= "0.00000\n";
$productos .= "0.00000\n";
$productos .= "0.00000\n"; 
$productos .= "1\n"; // TIPO DE PAGO
$productos .= "\n";
$productos .= "\n";
$productos .= "\n";
$productos .= "\n";
$productos .= "12/30/1899\n";
$productos .= "0.00000\n";
$productos .= "0.00000\n";
$productos .= "0.00000\n";
$productos .= "0.00000\n";
$productos .= "0.00000\n";
$productos .= "{\n";
$productos .= "\n";
$productos .= "}\n";
$productos .= "0.00000\n";
$productos .= "\n";

			}
		}
	}

$txt = "DOCUMENTOS
MGW10008
cCodigoConcepto
CSERIEDOCUMENTO
CFOLIO
CFECHA
cCodigoCteProv
CRAZONSOCIAL
CRFC
cCodigoAgente
CFECHAVENCIMIENTO
CFECHAPRONTOPAGO
CFECHAENTREGARECEPCION
CFECHAULTIMOINTERES
CIDMONEDA
CTIPOCAMBIO
CREFERENCIA
cCodigoDocumentoOrigen
CAFECTADO
CIMPRESO
CCANCELADO
CDEVUELTO
CIDPREPOLIZA
CIDPREPOLIZACANCELACION
CESTADOCONTABLE
CGASTO1
CGASTO2
CGASTO3
CDESCUENTOPRONTOPAGO
CPORCENTAJEIMPUESTO1
CPORCENTAJEIMPUESTO2
CPORCENTAJEIMPUESTO3
CPORCENTAJERETENCION1
CPORCENTAJERETENCION2
CPORCENTAJEINTERES
CTEXTOEXTRA1
CTEXTOEXTRA2
CTEXTOEXTRA3
CFECHAEXTRA
CIMPORTEEXTRA1
CIMPORTEEXTRA2
CIMPORTEEXTRA3
CIMPORTEEXTRA4
CDESTINATARIO
CNUMEROGUIA
CMENSAJERIA
CCUENTAMENSAJERIA
CNUMEROCAJAS
CPESO
COBSERVACIONES
CBANOBSERVACIONES
CBANDATOSENVIO
CBANCONDICIONESCREDITO
CBANGASTOS
CIMPCHEQPAQ
CSISTORIG
CIDMONEDCA
CTIPOCAMCA
/MGW10008
MGW10011
CTIPOCATALOGO
CTIPODIRECCION
CNOMBRECALLE
CNUMEROEXTERIOR
CNUMEROINTERIOR
CCOLONIA
CCODIGOPOSTAL
CTELEFONO1
CTELEFONO2
CTELEFONO3
CTELEFONO4
CEMAIL
CDIRECCIONWEB
CPAIS
CESTADO
CCIUDAD
CTEXTOEXTRA
CMUNICIPIO
/MGW10011
MGW10010
CNUMEROMOVIMIENTO
cCodigoProducto
cCodigoAlmacen
CUNIDADES
CUNIDADESNC
CUNIDADESCAPTURADAS
cNombreUnidad
cNombreUnidadNC
CPRECIO
CPRECIOCAPTURADO
CCOSTOCAPTURADO
CCOSTOESPECIFICO
CNETO
CIMPUESTO1
CPORCENTAJEIMPUESTO1
CIMPUESTO2
CPORCENTAJEIMPUESTO2
CIMPUESTO3
CPORCENTAJEIMPUESTO3
CRETENCION1
CPORCENTAJERETENCION1
CRETENCION2
CPORCENTAJERETENCION2
CDESCUENTO1
CPORCENTAJEDESCUENTO1
CDESCUENTO2
CPORCENTAJEDESCUENTO2
CDESCUENTO3
CPORCENTAJEDESCUENTO3
CDESCUENTO4
CPORCENTAJEDESCUENTO4
CDESCUENTO5
CPORCENTAJEDESCUENTO5
CTOTAL
CPORCENTAJECOMISION
CREFERENCIA
CAFECTAEXISTENCIA
CAFECTADOSALDOS
CAFECTADOINVENTARIO
CFECHA
CMOVTOOCULTO
cCodigoMovtoOrigen
CUNIDADESPENDIENTES
CUNIDADESNCPENDIENTES
CUNIDADESORIGEN
CUNIDADESNCORIGEN
CTIPOTRASPASO
cCodigoValorClasificacion
CTEXTOEXTRA1
CTEXTOEXTRA2
CTEXTOEXTRA3
CFECHAEXTRA
CIMPORTEEXTRA1
CIMPORTEEXTRA2
CIMPORTEEXTRA3
CIMPORTEEXTRA4
CGTOMOVTO
COBSERVAMOV
CCOMVENTA
CSCMOVTO
/MGW10010
MGW10025
cCodigoAlmacen
cCodigoProducto
CFECHA
CTIPOCAPA
CNUMEROLOTE
CFECHACADUCIDAD
CFECHAFABRICACION
CPEDIMENTO
CADUANA
CFECHAPEDIMENTO
CTIPOCAMBIO
CEXISTENCIA
CCOSTO
CCODIGOCAPAORIGEN
CNUMADUANA
/MGW10025
MGW10028
cCodigoCapa
CFECHA
CUNIDADES
CTIPOCAPA
CNOMBREUNIDADCAPTURA
/MGW10028
MGW10032
cCodigoProducto
CNUMEROSERIE
cCodigoAlmacen
CNUMEROLOTE
CFECHACADUCIDAD
CFECHAFABRICACION
CPEDIMENTO
CADUANA
CFECHAPEDIMENTO
CTIPOCAMBIO
CCOSTO
CNUMADUANA
/MGW10032
MGW10009
CLLAVEABONO
cMonedaAbono
CLLAVECARGO
CIDMONEDA
CIMPORTEABONO
CIMPORTECARGO
CFECHAABONOCARGO
CLLAVEDESCUENTO
cCodigoCteProv
/MGW10009
"; // INICIO DE LA ESTRUCTURA DEL TXT--------------

$txt .= "\n";
$txt .= "MGW10008\n"; //INFO DEL PEDIDO----------
$txt .= "2\n";
$txt .= "\n";
$txt .= $folio."\n";
$txt .= $fechaPedido."\n";
$txt .= $id_cliente."\n";
$txt .= $nombre."\n";
$txt .= $rfc."\n";
$txt .= "1\n";
$txt .= $fecha15dias."\n"; // AUMENTADA 15 DIAS
$txt .= $fechaPedido."\n";
$txt .= $fecha1dia."\n"; // AUMENTADA 1 DIA
$txt .= $fecha15dias."\n"; // AUMENTADA 15 DIAS
$txt .= "1\n";
$txt .= "1.00000\n";
$txt .= "\n";
$txt .= "\n";
$txt .= "1\n";
$txt .= "0\n"; // DOCUMENTO IMPRESO?
$txt .= "0\n";
$txt .= "0\n";
$txt .= "0\n";
$txt .= "0\n";
$txt .= "1\n";
$txt .= "0.00000\n";
$txt .= "0.00000\n";
$txt .= "0.00000\n";
$txt .= "0.00000\n";
$txt .= "0.00000\n";
$txt .= "0.00000\n";
$txt .= "0.00000\n";
$txt .= "0.00000\n";
$txt .= "0.00000\n";
$txt .= "0.00000\n";
$txt .= "\n";
$txt .= "\n";
$txt .= "\n";
$txt .= "12/30/1899\n";
$txt .= "0.00000\n";
$txt .= "0.00000\n";
$txt .= "0.00000\n";
$txt .= "0.00000\n";
$txt .= $nombre."\n";
$txt .= "0\n";
$txt .= "\n";
$txt .= "\n";
$txt .= "0.00000\n";
$txt .= "0.00000\n";
$txt .= "{\n";
$txt .= "\n";
$txt .= "\n";
$txt .= "}\n";
$txt .= "0\n";
$txt .= "1\n";
$txt .= "0\n";
$txt .= "0\n";
$txt .= "0.00000\n";
$txt .= "0\n";
$txt .= "0\n";
$txt .= "0.00000\n";
$txt .= "MGW10011\n"; // DIRECCION DEL CLIENTE-------------
$txt .= "3\n"; // TIPO DE CATALOGO
$txt .= "0\n"; // NUMERO DE DIRACCION
$txt .= $calle."\n";
$txt .= $numero."\n"; // NUMERO EXTERIOR
$txt .= "\n"; // NUMERO INTERIOR
$txt .= $colonia."\n";
$txt .= $cp."\n";
$txt .= $telefono1."\n";
$txt .= $telefono2."\n";
$txt .= "\n";
$txt .= "\n";
$txt .= "\n";  //******************************************
$txt .= "\n";
$txt .= "MEXICO\n";
$txt .= $localidad."\n";
$txt .= $localidad."\n";
$txt .= "\n";
$txt .= $estado."\n";
$txt .= "MGW10011\n"; // DIRECCION DEL CLIENTE-------------
$txt .= "3\n"; // TIPO DE CATALOGO
$txt .= "1\n"; // NUMERO DE DIRACCION
$txt .= $calle."\n";
$txt .= $numero."\n"; // NUMERO EXTERIOR
$txt .= "\n"; // NUMERO INTERIOR
$txt .= $colonia."\n";
$txt .= $cp."\n";
$txt .= $telefono1."\n";
$txt .= $telefono2."\n";
$txt .= "\n";
$txt .= "\n";
$txt .= "\n";  //******************************************
$txt .= "\n";
$txt .= "MEXICO\n";
$txt .= $localidad."\n";
$txt .= $localidad."\n";
$txt .= "\n";
$txt .= $estado."\n";
$txt .= $productos;
/*//---------------------------------------------------------------
$txt .= "MGW10010
"; // DETALLES DEL PEDIDO---------------
$txt .= "200
"; // NO. DE RENGLON DEL PEDIDO
$txt .= $codigo."
";
$txt .= "1
"; // ALMACEN
$txt .= $cantidad."
";
$txt .= "0.00000
";
$txt .= $cantidad."
";
$txt .= "
";
$txt .= "
";
$txt .= $precio."
"; // PRECIO DE CATALOGO
$txt .= $precio."
"; // PRECIO CAPTURADO
$txt .= "0.00000
";
$txt .= "0.00000
";
$txt .= $stl_sin_iva."
"; // STL SIN IVA
$txt .= $iva_importe."
";
$txt .= $iva_porc."
";
$txt .= "0.00000
";
$txt .= "0.00000
";
$txt .= "0.00000
";
$txt .= "0.00000
";
$txt .= "0.00000
";
$txt .= "0.00000
";
$txt .= "0.00000
";
$txt .= "0.00000
";
$txt .= $desc_importe."
";
$txt .= $desc_porc."
";
$txt .= "0.00000
";
$txt .= "0.00000
";
$txt .= "0.00000
";
$txt .= "0.00000
";
$txt .= "0.00000
";
$txt .= "0.00000
";
$txt .= "0.00000
";
$txt .= "0.00000
";
$txt .= $stl_con_iva."
";
$txt .= "0.00000
";
$txt .= "
";
$txt .= "3
";
$txt .= "0
";
$txt .= "0
";
$txt .= $fechaPedido."
";
$txt .= "0
";
$txt .= "
";
$txt .= "0.00000
";
$txt .= "0.00000
";
$txt .= "0.00000
";
$txt .= "0.00000
"; 
$txt .= "1
"; // TIPO DE PAGO
$txt .= "
";
$txt .= "
";
$txt .= "
";
$txt .= "
";
$txt .= "12/30/1899
";
$txt .= "0.00000
";
$txt .= "0.00000
";
$txt .= "0.00000
";
$txt .= "0.00000
";
$txt .= "0.00000
";
$txt .= "{
";
$txt .= "
";
$txt .= "}
";
$txt .= "0.00000
";
$txt .= "
";
//---------------------------------------------------------------*/



	$pedidoFormatted = str_pad($folio, 15, '0', STR_PAD_LEFT); // SETS FORMAT WITH LEADING ZEROS

	header('Content-Type: application/octetstream; name="Pedido_20130116'.$pedidoFormatted.'.txt"');
	header('Content-Type: application/octet-stream; name="Pedido_20130116'.$pedidoFormatted.'.txt"');
	header('Content-Disposition: attachment; filename="Pedido_20130116'.$pedidoFormatted.'.txt"');
	print $txt;

	/*$myFile = "/files/orders/txt/testFile.txt";
	$txt = fopen($myFile, 'w') or die("can't open file");
	//$stringData = "Bobby Bopper\n";
	fwrite($txt, $stringData);
	//$stringData = "Tracy Tanner\n";
	//fwrite($txt, $stringData);
	fclose($txt);*/
}

adminPAQtxt();

?>