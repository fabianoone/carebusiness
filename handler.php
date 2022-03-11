<?php 
include __DIR__ . '/inc/functions.php';

if (isset($_POST['action']) && !empty($_POST['action']) ) {
	$action = $_POST['action'];	
	switch($action) {
		case 'salva_nota_fiscal':
			$fileNota  = $_FILES['filenota'];			
			salva_nota_fiscal($fileNota);			
			break;
		default:
			echo json_encode(
				[
					'status' => 'error',
					'message' => 'Erro ao salvar nota fiscal!',
				]
			);
		break;		
	}
}
?>