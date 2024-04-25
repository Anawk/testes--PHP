<?php include "top.php"; ?>
<div class="page-container">
	<!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->

	<?php include "sidebar.php"; ?>

	<div class="main-content">

		<?php


		// echo "<pre>";
		// print_r($objPagamento->PagamentoStatus());
		// die;


		$diaAtul = date("d"); //Pego dia atual
		$pagamento = true;
		//$diaAtul = 11;
		if (isset($_SESSION['logged_success'])) {
			//$numerosLimitesParaPagamento = array("11","12","13", "14", "16", "17", "18", "19", "20");

			if (count($objPagamento->PagamentoStatus()) > 0) {
				if ($objPagamento->PagamentoStatus()[0]->bus_status_pagamento == 0) {
					//if(in_array($diaAtul, $numerosLimitesParaPagamento)){
		?>
					<!-- Aviso de pagamento -->
					<div class="alert alert-danger alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<strong>Aviso!</strong> <?php echo  $objPagamento->PagamentoStatus()[0]->bus_descricao_aviso; ?>

					</div>
					<!-- Fim aviso de pagamento -->

					<?php
					//não mostra bloqueio para usuário master
					if ($objPagamento->PagamentoStatus()[0]->bus_status_bloqueio == 1 && $_SESSION['usuario_perfil'] != 4) {  ?>

						<script>
							alert('Até o momento não identificamos o pagamento. Entre em contato com o nosso financeiro - O sistema está bloqueado temporariamente')
						</script>

		<?php

						die;
					}
				}
			}
		}
		unset($_SESSION['logged_success']);
		?>
		<div class="row" id="Tiradoimprimir">
			<!-- Profile Info and Notifications -->
			<div class="col-md-6 col-sm-8 clearfix">
				<ul class="user-info pull-left pull-none-xsm">
					<!-- Profile Info -->
					<li class="profile-info dropdown">
						<!-- add class "pull-right" if you want to place this from right -->
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<!--<img src="assets/images/thumb-1@2x.png" alt="" class="img-circle" width="44" />-->
							<?php if ($_SESSION['usuario_id'] == 19 && $_SESSION['usuario_login'] == 'daniel') { // Se o usuário for igual daniel 
							?>
								<img src="images/84a8625a-3fc8-4b36-b737-cea48b268107.jpg" alt="" style="border-radius: 24%;" class="img-circle" width="44" />
							<?php } ?>
							<!--<i style="color:#b4bcc8" class="fas fa-user-circle fa-2x"></i>-->
							<?php echo  $_SESSION['usuario_nome']; ?>
						</a>
					</li>
				</ul>
			</div>
			<!-- Raw Links -->
			<div class="col-md-6 col-sm-4 clearfix hidden-xs">
				<ul class="list-inline links-list pull-right">
					<li>
						<a href="logout.php">
							Sair <i class="entypo-logout right"></i>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<hr />
		<?php
		if (isset($_GET['page']) && $_GET['page'] != "") {
			if ($_GET['page'] == "onibus") {
				include "onibus.php";
			} elseif ($_GET['page'] == "motorista") {
				include "motorista.php";
			} elseif ($_GET['page'] == "passageiros") {
				include "passageiros.php";
			} elseif ($_GET['page'] == "viagens-calendarios") {
				include "viagens-calendarios.php";
			} elseif ($_GET['page'] == "viagens-lista") {

				include "viagens-lista.php";
			} elseif ($_GET['page'] == "lista-clientes") {
				include "lista-clientes.php";
			} elseif ($_GET['page'] == "usuarios") {
				include "usuarios.php";
			} elseif ($_GET['page']  == "agencias") {

				include "agencias.php";
			} elseif ($_GET['page']  == "encomendas") {

				include "encomendas.php";
			} elseif ($_GET['page']  == "linhas_rotas") {
				include "linhas_rotas.php";
			} elseif ($_GET['page']  == "vendas") {
				include "vendas.php";
			} elseif ($_GET['page']  == "lista-comissao") {
				include __DIR__ . '../../../global/modulos/relatorios/comissao/lista-comissao.php';
			} elseif ($_GET['page']  == "vendas-comissao") {
				include __DIR__ . '../../../global/modulos/relatorios/comissao/vendas-comissao.php';
			} elseif ($_GET['page']  == "lista-agenciadores") {
				include __DIR__ . '../../../global/modulos/relatorios/agenciadores/lista-agenciadores.php';
			} elseif ($_GET['page']  == "vendas-agenciadores") {
				include __DIR__ . '../../../global/modulos/relatorios/agenciadores/vendas-agenciadores.php';
			} elseif ($_GET['page']  == "lancamentos") {
				include "lancamentos.php";
			} elseif ($_GET['page']  == "pagamento") {
				include __DIR__ . '../../../global/modulos/pagamento/status/pagamento.php';

			} elseif ($_GET['page']  == "monitor_lista_atividades") {
				include __DIR__ . '../../../global/modulos/monitoramento/monitor_lista_atividades.php';
			} elseif ($_GET['page']  == "monitor_entrada_usuarios") {
				include __DIR__ . '../../../global/modulos/monitoramento/monitor_entrada_usuarios.php';
			} else {
				include "visao-geral.php";
			}
		} else {
			include "visao-geral.php";
		}
		?>
		<br />
		<?php include "footer.php"; ?>