<?php if (!isset($_SESSION)) session_start(); ?>
<?php
include "classes/Login.class.php";
require_once "classes/Helpers.class.php";

Login::verificaSeEstaLogado();
$objHelpers = new Helpers();

require_once "classes/Usuario.class.php";
$user = new Usuario;

require_once "classes/Pagamento.class.php";
$objPagamento = new Pagamento1;

$protocolo = "http";
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
		$protocolo = "https";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Neon Admin Panel" />
	<meta name="author" content="" />

	<link rel="icon" href="<?php echo $protocolo;?>://www.tinebus.com.br/arquivos/assets/images/favicon.ico">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

	<title>Tine Bus</title>


	<link rel="stylesheet" href="<?php echo $protocolo;?>://www.tinebus.com.br/arquivos/assets/css/font-icons/entypo/css/entypo.css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
	<link rel="stylesheet" href="<?php echo $protocolo;?>://www.tinebus.com.br/arquivos/assets/css/bootstrap.css">
	<!--
	<link rel="stylesheet" href="<?php echo $protocolo;?>://www.tinebus.com.br/arquivos/assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
   -->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

	<link rel="stylesheet" href="<?php echo $protocolo;?>://www.tinebus.com.br/arquivos/assets/css/neon-core.css">
	<link rel="stylesheet" href="<?php echo $protocolo;?>://www.tinebus.com.br/arquivos/assets/css/neon-theme.css">

	<link rel="stylesheet" href="<?php echo $protocolo;?>://www.tinebus.com.br/arquivos/assets/css/neon-forms.css">

	<link rel="stylesheet" href="<?php echo $protocolo;?>://www.tinebus.com.br/arquivos/assets/css/animade.css">

	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">

	<link rel="stylesheet" href="<?php echo $protocolo;?>://www.tinebus.com.br/arquivos/assets/css/custom.css">

	<link rel="stylesheet" href="<?php echo $protocolo;?>://www.tinebus.com.br/arquivos/assets/css/custom.css">

	<!-- se página for igual passageiros mostra o css do onibus -->
	<?php if (isset($_GET['page']) && $_GET['page'] != "") { ?>
		<?php if ($_GET['page'] == "passageiros") { ?>
			<link rel="stylesheet" href="<?php echo $protocolo;?>://www.tinebus.com.br/arquivos/assets/css/css_onibus.css">
	<?php }
	} ?>

<link rel="stylesheet" href="<?php echo $protocolo;?>://www.tinebus.com.br/global/assets/css/botao_switch.css">

	<link rel="stylesheet" href="<?php echo $protocolo;?>://www.tinebus.com.br/arquivos/assets/js/jvectormap/jquery-jvectormap-1.2.2.css">


	<!----<script src="<?php echo $protocolo;?>://www.tinebus.com.br/arquivos/assets/js/jquery-1.11.3.min.js"></script>-->

	<!--[if lt IE 9]><script src="<?php echo $protocolo;?>://www.tinebus.com.br/arquivos/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style type="text/css">
		body {
			color: #4e4a4a;
			font-size: 15px;
		}

		/*Css lista de viagens*/
		.lista-viagens .btn {
			padding: 1px 5px;
		}

		.lista-viagens .btn-editar {
			background-color: #364150 !important;
			border: 0;
		}

		.lista-viagens .btn-primary {
			color: #fff;
		}

		.lista-viagens .btn-excluir {
			background-color: #f74e4e !important;
			border: 0;
		}

		.lista-viagens .fieldset-border {
			border: 1px groove #45c7d8 !important;
			padding: 0 1.4em 1.4em 1.4em !important;
			margin: 0 0 1.5em 0 !important;
			-webkit-box-shadow: 0px 0px 0px 0px #000;
			box-shadow: 0px 0px 0px 0px #000;
		}

		.lista-viagens .fieldset-border .legend-border {
			font-size: 1.2em !important;
			text-align: left !important;
			width: auto;
			padding: 0 10px;
			border-bottom: none;
		}

		.lista-viagens {
			overflow-y: scroll;
			height: 1000px;
		}


		/*Corrigindo bug da modal excluir 
	Estava dando conflito com esse o css neon-core
	<?php echo $protocolo;?>://www.tinebus.com.br/tinebus/tine-bus-neon/<?php echo $protocolo;?>://www.tinebus.com.br/arquivos/assets/css/neon-core.css
	 */

		/*Fim Css lista de viagens*/


		/*MOBILE*/

		/*ajustando logo*/
		@media (max-width: 767px) {
			.logo img {
				width: 66px;
			}

			.title-viagens h3 {
				font-size: 15px;
				margin-top: 17px;
				margin-bottom: 0;
			}

			/*coloca scroll quando for mobile na lista de passageiros*/
			.scroll_mobile {
				overflow-x: scroll;
			}
		}

		hr {
			margin-top: 0;
			margin-bottom: 0;
			border: 0;
			border-top: 1px solid #eeeeee;
		}

		.btn_add_viagem {
			margin-top: 12px;
			margin-bottom: 8.5px;
		}

		.page-container .sidebar-menu #main-menu li {
			font-size: 14px !important;
		}
	</style>
	<style type="text/css">
		<?php $lista_onibus = "lista-onibus" ?>

		/*Css lista de onibus*/
		.<?php echo $lista_onibus ?> .btn {
			padding: 1px 5px;
		}

		.<?php echo $lista_onibus ?> .btn-editar {
			background-color: #364150 !important;
			border: 0;
		}

		.<?php echo $lista_onibus ?> .btn-primary {
			color: #fff;
		}

		.<?php echo $lista_onibus ?> .btn-excluir {
			background-color: #f74e4e !important;
			border: 0;
		}
	</style>

	<style type="text/css">
		<?php $lista_motorista = "lista-motorista" ?>

		/*Css lista de onibus*/
		.<?php echo $lista_motorista ?> .btn {
			padding: 1px 5px;
		}

		.<?php echo $lista_motorista ?> .btn-editar {
			background-color: #364150 !important;
			border: 0;
		}

		.<?php echo $lista_motorista ?> .btn-primary {
			color: #fff;
		}

		.<?php echo $lista_motorista ?> .btn-excluir {
			background-color: #f74e4e !important;
			border: 0;
		}
	</style>

	<style type="text/css">
		<?php $lista_usuarios = "lista-usuarios" ?>

		/*Css lista de onibus*/
		.<?php echo $lista_usuarios ?> .btn {
			padding: 1px 5px;
		}

		.<?php echo $lista_usuarios ?> .btn-editar {
			background-color: #364150 !important;
			border: 0;
		}

		.<?php echo $lista_usuarios ?> .btn-primary {
			color: #fff;
		}

		.<?php echo $lista_usuarios ?> .btn-excluir {
			background-color: #f74e4e !important;
			border: 0;
		}
	</style>

	<style type="text/css">
		<?php $lista_agencias = "lista-agencias" ?>

		/*Css lista de onibus*/
		.<?php echo $lista_agencias ?> .btn {
			padding: 1px 5px;
		}

		.<?php echo $lista_agencias ?> .btn-editar {
			background-color: #364150 !important;
			border: 0;
		}

		.<?php echo $lista_agencias ?> .btn-primary {
			color: #fff;
		}

		.<?php echo $lista_agencias ?> .btn-excluir {
			background-color: #f74e4e !important;
			border: 0;
		}
	</style>


	<style type="text/css">
		<?php $lista_passageiros = "lista-passageiros" ?>

		/*Css lista de onibus*/
		.<?php echo $lista_passageiros ?> .btn {
			padding: 1px 5px;
		}

		.<?php echo $lista_passageiros ?> .btn-editar {
			background-color: #364150 !important;
			border: 0;
		}

		.<?php echo $lista_passageiros ?> .btn-primary {
			color: #fff;
		}

		.<?php echo $lista_passageiros ?> .btn-excluir {
			background-color: #f74e4e !important;
			border: 0;
		}
	</style>

	<style type="text/css">
		<?php $lista_encomendas = "lista-encomendas" ?>

		/*Css lista de onibus*/
		.<?php echo $lista_encomendas ?> .btn {
			padding: 1px 5px;
		}

		.<?php echo $lista_encomendas ?> .btn-editar {
			background-color: #364150 !important;
			border: 0;
		}

		.<?php echo $lista_encomendas ?> .btn-primary {
			color: #fff;
		}

		.<?php echo $lista_encomendas ?> .btn-excluir {
			background-color: #f74e4e !important;
			border: 0;
		}
	</style>

</head>

<body class="page-body  page-fade" data-url="http://neon.dev">