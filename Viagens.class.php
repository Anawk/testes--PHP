<?php

require_once("DB.class.php");
date_default_timezone_set('America/Sao_Paulo');

class Viagens
{

	public $dt_atual;
	public $hora_atual;
	public static $tablename = "bus_viagens";
	public static $tableid = "bus_idViagem";
	public static $title = "viagens";

	public function __construct()
	{
		//public static $hora_atua = date('H:i:s');
		$this->dt_atual = date('Y-m-d');
		$this->hora_atual = date('H:i:s');
		$this->dt_atualMenos1Dias = date('Y-m-d', strtotime("-3 day"));
	}

	public function add($dados)
	{

		$dtaPartida = $this->formataData($dados['dt_partida']);
		//$dtaRetorno = $this->formataData($dados['dt_retorno']);

		//$this->p($dados); die;
		DB::getInstance()->beginTransaction();

		try {
			$dados_cadastro = array(
				'origem' => "teste",
				'destino' => "teste",
				'motorista1' => $dados['motorista1'],
				'motorista2' => $dados['motorista2'],
				'onibus' => $dados['onibus'],
				'dt_partida' => $dtaPartida,
				'dt_retorno' => null,
				'observacao' => $dados['observacao'],
				'dt_cadastro' => $this->dt_atual,
				'hora_cadastro' => $this->hora_atual,
				'bus_origem_estado' => "tt",
				'bus_destino_estado' => "tt",
				'plataforma_viagem' => $dados['plataforma_viagem'],
				'hora_viagem' => $dados['hora_viagem'],
				'bus_km_inicial' => $dados['bus_km_inicial'],
				'bus_km_final' => $dados['bus_km_final'],

			);

			//$this->p($dados); die;
			// Inseri a viagem
			$insert_dados = DB::prepare(
				"INSERT INTO " . self::$tablename . "
				(  
				origem, destino, motorista1, motorista2, onibus, dt_partida, dt_retorno, observacao, dt_cadastro, hora_cadastro, bus_origem_estado, bus_destino_estado, plataforma_viagem, hora_viagem, bus_km_inicial, bus_km_final
				)
				VALUES
				(   
				:origem, :destino, :motorista1, :motorista2, :onibus, :dt_partida , :dt_retorno , :observacao , :dt_cadastro, :hora_cadastro , :bus_origem_estado , :bus_destino_estado , :plataforma_viagem , :hora_viagem, :bus_km_inicial, :bus_km_final 
				)
				"
			)->execute($dados_cadastro);




			// Cadastro de motorista relacionado com a viagem
			$id = DB::getInstance()->lastInsertId(); // Pego o id da ultima viagem
			$dados_cadastroMotoristaViagens = array(
				'idViagem' => $id,
				'idMotorista1' => $dados['motorista1'],
				'idMotorista2' => $dados['motorista2'],
			);

			$insert_dados_motorista_viagens = DB::prepare("INSERT INTO motorista_viagens (bus_idViagem, bus_idMotorista1, bus_idMotorista2) 
				VALUES (:idViagem, :idMotorista1, :idMotorista2)")->execute($dados_cadastroMotoristaViagens);

			// Cadastro de onibus relacionado com a viagem
			//$bus_idViagem = DB::getInstance()->lastInsertId(); // Pego o id da ultima viagem
			$dados_cadastroViagensOnibus = array(
				'bus_idViagem' => $id,
				'bus_idOnibus' => $dados['onibus'],
			);

			$insert_dados_onibus_viagens = DB::prepare("INSERT INTO bus_viagem_onibus (bus_idViagem, bus_idOnibus) 
				VALUES (:bus_idViagem, :bus_idOnibus)")->execute($dados_cadastroViagensOnibus);




			// LINHA CADASTRANDO 

			$dados_linha = array(
				'bus_idViagem' => $id,
				'bus_idLinha' => $dados['linhas'],
			);

			$insert_dados_onibus_viagens = DB::prepare("INSERT INTO bus_viagens_linhas (bus_idViagem, bus_idLinha) 
				VALUES (:bus_idViagem, :bus_idLinha)")->execute($dados_linha);

			// LINHA FIM CADASTRANDO 


			//BLOQUEANDO POLTRONAS


			$dados_bloqueio = array(
				'bus_idViagem' => $id,
				'bus_poltrona' => $dados['bus_bloqueio_viagens_poltronas'],
				'usuario_id' => $_SESSION['usuario_id']

			);

			$insert_dados_bloqueio = DB::prepare("INSERT INTO bus_bloqueio_viagens_poltronas (bus_idViagem, bus_poltrona, usuario_id) 
				VALUES (:bus_idViagem, :bus_poltrona, :usuario_id)")->execute($dados_bloqueio);

			//FIM BLOQUEIO POLTRONAS

			// ADD VIAGENS GUIAS 
			
			$dados_guia= array(
				'bus_idViagem' => $id,
				'bus_usuario_guia' =>$dados['bus_usuario_guia'],
			);

			DB::prepare("INSERT INTO bus_viagens_usuarios (bus_idViagem, usuario_id) 
				VALUES (:bus_idViagem, :bus_usuario_guia)")->execute($dados_guia);

			DB::getInstance()->commit();
			return true;
		} catch (Exception $e) {
			DB::getInstance()->rollBack();
			//echo 'Graph returned an error: ' . $e->getMessage(); exit;			
			header("Location: ../../index.php?page=" . self::$title . "");
		}
	}

	public function update($dados)
	{
		//echo "<pre>"; print_r($dados); die;

		$dtaPartida = $this->formataData($dados['dt_partida']);
		//$dtaRetorno = $this->formataData($dados['dt_retorno']);
		$dtaRetorno = "0000-00-00";

		DB::getInstance()->beginTransaction();

		$valores = array(
			"origem = :origem, ",
			"destino = :destino, ",
			"motorista1 = :motorista1,",
			"motorista2 = :motorista2,",
			"onibus = :onibus,",
			"dt_partida = :dt_partida,",
			"dt_retorno = :dt_retorno,",
			"observacao = :observacao,",
			"dt_cadastro = :dt_cadastro,",
			"hora_cadastro = :hora_cadastro,",
			"bus_origem_estado = :bus_origem_estado,",
			"bus_destino_estado = :bus_destino_estado,",
			"plataforma_viagem = :plataforma_viagem,",
			"hora_viagem = :hora_viagem,",
			"bus_km_inicial = :bus_km_inicial,",
			"bus_km_final = :bus_km_final"


		);
		$valores = implode($valores);

		$array1 = array(
			':id' => $dados['id'],
			':origem' => 'teste',
			':destino' => 'teste',
			':motorista1' => $dados['motorista1'],
			':motorista2' => $dados['motorista2'],
			':onibus' => $dados['onibus'],
			':dt_partida' => $dtaPartida,
			':dt_retorno' => null,
			':observacao' => $dados['observacao'],
			':dt_cadastro' => $this->dt_atual,
			':hora_cadastro' => $this->hora_atual,
			':bus_origem_estado' => 'teste',
			':bus_destino_estado' => 'teste',
			':plataforma_viagem' => $dados['plataforma_viagem'],
			':hora_viagem' => $dados['hora_viagem'],
			':bus_km_inicial' => $dados['bus_km_inicial'],
			':bus_km_final' => $dados['bus_km_final'],


			
		);

		//$this->p($array1);
		//die('oi');

		$pdo = new DB();
		try {

			//$sql = "UPDATE onibus SET bus_nome_onibus = :nome, bus_rg_onibus = :rg, bus_postrona_onibus = :poltrona  WHERE bus_idOnibus= :id";
			$sql = "UPDATE "  . self::$tablename . " SET {$valores}  WHERE " . self::$tableid . " = :id";
			//$sql = "UPDATE "  .self::$tablename. " SET {$valores}  WHERE " .self::$tableid. " = :id";
			$stmt = DB::prepare($sql);
			$stmt->execute($array1);


			$sql = "UPDATE motorista_viagens SET bus_idMotorista1 = :bus_idMotorista1, bus_idMotorista2 = :bus_idMotorista2 WHERE bus_idViagem = :id";
			$stmt = DB::prepare($sql);

			$stmt->execute(array(
				':id' => $dados['id'],
				':bus_idMotorista1' => $dados['motorista1'],
				':bus_idMotorista2' => $dados['motorista2'],
			));


			$sql = "UPDATE bus_viagem_onibus SET bus_idOnibus = :bus_idOnibus WHERE bus_idViagem = :id";
			$stmt = DB::prepare($sql);

			$stmt->execute(array(
				':id' => $dados['id'],
				':bus_idOnibus' => $dados['onibus'],
			));
			/*

			$insert_dados_onibus_viagens = DB::prepare("INSERT INTO bus_viagem_onibus (bus_idViagem, bus_idOnibus) 
				VALUES (:bus_idViagem, :bus_idOnibus)")->execute($dados_cadastroViagensOnibus);
			if(!$insert_dados_onibus_viagens){
				die('erro');
			}
			*/
			// LINHA CADASTRANDO 

			$sql = "UPDATE bus_viagens_linhas SET bus_idLinha = :bus_idLinha WHERE bus_idViagem = :id";
			$stmt = DB::prepare($sql);

			$stmt->execute(array(
				':id' => $dados['id'],
				':bus_idLinha' => $dados['linhas_edite']
			));

			//$sql = "Select * ";

			$sql_blo = "SELECT  `bus_idBlockPolt` FROM `bus_bloqueio_viagens_poltronas` WHERE bus_idViagem = :idblo";

			$stmt_blo = DB::prepare($sql_blo);
			$stmt_blo->bindValue(':idblo', $dados['id'], PDO::PARAM_INT);
			$stmt_blo->execute();
			//$linhablo = $stmt_blo->fetch(PDO::FETCH_ASSOC);

			//echo "<pre>"; print_r($linhablo); die;

			if ($stmt_blo->rowCount() > 0) {

				$sql = "UPDATE bus_bloqueio_viagens_poltronas SET bus_poltrona = :bus_poltrona, usuario_id = :usuario_id WHERE bus_idViagem = :id";
				$stmt = DB::prepare($sql);

				$stmt->execute(array(
					':id' => $dados['id'],
					':bus_poltrona' => $dados['bus_bloqueio_viagens_poltronas'],
					':usuario_id' => $_SESSION['usuario_id']
				));
			} else {




				$dados_bloqueio = array(
					'bus_idViagem' => $dados['id'],
					'bus_poltrona' => $dados['bus_bloqueio_viagens_poltronas'],
					'usuario_id' => $_SESSION['usuario_id']

				);

				$insert_dados_bloqueio = DB::prepare("INSERT INTO bus_bloqueio_viagens_poltronas (bus_idViagem, bus_poltrona, usuario_id) 
					VALUES (:bus_idViagem, :bus_poltrona, :usuario_id)")->execute($dados_bloqueio);
			}

			// ADD GUIA
			$sql = "UPDATE bus_viagens_usuarios SET usuario_id = :bus_usuario_guia WHERE bus_idViagem = :bus_idViagem";
			$stmt_guia = DB::prepare($sql);

			$guiaUser = $stmt_guia->execute(array(
				':bus_idViagem' => $dados['id'],
				':bus_usuario_guia' => $dados['bus_usuario_guia']
				
			));


			if(!$guiaUser){
				die("Erro");
			}

			// LINHA FIM CADASTRANDO 

			DB::getInstance()->commit();
			return true;
		} catch (PDOException $e) {
			//echo 'Error: ' . $e->getMessage();
			DB::getInstance()->rollBack();
			return false;
		}
	}

	public function find($id)
	{
		$pdo = new DB();
		$linha = 0;
		$sql = "SELECT * FROM " . self::$tablename . " where " . self::$tableid . " = :id;";

		$stmt = DB::prepare($sql);
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$linha = $stmt->fetch(PDO::FETCH_ASSOC);

		return $linha;
	}

	public function findViagensLinhas($id)
	{
		$pdo = new DB();
		$linha = 0;
		// $sql = "SELECT * FROM ".self::$tablename."  AS bv JOIN bus_viagens_linhas AS bvl 
		// ON bv.bus_idViagem = bvl.bus_idViagem where bv.bus_idViagem = :id;";

		$sql = "SELECT * , bv.bus_idViagem as id_viageem FROM " . self::$tablename . "  AS bv 
		JOIN bus_viagens_linhas AS bvl 
		ON bv.bus_idViagem = bvl.bus_idViagem 
		LEFT JOIN bus_bloqueio_viagens_poltronas AS bbvp 
		ON bv.bus_idViagem = bbvp.bus_idViagem 

		LEFT JOIN bus_viagens_usuarios AS bvu
		ON bvu.bus_idViagem = bbvp.bus_idViagem 
		
		where bv.bus_idViagem = :id;
		";

		$stmt = DB::prepare($sql);
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$linha = $stmt->fetch(PDO::FETCH_ASSOC);

		//echo "<pre>"; print_r($linha); die;

		return $linha;
	}


	public function findAllOriDes($id)
	{
		$pdo = new DB();
		$linha = 0;
		$sql = "SELECT bl.*, bv.*  FROM `bus_linhas` AS bl

		JOIN   bus_viagens_linhas AS bvl
		  ON bl.bus_idLinha = bvl.bus_idLinha
		  
		JOIN bus_viagens AS bv
		ON bv.bus_idViagem = bvl.bus_idViagem
		  
		  WHERE bvl.bus_idViagem  = :id";

		$stmt = DB::prepare($sql);
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$linha = $stmt->fetch(PDO::FETCH_ASSOC);

		return $linha;
	}

	//Pega a viagem de acordo com o onibus	
	public function getOnibusByViagem($id)
	{
		$pdo = new DB();
		$linha = 0;

		$sql = "SELECT 
		bus_qtd_poltrona
		FROM
		bus_onibus as bo
		JOIN bus_viagem_onibus as bvo
		ON bo.bus_idOnibus = bvo.bus_idOnibus
		WHERE bvo.bus_idViagem =  :id";
		$stmt = DB::prepare($sql);
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$linha = $stmt->fetch(PDO::FETCH_ASSOC);

		return $linha;
	}

	public function findAll()
	{
		$sql = "SELECT * FROM " . self::$tablename . "";
		$stmt = DB::prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function findAllCidadesOrigensAndDestinos()
	{
		$sql = "SELECT * FROM bus_estados_origens_destinhos";
		$stmt = DB::prepare($sql);
		$stmt->execute();
		foreach ($stmt->fetchAll() as $ke => $value) {
			$res[] = $value;
		}
		return $res;
	}
	public function findAllLista($dataInicio = null, $dataFim = null)
	{


		$where = " Where dt_partida >= " . $this->dt_atualMenos1Dias;
		if (isset($dataInicio) && $dataInicio != "") {
			$ex = explode("/", $dataInicio);
			$daFormIni = $ex[2] . "-" . $ex[1] . "-" . $ex[0];
			$where = " Where dt_partida >= '$daFormIni'";
		}
		//echo $daFormIni;
		$sql = "SELECT 
		bus_idViagem,
		(select bus_nome_origens_destinos FROM bus_estados_origens_destinhos WHERE bus_estado_origens_destinos = origem limit 1) as origem,
		(select bus_nome_origens_destinos FROM bus_estados_origens_destinhos WHERE bus_estado_origens_destinos = destino limit 1) as destino,
		(select bus_nome_motorista FROM bus_motorista WHERE bus_idMotorista = motorista1 limit 1) as motorista1,
		(select bus_nome_motorista FROM bus_motorista WHERE bus_idMotorista = motorista2 limit 1) as motorista2,
		(select CONCAT(bus_numero,'-', bus_modelo,' (', bus_qtd_poltrona, ' Lugares)') FROM bus_onibus WHERE bus_idOnibus = onibus ) as onibus,
		dt_partida,
		dt_retorno,
		hora_viagem,
		plataforma_viagem,
		observacao,
		dt_cadastro,
		hora_cadastro,
		bus_origem_estado,
		bus_destino_estado";

		$sql .= " FROM bus_viagens ";
		$sql .= $where;
		$sql .= " order by dt_partida";

		/*FROM ". self::$tablename ." WHERE dt_partida >= '$this->dt_atualMenos1Dias' ||  dt_partida = '2019-01-06' order by dt_partida";*/

		$stmt = DB::prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function findAllListaLinhasRotas($dataInicio = null, $dataFim = null)
	{


		$where = " Where bv.dt_partida >= " . $this->dt_atualMenos1Dias;
		if (isset($dataInicio) && $dataInicio != "") {
			$ex = explode("/", $dataInicio);
			$daFormIni = $ex[2] . "-" . $ex[1] . "-" . $ex[0];
			$where = " Where bv.dt_partida >= '$daFormIni'";
		}
		//echo $daFormIni;
		$sql = "SELECT 
				bv.bus_idViagem,
			bv.dt_partida,
			bl.bus_estado_origem,
			bl.bus_cidade_origem,
			bl.bus_estado_destino,
			bl.bus_cidade_destino,
			bvu.usuario_id,
			bv.bus_km_inicial,
			bv.bus_km_final,
			(select bus_nome_motorista FROM bus_motorista WHERE bus_idMotorista = bv.motorista1 limit 1) as motorista1,
			(select bus_nome_motorista FROM bus_motorista WHERE bus_idMotorista = bv.motorista2 limit 1) as motorista2,
		
			(select CONCAT(bus_numero,'-', bus_modelo,' (', bus_qtd_poltrona, ' Lugares)') FROM bus_onibus WHERE bus_idOnibus =  bv.onibus ) as onibus,
			bv.plataforma_viagem ,
			bv.hora_viagem , bv.observacao ";

		$sql .= " FROM bus_viagens_linhas AS bvl";

		$sql .= " JOIN bus_viagens AS bv ON bv.bus_idViagem = bvl.bus_idViagem ";
		$sql .= " JOIN bus_linhas AS bl ON bl.bus_idLinha = bvl.bus_idLinha ";
		$sql .= " LEFT JOIN bus_viagens_usuarios AS bvu ON bvu.bus_idViagem = bv.bus_idViagem ";

		$sql .= $where;
		$sql .= " order by bv.dt_partida";

		/*FROM ". self::$tablename ." WHERE dt_partida >= '$this->dt_atualMenos1Dias' ||  dt_partida = '2019-01-06' order by dt_partida";*/

		$stmt = DB::prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	// Pega o total de viagens registras apenas esse mês
	public function findAllListaMesAtual()
	{
		$sql = "SELECT 
		count(bus_idViagem) as totalViagens
		FROM " . self::$tablename . "  WHERE MONTH(`dt_partida`) = MONTH(CURRENT_DATE()) AND YEAR(`dt_partida`) = YEAR(CURRENT_DATE())";
		$stmt = DB::prepare($sql);
		$stmt->execute();
		return $linha = $stmt->fetch(PDO::FETCH_ASSOC);
	}



	//Pega a apolice de acordo com o onibus	
	public function getApoliceByOnibus($idViagem)
	{
		$pdo = new DB();
		$linha = 0;

		$sql = "SELECT 
			bo.apolice_bus
            FROM
			bus_onibus as bo
			LEFT JOIN bus_viagem_onibus as bvo
			ON bo.bus_idOnibus = bvo.bus_idOnibus          
			WHERE bvo.bus_idViagem =  :id";
		$stmt = DB::prepare($sql);
		$stmt->bindValue(':id', $idViagem, PDO::PARAM_INT);
		$stmt->execute();
		$linha = $stmt->fetch(PDO::FETCH_ASSOC);

		return $linha;
	}

	public function excluir($id)
	{
		$pdo = new DB();
		DB::getInstance()->beginTransaction();
		try {



			$verificaSePossuiPassageiroNessaViagem = $this->getPassageiroByViagem($id);
			if ($verificaSePossuiPassageiroNessaViagem) {
				return false;
			}

			try {
				// Exclui os motorista relacionados a essa viagem
				$sql = "DELETE FROM motorista_viagens WHERE bus_idViagem = :id";
				$stmt1 = DB::prepare($sql);
				$stmt1->bindParam(':id', $id);
				$stmt1->execute();
			} catch (PDOExceurption $e) {
				return false;
			}

			try {
				// Exclui os onibus relacionados a essa viagem
				$sql = "DELETE FROM bus_viagem_onibus WHERE bus_idViagem = :id";
				$stmt2 = DB::prepare($sql);
				$stmt2->bindParam(':id', $id);
				$stmt2->execute();
			} catch (PDOExceurption $e) {
				return false;
			}

			try {
				// Exclui os valores relacionados a essa viagem
				$sql = "DELETE FROM passagens_valores_viagens WHERE bus_id_viag = :id";
				$stmtVa = DB::prepare($sql);
				$stmtVa->bindParam(':id', $id);
				$stmtVa->execute();
			} catch (PDOExceurption $e) {
				return false;
			}

			try {
				// Exclui o Guia da viagem
				$sql = "DELETE FROM bus_viagens_usuarios WHERE bus_idViagem = :id";
				$stmtLinha = DB::prepare($sql);
				$stmtLinha->bindParam(':id', $id);	
				$stmtLinha->execute();	
				
			} catch (PDOException $e) { 
				return false;
			}

			try {
				// Exclui a linha relaciona da viagem
				$sql = "DELETE FROM bus_viagens_linhas WHERE bus_idViagem = :id";
				$stmtLinha = DB::prepare($sql);
				$stmtLinha->bindParam(':id', $id);
				$stmtLinha->execute();
			} catch (PDOExceurption $e) {
				return false;
			}

			try {
				// Exclui a viagem
				// Só exclui uma viagem caso não tenha passageiro vinculado a essa viagem na tabela passageiros_viagens
				// Ou seja primeiro exclui os passageiros dessas viagem para conseguir excluir essa viagem
				$sql = "DELETE FROM " . self::$tablename . " WHERE " . self::$tableid . "  
				= :id";
				$stmt3 = DB::prepare($sql);
				$stmt3->bindParam(':id', $id);
				$stmt3->execute();
			} catch (PDOExceurption $e) {
				return false;
			}

			DB::getInstance()->commit();
			return true;
		} catch (Exception $e) {
			DB::getInstance()->rollBack();
			return false;
		}
	}


	//Verifica se existe passageiro nessa viagem
	public function getPassageiroByViagem($id)
	{

		$sql = "SELECT 
		bus_idPassageirosViagens
		FROM
		passageiros_viagens AS bp
		JOIN bus_viagens AS bv    
		ON bv.bus_idViagem = bp.bus_idViagem

		WHERE bv.bus_idViagem = :id";

		$stmt = DB::prepare($sql);
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		//$linha = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($stmt->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function login($dados)
	{


		if (isset($dados['usuario_login'])) {
			$usuario_login = $dados['usuario_login'];
		}
		if (isset($dados['usuario_senha'])) {
			$usuario_senha = $dados['usuario_senha'];
		}


		try {

			$sql = "SELECT 
			us.usuario_nome, us.usuario_login, us.usuario_senha
			FROM
			bus_usuario as us
			WHERE  us.usuario_login = :usuario_login AND us.usuario_senha = :usuario_senha";


			// $stmt = DB::prepare("Select * from bus_usuario where usuario_login = :usuario_login");
			//$stmt->bindValue(':usuario_login', "tiago");
			$stmt = DB::prepare($sql);


			$param = array(
				":usuario_login" => $usuario_login,
				":usuario_senha" => $usuario_senha
			);

			$stmt->execute($param);


			if ($stmt->rowCount() > 0) {
				//return $stmt->fetch(PDO::FETCH_ASSOC);
				// return $stmt->fetchAll();
				return $stmt->fetchall(PDO::FETCH_ASSOC);
			} else {
				return false;
			}
		} catch (PDOException $ex) {
			echo "Erro " . $ex->getMessage();
		}
	}


	public function formataData($data)
	{

		$data = explode("/", $data);
		$data = $data[2] . "-" . $data[1] . "-" . $data[0];
		return $data;
	}

	public function formataDataEdicao($data)
	{
		$data = explode("-", $data);
		$dt = $data[2] . "/" . $data[1] . "/" . $data[0];
		return $dt;
	}

	static public function p($valor)
	{

		echo "<pre>";
		print_r($valor);
	}
}
