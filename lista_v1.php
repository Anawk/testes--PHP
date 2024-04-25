<?php


require_once('classes/Passageiros.class.php');
require_once('classes/Viagens.class.php');


$objPassageiros = new Passageiros;
$objViagens = new Viagens;



$idViagem = isset($_GET['idViagem']) ? $_GET['idViagem'] : "";
$contador = 0;

// pego a quantidade de poltronas do onibus de acordo com a viagem. ex 42, 46, 50.
$quantidadeDePoltronasDoOnibus = $objViagens->getOnibusByViagem($idViagem);
// Depois de pegar o total de poltronas do onibus eu tranformo essa quantidade em um array 
for ($i = 1; $i <= $quantidadeDePoltronasDoOnibus['bus_qtd_poltrona']; $i++) { //Tranformo a quantidade em array
	// agora eu tenho a quantidade de poltronas em um array ex. 1,2,3...

	if (in_array(58, $poltronas)) {
		unset($poltronas[57]);

		//die($poltronas[$i]);
	}

	$poltronas[] = $i;
}

//$dados_viagem = $objViagens->find($idViagem);
$dados_viagem = ( explode("-",$objViagens->find($idViagem)['dt_partida']));

//echo "<pre>"; print_r($dados_viagem); die;
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta name="viewport" name="content=width=device-width, inicial-scale=1">
	<meta name="google-site-verification" content="XWF3zfS-1yxkoGf-NRaHzeIIYclXah1EQNvsSeczxQc" />
	<title>Bus</title>
	<meta name="description" content="Agência especializada em Marketing Digital, Criação de Sites Aplicativos Mobile">
	<meta name="keywords" content="Agência digital, Marketing, Sites">
	<meta name="robots" content="index, follow">
	<meta name="author" content="Tiago Silva">
	<!-- arquivos quando estava sem internet-->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<!--<link href="css/jquery-ui.min.css" rel="stylesheet">-->

	<!-- fim arquivos quando estava sem internet-->
	<!--<link href="css/css_onibus.css" rel="stylesheet">-->
	<link rel="stylesheet" href="css/man.css">
	<link rel="stylesheet" href="css/animade.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">

	<style>
		@media print {

			* {
				font-size: 12px !important;
				margin: 0;
				padding: 0;
				line-height: unset;
				font-size: 10px !important;
			}

			td.numero_par {
				background-color: #cccccc57 !important;
				-webkit-print-color-adjust: exact;

			}

			td.cor {

				background-color: red !important;
				-webkit-print-color-adjust: exact;

			}

			.table-bordered>thead>tr>td,
			.table-bordered>thead>tr>th {
				border-bottom-width: 0px;
			}

			.table-bordered {
				border: 1px solid #ddd !important;
			}

			/* .table-striped>tbody>tr:nth-of-type(odd) {
				background-color: #cccccc57 !important;
				-webkit-print-color-adjust: exact;
			} */

			.table>tbody>tr>td,
			.table>tbody>tr>th,
			.table>tfoot>tr>td,
			.table>tfoot>tr>th,
			.table>thead>tr>td,
			.table>thead>tr>th {
				padding: 0px;
			/*padding: 2px 0 2px 10px;*/
			}



		}


		.table>tbody>tr>td,
		.table>tbody>tr>th,
		.table>tfoot>tr>td,
		.table>tfoot>tr>th,
		.table>thead>tr>td,
		.table>thead>tr>th {
			padding: 0px;
			/*padding: 2px 0 2px 10px;*/
		}

		.table-bordered>tbody>tr>td,
		.table-bordered>tbody>tr>th,
		.table-bordered>tfoot>tr>td,
		.table-bordered>tfoot>tr>th,
		.table-bordered>thead>tr>td,
		.table-bordered>thead>tr>th {
			border: 1px solid #000 !important;
		}

		.cor {
			background-color: red !important;


		}

		td.numero_par {
			background-color: #cccccc57 !important;
			-webkit-print-color-adjust: exact;

		}

		@page {
			margin: 0;
			padding: 0;

		}

		.table-bordered {
			border: 1px solid #ddd !important;
		}

		/* .table-striped>tbody>tr:nth-of-type(odd) {
			background-color: #cccccc57;
		} */
	</style>


</head>


<body>

	<br>

	<a style="padding-left:10px" onclick="Imprimir();" id="btnimprimir" class="btn btn-adicionar btn-primary">
		Imprimir
	</a>
	<p style="padding-left:10px"> </p>

	<table id="myTabledd" class="table table-striped table-striped table-hover table-bordered text-uppercase display nowrap" style="width: 96%; margin-left:2%">
		<thead>
			<tr>

				<th scope="col" colspan="6" style="padding-left:2px">
					<p class="text-center bold" style="font-weight: bold;"> RELAÇÃO DE PASSAGEIROS
						PORTAL SUL VIAGENS
					</p>
				</th>
			</tr>
			<tr>

				<th scope="col" colspan="6" style="padding-left:20px">Data: <?php echo $dados_viagem['2']."/".$dados_viagem['1']."/".$dados_viagem['0'];?></th>
			</tr>
			<tr class="vendorListHeading">

				<th scope="col" style="padding-left:18px">EMBARQUE</th>
				<th style="width: 25px;" class="text-center" scope="col" onclick="sortTable(1)"><img style="margin:0" src="images/baseline_airline_seat_recline_extra_black_18dp.png" alt=""></th>
				<th scope="col">NOME</th>
				<th>TELEFONE</th>
				<th>VALOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php

			//echo "<pre>"; print_r($objPassageiros->findAllPassageirosViagens1($idViagem));


			foreach ($objPassageiros->findAllPassageirosViagens1($idViagem) as $key => $value) {
				$totalPassageiros[] = $value->bus_Numero_poltrona;
				$nomePassageiroByPoltrona[$value->bus_Numero_poltrona] = $value->bus_nome_passageiros;

				$pontoInicial[$value->bus_Numero_poltrona] = $value->bus_ponto_inicial;
				$fone[$value->bus_Numero_poltrona] = $value->bus_telefone;
			}


			//echo "<pre>"; print_r($objPassageiros->findAllPassageirosViagens1($idViagem)); die;

			//foreach ($objPassageiros->findAllPassageirosViagens1($idViagem) as $key => $value) { 
			$i = 0;
			$par = "";
			$numero_par = "";
			foreach ($poltronas as $key => $value) {
				$i + 1;

				if ($i % 2 == 0) {
					$numero_par = "numero_par";
				} else {
					$numero_par = "";
				}
				// $mod = $i & 0;

				// if($mod == 0){
				// 	echo "Par";
				// }else{
				// 	echo "Impar";
				// }
			?>


				<?php

				$contador++;
				$cor = "";

				switch ($contador % 4) {
					case 1:
					case 2:
						$cor = "cor";
				}
				?>
				<tr class="vendorListHeading <?php echo $par ?>">

					<td style="padding-left:20px" class="<?php echo $numero_par; ?>">

						<?php

						//echo (isset($value->bus_ponto_inicial)) ? $value->bus_ponto_inicial : ''; 

						if (in_array($value, $totalPassageiros)) {

							echo $pontoInicial[$value];
						}
						?>

					</td>
					<!-- Se tiver a cor de fundo da poltrona coloca red, se não coloca a cor da zebra - tive que validar estava dando pau -->
					<td style="font-weight:bold" class="text-center  <?php echo (isset($cor) && $cor != "" ) ? $cor : $numero_par;  ?> ">
						<?php
						//echo (isset($value->bus_postrona_passageiros) && $value->bus_postrona_passageiros != null) ? $value->bus_postrona_passageiros : '<img style="float:none" src="images/bebe.png">'; 

						//if (in_array($value, $totalPassageiros)) { 

						echo $value;

						//}
						?>
					</td>
					<td style="padding-left:3px" class="<?php echo $numero_par; ?>"> <?php

																					if (in_array($value, $totalPassageiros)) {

																						echo $nomePassageiroByPoltrona[$value];
																					}

																					//echo (isset($value->bus_nome_passageiros)) ? $value->bus_nome_passageiros : ''; 
																					?></td>




					<td class="<?php echo $numero_par; ?>"><?php
															if (in_array($value, $totalPassageiros)) {

																echo (isset($fone[$value]) && $fone[$value] != "0") ? $fone[$value] : "";
															}



															?></td>
					<td class="<?php echo $numero_par; ?>">R$</td>
				</tr>
			<?php $i++;
			} ?>
		</tbody>
	</table>

	<script>
		function Imprimir() {
			document.getElementById("btnimprimir").style.display = "none";


			// document.getElementById("Tiradoimprimir").style.display = "none";

			window.print();
		}
	</script>

</body>

</html>