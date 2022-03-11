<?php require_once 'config.php'; ?>
<?php require_once ABSPATH . '/inc/functions.php'; ?>
<?php require_once ABSPATH . '/inc/header.php'; ?>

<?php
$id = $_GET['cnpj'];
$nota_fiscal = selecionar_nota_completa_id($id);
$endDestCompl = $nota_fiscal['logradouro'] . ' - ' . $nota_fiscal['bairro'] . ' | CEP: ' . $nota_fiscal['cep'] . ' - ' . $nota_fiscal['municipio'] . ' / ' . $nota_fiscal['uf'] . ' - ' . ($nota_fiscal['pais'] == 1058 ? 'Brasil' : $nota_fiscal['pais']);
$endEmitCompl = $nota_fiscal['emitLogr'] . ' - ' . $nota_fiscal['emitBairro'] . ' | CEP: ' . $nota_fiscal['emitCEP'] . ' - ' . $nota_fiscal['emitMun'] . ' / ' . $nota_fiscal['emitUf'] . ' - ' . ($nota_fiscal['emitPais'] == 1058 ? 'Brasil' : $nota_fiscal['emitPais']);
?>

<div class="container">
	<div class="nota-info text-center">
		<img class="cb-logo d-block mx-auto mb-4" src="/public/img/logo-care-business.png" alt="Care Business">
		<h4>Informações da Nota Fiscal número #<?php echo !empty($nota_fiscal['numero_nota']) ? $nota_fiscal['numero_nota'] : $id; ?></h4>
	</div>
	<div class="row">
		<div class="col-12">
			<form class="needs-validation">
				<div class="row">
					<div class="col-md-6 mb-3">
						<label for="nome">Destinatário</label>
						<input type="text" class="form-control" id="nome" placeholder="<?php echo !empty($nota_fiscal['DestNome']) ? $nota_fiscal['DestNome'] : '--'; ?>" value="" disabled>
					</div>
					<div class="col-md-6 mb-3">
						<label for="email">E-mail</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">@</span>
							</div>
							<input type="text" class="form-control" id="email" placeholder="<?php echo !empty($nota_fiscal['DestEmail']) ? $nota_fiscal['DestEmail'] : '--'; ?>" disabled>
						</div>
					</div>
				</div>


				<div class="row">
					<div class="col-md-2 mb-3">
						<label for="cpf">CPF</label>
						<input type="text" class="form-control" id="cpf" placeholder="<?php echo !empty($nota_fiscal['cpf']) ? $nota_fiscal['cpf'] : '--'; ?>" value="" disabled>
					</div>

					<div class="col-md-10 mb-3">
						<label for="endereco">Endereço</label>
						<input type="text" class="form-control" id="endereco" placeholder="<?php echo !empty($endDestCompl) ? $endDestCompl : '--'; ?>" disabled>
					</div>
				</div>

				<hr class="mb-4">

				<h4 class="mb-3">Emitente</h4>
				<div class="row">
					<div class="col-2 mb3">
						<label for="zip"><small>CNPJ</small></label>
						<input type="text" class="form-control" id="emitente" placeholder="<?php echo !empty($nota_fiscal['cnpj']) ? $nota_fiscal['cnpj'] : '--'; ?>" disabled>
					</div>
					<div class="col-10 mb-3">
						<label for="zip"><small>Emitente</small></label>
						<input type="text" class="form-control" id="emitente" placeholder="<?php echo !empty($nota_fiscal['emitNome']) ? $nota_fiscal['emitNome'] : '--'; ?>" disabled>
					</div>
				</div>

				<div class="mb-3">
					<label for="endereco">Endereço </label>
					<input type="endereco" class="form-control" id="endereco" placeholder="<?php echo !empty($endEmitCompl) ? $endEmitCompl : '--'; ?>" disabled>
				</div>

				<hr class="mb-4">

				<h4 class="d-flex justify-content-between align-items-center mb-3">
					<span class="text-muted">Valores</span>
				</h4>
				<ul class="list-group mb-3">
					<li class="list-group-item d-flex justify-content-between lh-condensed">
						<span>Valor Total da Nota</span>
						<strong><?php echo !empty($nota_fiscal['valor_total']) ? 'R$ ' . $nota_fiscal['valor_total'] : '--'; ?></strong>
					</li>
				</ul>
			</form>
		</div>
	</div>
</div>



<?php require_once __DIR__ . '/inc/footer.php'; ?>
<script>
	$(document).ready(function() {
		$('body').removeClass('text-center');
	});
</script>