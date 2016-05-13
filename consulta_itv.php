<?php
	include('simple_html_dom.php');

	$url = "http://itv.com.es";

	$contenido = file_get_html($url);

	$return = $contenido->find('area[title=*]');

	$aux=[];
	$i = 0;
	foreach ($return as $l) {
		$aux[$i]=($url.$l->href);
		$i++;
		if($i >= 5){break;}
	}

	$solucion = [];
	$k = 0;

	foreach ($aux as $p) {
		$c = file_get_html($p);
		$temp = $c->find('td');
		$i = 0;
		$j = 0;
		$itv = [];
		foreach ($temp as $t) {
		 	if($i % 3 == 2){
		 		$info = $t->first_child()->href;
		 		$ruta = $url.'/'.$info;
		 		$itv[$j] = file_get_html($ruta);
		 		$j++;
		 	}
		 	$i++;
		} 
		//$k = 0;
		//$solucion = [];
		foreach ($itv as $seleccion) {
			$res = $seleccion->find('h3[itemprop=*],span[itemprop=*]');
			$cont = 0;
			$cad = [];
			foreach ($res as $r) {
				array_push($cad, $r->plaintext);

				if($cont > 3){  
					break;
				}
				else{$cont++;}
			}
			$solucion[$k] = $cad;
			$k++;
		}	
	}

	$fich = fopen("resultado.csv", "w");
	$array1 = array('NOMBRE','PROVINCIA','LOCALIDAD','DIRECCION','TELEFONO');
	
	fputcsv($fich, $array1);
	foreach ($solucion as $s) {
		fputcsv($fich, $s);
	}
	fclose($fich);
?>