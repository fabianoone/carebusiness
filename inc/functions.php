<?php 
include __DIR__ . '/db.php';

// Inserir a nota fiscal no banco de dados
function salva_nota_fiscal($dados) {
	$dados_nota = trata_xml($dados);
	$emissor = null;       // Emissor
	$destinatario = null; // Destinatario

	if ( $dados_nota ) {
		$nome_emissor = $dados_nota->NFe->infNFe->emit->xNome . ' | ' . $dados_nota->NFe->infNFe->emit->xFant;
		$tipo_usuario = 'pj';
		$email_emissor = $dados_nota->NFe->infNFe->emit->email;
		$cnpj = $dados_nota->NFe->infNFe->emit->CNPJ;
		$emissor_id = null;
		$destinatario_id = null;
		$nota_fiscal_id = null;
		
		$emissor = array_merge(
			array(
				'nome' => $nome_emissor,
				'tipo' => $tipo_usuario,
				'email' => $email_emissor,
			)
		);
	
		// verfica se o cnpj já existe
		$cnpj = verifica_cnpj_banco($cnpj);
		
		// Cadastra emissor caso não exista e retorna o ID do emissor
		if (! $cnpj) {
			$emissor_id = insert_emissor($emissor);
		}
		
		// Inserir endereço e doc do emissor
		if ($emissor_id) {
			// Cadastra doc do emissor
			$doc_emissor = array(
				'id_usuario' => $emissor_id,
				'cnpj' => $dados_nota->NFe->infNFe->emit->CNPJ,
				'im' => $dados_nota->NFe->infNFe->emit->IM,
				'ie' => $dados_nota->NFe->infNFe->emit->IE,
				'crt'=> $dados_nota->NFe->infNFe->emit->CRT,
			);

			// Retorna id do doc do emissor
			$doc_id = insert_doc_emissor($doc_emissor);			

			// Endereço do emissor
			$endereco_emissor = null;

			$emit_end = $dados_nota->NFe->infNFe->emit->enderEmit->xLgr . ', '
				. $dados_nota->NFe->infNFe->emit->enderEmit->nro
			;
			$endereco_emissor = array_merge(
				array(
					'id_usuario' => $emissor_id,
					'logradouro' => $emit_end,
					'bairro' => $dados_nota->NFe->infNFe->emit->enderEmit->xBairro,
					'cep' => $dados_nota->NFe->infNFe->emit->enderEmit->CEP,
					'municipio' => $dados_nota->NFe->infNFe->emit->enderEmit->xMun,
					'uf' => $dados_nota->NFe->infNFe->emit->enderEmit->UF,
					'pais'   => $dados_nota->NFe->infNFe->emit->enderEmit->cPais,
				)
			);

			$end_emissor_id = insert_endereco_emissor($endereco_emissor);
		}

		// Destinatario
		$nome_destinatario = $dados_nota->NFe->infNFe->dest->xNome;
		$tipo_destinatario = 'pf';
		$email_destinatario = $dados_nota->NFe->infNFe->dest->email;
		
		$cpf_destinatario = $dados_nota->NFe->infNFe->dest->CPF;

		$destinatario = array_merge(
			array(
				'nome' => $nome_destinatario,
				'tipo' => $tipo_destinatario,
				'email' => $email_destinatario,
			)
		);
		
		// verfica se o cpf já existe
		$cpf_destinatario = verifica_cpf_banco($cpf_destinatario);
		
		// Cadastra destinatario caso não exista e retorna o ID do destinatario
		if (! $cpf_destinatario) {
			$destinatario_id = insert_destinatario($destinatario);
		}

		// Inserir endereço e doc do destinatario
		if ($destinatario_id) {
			// Cadastra doc do destinatario
			$doc_destinatario = array(
				'id_usuario' => $destinatario_id,
				'cpf' => $dados_nota->NFe->infNFe->dest->CPF,
			);
			
			// Retorna id do doc do destinatario
			$doc_id = insert_doc_destinatario($doc_destinatario);

			// Endereço do destinatario
			$endereco_destinatario = null;

			$dest_end = $dados_nota->NFe->infNFe->dest->enderDest->xLgr . ', '
				. $dados_nota->NFe->infNFe->dest->enderDest->nro . ', '
				. $dados_nota->NFe->infNFe->dest->enderDest->xCpl
			;
			$endereco_destinatario = array_merge(
				array(
					'id_usuario' => $destinatario_id,
					'logradouro' => $dest_end,
					'bairro' => $dados_nota->NFe->infNFe->dest->enderDest->xBairro,
					'cep' => $dados_nota->NFe->infNFe->dest->enderDest->CEP,
					'municipio' => $dados_nota->NFe->infNFe->dest->enderDest->xMun,
					'uf' => $dados_nota->NFe->infNFe->dest->enderDest->UF,
					'pais'   => $dados_nota->NFe->infNFe->dest->enderDest->cPais,
				)
			);

			$end_destinatario_id = insert_endereco_destinatario($endereco_destinatario);
		}

		// Produto
		$produto = array(
			'id_usuario' => $destinatario_id,
			'nome' => $dados_nota->NFe->infNFe->det->prod->xProd,
			'codigo' => $dados_nota->NFe->infNFe->det->prod->cProd,
			'ncm' => $dados_nota->NFe->infNFe->det->prod->NCM,
			'cfop' => $dados_nota->NFe->infNFe->det->prod->CFOP,
			'unidade' => $dados_nota->NFe->infNFe->det->prod->uCom,
			'quantidade' => $dados_nota->NFe->infNFe->det->prod->qCom,
			'valor_unitario' => $dados_nota->NFe->infNFe->det->prod->vUnCom,
			'valor_total' => $dados_nota->NFe->infNFe->det->prod->vProd,
		);

		// Inserir produto
		$produto_id = insert_produto($produto);

		// Cria a Nota Fiscal
		$nota_fiscal = array(
			'numero_nota ' => $dados_nota->NFe->infNFe->ide->nNF,
			'data_emissao' => $dados_nota->NFe->infNFe->ide->dhEmi,
			'valor_total' => $dados_nota->NFe->infNFe->total->ICMSTot->vNF,
			'emitente_id' => $emissor_id,
			'destinatario_id' => $destinatario_id,
		);
		
		// Inserir nota fiscal
		$nota_fiscal_id = insert_nota_fiscal($nota_fiscal);

		if ( $nota_fiscal_id ) {
			// Atualiza o produto com o id da nota fiscal
			$produto_id = update_produto_num_nota($produto_id, $nota_fiscal_id);
		}

		echo json_encode([
			'status' => 'success',
			'message' => 'Nota Fiscal de no. '. $nota_fiscal_id .' cadastrada com sucesso!',
			'nota_fiscal_id' => $nota_fiscal_id,
		]);
		
	} // end dados da nota
	
}

// trata arquivo XML
function trata_xml($fileNota) {
	
	$fileName  = $fileNota['name'];
	$fileType  = $fileNota['type'];
	$fileError = $fileNota['error'];
	$fileSize  = $fileNota['size'];
	$fileTmp   = $fileNota['tmp_name'];
	$xml       = null;

	if ($fileSize > 0 && $fileError == 0) {
		$xml = simplexml_load_file($fileTmp);
		
		// CNPJ do emitente
		$cnpj = $xml->NFe->infNFe->emit->CNPJ;

		// Encerra o processo se não for o CNPJ permitido: 09066241000884
		if ( $cnpj != '09066241000884' ) {
			echo json_encode(
				[
					'status' => 'error',
					'message' => 'CNPJ da nota não confere com o CNPJ da empresa!',
				]
			);
			exit;
		}

		$fileType = pathinfo($fileName, PATHINFO_EXTENSION);
		
		if ($fileType === "xml") {
			
			$fileName = basename($fileName);
			
			$dirName = 'uploads/';
			
			
			if (!file_exists($dirName)) {
				mkdir($dirName, 0777, true);
			}

			if (!move_uploaded_file($fileTmp, $dirName . $fileName ) ) {
				echo json_encode(
					[
						'status' => 'error',
						'message' => 'Erro ao realizar upload do arquivo!',
					]
				);
			}
		}
	} else {
		echo json_encode(
			[
				'status' => 'error',
				'message' => 'Arquivo não foi enviado!'				
			]
		);
	}
	return $xml;
}

// Retorna info completa do usuario inner usuario, endereco, doc
function selecionar_info_completa_usuario($id) {
	$conn = open_database();
	$sql = "SELECT * FROM usuario INNER JOIN endereco ON usuario.id = endereco.idendereco INNER JOIN doc ON usuario.id = doc.id_usuario WHERE usuario.id = " . $id;
	$result = $conn->query($sql);
	$info_usuario = null;
	if ($result->num_rows > 0) {
		$info_usuario = $result->fetch_assoc();
	}
	close_database($conn);
	return $info_usuario;
}

// Retornar Nota completa inner emitente, destinatario
function selecionar_nota_completa($emitente_doc) {
	$conn = open_database();
	$sql = "SELECT * FROM nota, usuario, doc, endereco WHERE nota.emitente_doc = " . $emitente_doc;
	$result = $conn->query($sql);
	$notafiscal = null;
	if ($result->num_rows > 0) {
		$notafiscal = $result->fetch_assoc();
	}

	// busca destinatario
	$sql = "SELECT * FROM usuario, doc, endereco
		WHERE endereco.id_usuario = " . $notafiscal['destinatario_doc'];
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$notafiscal['destinatario'] = $result->fetch_assoc();
	}
	close_database($conn);

	echo json_encode($notafiscal, true);
}

// Retornar Nota completa inner emitente, destinatario via id
function selecionar_nota_completa_id($id) {
	$conn = open_database();
	// $sql = "SELECT * FROM nota, usuario, doc, endereco WHERE nota.id = " . $id;
	

	// Select usuario as emitente e usuario as destinatario from nota inner join usuario on nota.emitente_doc = usuario.doc
	$sql = "SELECT nota.numero_nota, nota.data_emissao, nota.valor_total, emitDoc.cnpj, uEmit.nome as emitNome, endEmit.logradouro as emitLogr, endEmit.bairro as emitBairro, endEmit.cep as emitCEP, endEmit.municipio as emitMun, endEmit.uf as emitUf, endEmit.pais as emitPais,
	uDest.nome as DestNome, uDest.email as DestEmail, docDest.cpf, endDest.logradouro, endDest.bairro, endDest.cep, endDest.municipio, endDest.uf, endDest.pais
	FROM nota
	INNER JOIN doc emitDoc ON nota.emitente_id = emitDoc.id_usuario
	JOIN usuario uEmit ON nota.emitente_id = uEmit.id
	JOIN endereco endEmit ON nota.emitente_id = endEmit.id_usuario
	JOIN usuario uDest ON nota.destinatario_id = uDest.id
	JOIN doc docDest ON nota.destinatario_id = docDest.id_usuario
	JOIN endereco endDest ON nota.destinatario_id = endDest.id_usuario
	WHERE nota.id = " . $id;

	$result = $conn->query($sql);
	$notafiscal = null;
	if ($result->num_rows > 0) {
		$notafiscal = $result->fetch_assoc();
	}
	close_database($conn);
	return $notafiscal;
}

function insert_emissor($emissor) {
	$database = open_database();
	$nome_emissor = $emissor['nome'];
	$tipo_usuario = $emissor['tipo'];
	$email_emissor = $emissor['email'];
	
	$sql = "INSERT INTO usuario (nome, tipo, email) VALUES ('$nome_emissor', '$tipo_usuario', '$email_emissor')";
	$result = $database->query($sql);
	if ($result) {
		$emissor = $database->insert_id;
	}
	 close_database($database);
	return $emissor;
}

// Inserir doc do emissor conforme id_usuario no banco de dados
function insert_doc_emissor($doc_emissor) {
	$database = open_database();
	$emissor_id = $doc_emissor['id_usuario'];
	$cnpj = $doc_emissor['cnpj'];
	$im = $doc_emissor['im'];
	$ie = $doc_emissor['ie'];
	$crt = $doc_emissor['crt'];
	$sql = "INSERT INTO doc (id_usuario, cnpj, im, ie, crt) VALUES ('$emissor_id', '$cnpj', '$im', '$ie', '$crt')";
	$result = $database->query($sql);
	if ($result) {
		$doc_emissor = $database->insert_id;
	}
	 close_database($database);
	return $doc_emissor;
}

// Inserir endereco do emissor conforme id_usuario no banco de dados
function insert_endereco_emissor($endereco_emissor) {
	$database = open_database();
	$logradouro = $endereco_emissor['logradouro'];
	$bairro = $endereco_emissor['bairro'];
	$cep = $endereco_emissor['cep'];
	$municipio = $endereco_emissor['municipio'];
	$uf = $endereco_emissor['uf'];
	$pais = $endereco_emissor['pais'];
	$id_usuario = $endereco_emissor['id_usuario'];
	$sql = "INSERT INTO endereco (logradouro, bairro, cep, municipio, uf, pais, id_usuario) VALUES ('$logradouro', '$bairro', '$cep', '$municipio', '$uf', '$pais', '$id_usuario')";
	$result = $database->query($sql);
	if ($result) {
		$endereco_emissor = $database->insert_id;
	}
	close_database($database);
	return $endereco_emissor;
}

// verifica se ja existe cnpj cadastrado no banco de dados
function verifica_cnpj_banco($cnpj) {
	$database = open_database();
	$sql = "SELECT id_usuario FROM doc WHERE cnpj = " . $cnpj;
	$result = $database->query($sql);
	$cnpj_banco = null;
	if ($result->num_rows > 0) {
		$cnpj_banco = $result->fetch_assoc();
	}
	close_database($database);
	return $cnpj_banco;
}

function verifica_cpf_banco($cpf) {
	$database = open_database();
	$sql = "SELECT id_usuario FROM doc WHERE cpf = " . $cpf;
	$result = $database->query($sql);
	$cpf_banco = null;
	if ($result->num_rows > 0) {
		$cpf_banco = $result->fetch_assoc();
	}
	close_database($database);
	return $cpf_banco;
}

// Inserir Destinatario conforme id_usuario no banco de dados
function insert_destinatario($destinatario) {
	$database = open_database();
	$nome_destinatario = $destinatario['nome'];
	$tipo_usuario = $destinatario['tipo'];
	$email_destinatario = $destinatario['email'];
	$sql = "INSERT INTO usuario (nome, tipo, email) VALUES ('$nome_destinatario', '$tipo_usuario', '$email_destinatario')";
	$result = $database->query($sql);
	if ($result) {
		$destinatario = $database->insert_id;
	}
	 close_database($database);
	return $destinatario;
}

// Inserir doc do destinatario conforme id_usuario no banco de dados
function insert_doc_destinatario($doc_destinatario) {
	$database = open_database();
	$destinatario_id = $doc_destinatario['id_usuario'];
	$cpf = $doc_destinatario['cpf'];
	$sql = "INSERT INTO doc (id_usuario, cpf) VALUES ('$destinatario_id', '$cpf')";
	$result = $database->query($sql);
	if ($result) {
		$doc_destinatario = $database->insert_id;
	}
	 close_database($database);
	return $doc_destinatario;
}

function insert_endereco_destinatario($endereco_destinatario) {
	$database = open_database();
	$logradouro = $endereco_destinatario['logradouro'];
	$bairro = $endereco_destinatario['bairro'];
	$cep = $endereco_destinatario['cep'];
	$municipio = $endereco_destinatario['municipio'];
	$uf = $endereco_destinatario['uf'];
	$pais = $endereco_destinatario['pais'];
	$id_usuario = $endereco_destinatario['id_usuario'];
	$sql = "INSERT INTO endereco (logradouro, bairro, cep, municipio, uf, pais, id_usuario) VALUES ('$logradouro', '$bairro', '$cep', '$municipio', '$uf', '$pais', '$id_usuario')";
	$result = $database->query($sql);
	if ($result) {
		$endereco_destinatario = $database->insert_id;
	}
	close_database($database);
	return $endereco_destinatario;
}

// Inserir produto no banco de dados
function insert_produto($produto) {
	$database = open_database();
	$nome = $produto['nome'];
	$codigo = $produto['codigo'];
	$ncm = $produto['ncm'];
	$cfop = $produto['cfop'];
	$unidade = $produto['unidade'];
	$quantidade = $produto['quantidade'];
	$valor_unitario = $produto['valor_unitario'];
	$valor_total = $produto['valor_total'];

	$sql = "INSERT INTO produto (nome, codigo, ncm, cfop, unidade, quantidade, valor_unitario, valor_total) VALUES ('$nome', '$codigo', '$ncm', '$cfop', '$unidade', '$quantidade', '$valor_unitario', '$valor_total')";
	$result = $database->query($sql);
	if ($result) {
		$produto = $database->insert_id;
	}
	close_database($database);
	return $produto;	
}

// update_produto_nota_fiscal
function update_produto_num_nota($produto_id, $nota_fiscal_id){
	$database = open_database();
	$sql = "UPDATE produto SET num_nota = '$nota_fiscal_id' WHERE id_produto = '$produto_id'";
	$result = $database->query($sql);
	close_database($database);
	return $result;
}

// Inserir Nota Fiscal no banco de dados
function insert_nota_fiscal($nota_fiscal) {
	$database = open_database();
	$numero_nota = $nota_fiscal['numero_nota'];
	$valor_total = $nota_fiscal['valor_total'];
	$emitente_id = $nota_fiscal['emitente_id'];
	$destinatario_id = $nota_fiscal['destinatario_id'];
	$data_emissao = $nota_fiscal['data_emissao'];

	$sql = "INSERT INTO nota (numero_nota, valor_total, emitente_id, destinatario_id, data_emissao) VALUES ('$numero_nota', '$valor_total', '$emitente_id', '$destinatario_id', '$data_emissao')";
	$result = $database->query($sql);
	if ($result) {
		$nota_fiscal = $database->insert_id;
	} else {
		echo json_encode(array(
			"error" => true,
			"message" => "Não foi possível inserir a nota fiscal."
		));
		exit;
	}
	close_database($database);

	return $nota_fiscal;
}


?>