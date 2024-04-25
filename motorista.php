<?php
Login::verificaSeEstaLogado();

$Title = "Motorista";
$title = "motorista";

require"classes/$Title.class.php";
$obj = new Motorista();

$usuari_perfil  = isset($_SESSION['usuario_perfil']) ? $_SESSION['usuario_perfil'] : "";


	
?>

<div>
	<h3>
		Lista de motoristas
	</h3>
	<br />

	<!-- Adiconar mortoristas-->
	<?php if ($usuari_perfil == 1 || $usuari_perfil == 4 || $usuari_perfil == 6) { // Apenas administrador e master consegui adicionar ?>		
	<div data-toggle="modal" data-target="#myModal<?php echo $Title;?>" id="sample_editable_1_new">
		<a href="javascript: fnClickAddRow();" class="btn btn-primary">
			<i class="entypo-plus"></i>
			Adicionar <?php echo $Title; ?>
		</a>
	</div>
	<?php } else {?>
	<div>
		<a class="btn btn-primary" style="cursor: no-drop" onclick="permissaoExcluir()">
			<i class="entypo-plus"></i>
			Adicionar <?php echo $Title; ?>
		</a>
	</div>	
	<?php } ?>
	<!-- Fim Adiconar mortoristas-->
</div>
<br />
		<div class="row lista-motorista">
			<div class="col-md-12">
				
				<table class="table table-bordered responsive">
					<thead>
						<tr>
        					<th scope="col">ID</th>
        					<th scope="col">Nome</th>
        					<th scope="col">Telefone </th>
        					<th scope="col">Whatsapp</th>
							<th scope="col">Setor</th>
        					<th scope="col">Observação</th>
        					<th colspan="2" style="text-align: center;border: width: 18px;" scope="col">Ações</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($obj->findAll() as $key => $value) {?>
				<tr>
					<th scope="row"><?php echo $value->bus_idMotorista; ?></th>
					<td><?php echo  utf8_decode($value->bus_nome_motorista); ?></td>
					<td><?php echo  $value->bus_telefone_motorista; ?></td>
					<td><?php echo  $value->bus_whatsapp_motorista; ?></td>
					<td><?php echo $value->bus_setor_motorista;?></td>
					<td><?php echo  utf8_decode($value->bus_observacao);?></td>
					

					<!-- Alteração-->		
				    <?php if ($usuari_perfil == 1 || $usuari_perfil == 4 || $usuari_perfil == 6) { // Apenas administrador consegui alterar?>
					<td style="width: 18px;">
						<a href="javascript:"  data-toggle="modal" data-target="#myModal<?php echo $Title?>Editar" id="sample_editable_3_new" onclick="passaId<?php echo $Title?>(<?php echo $value->bus_idMotorista;?>)"  class="btn btn-editar btn-primary "><i class="fas fa-pencil-alt"></i>
						</a>
					</td>
					<?php } else { //mostra opção mas não consegui alterar nada?>
					<td style="width: 18px;">
						<a href="javascript:"  class="btn btn-editar btn-primary" style="cursor: no-drop" onclick="permissaoExcluir()">
						<i class="fas fa-pencil-alt"></i>
						</a>
					</td>
					<?php }?>
					<!-- Fim Alteração-->

					<!-- Exclusão-->
					<?php if ($usuari_perfil == 1 || $usuari_perfil == 4 || $usuari_perfil == 6) { // Apenas administrador consegui alterar?>	
					<td style="width: 40px;">
						<a class="delete_<?php echo $title;?>"  data-id="<?php echo isset($value->bus_idMotorista) ? $value->bus_idMotorista : ''; ?>" href="javascript:void(0)">
							<button  class="btn btn-excluir btn-primary" value="motorista_exclui">
								<i class="far fa-trash-alt"></i>
							</button>
						</a>
					</td>
					<?php } else {?>
						<td style="width: 40px;">
						<a href="javascript:void(0)" style="cursor: no-drop" onclick="permissaoExcluir()">
							<button  class="btn btn-excluir btn-primary">
								<i class="far fa-trash-alt"></i>
							</button>
						</a>
					</td>

					<?php } ?>
				</tr>
				<?php } //fim do foreach ?>

					</tbody>
				</table>
				
			</div>
		</div>

<?php include_once "modais/motorista/modal_cadastrar.php"; ?>
<?php include_once "modais/motorista/modal_editar.php"; ?>

<script type="text/javascript">
	function passaIdMotorista (id) {
			$.ajax({
				type: 'POST',
				url: 'ajax/<?php echo $title?>/edita_<?php echo $title?>_ajax.php',
				data: {id: id},
				success: function (data) {
					var res = data.split("|");

					$("#id_edit_motorista").val(res[0]);
					$("#nome_motorista_edit").val(res[1]);
					$("#telefone_motorista_edit").val(res[2]);
					$("#whatsapp_motorista_edit").val(res[3]);
					$("#bus_setor_motorista_edite").val(res[4]);
					$("#observacao_edit_motorista").val(res[5]);
					
				}
			});
		}
</script>