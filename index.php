<?php require_once 'config.php'; ?>
<?php require_once ABSPATH . '/inc/functions.php'; ?>
<?php require_once ABSPATH . '/inc/header.php'; ?>



<form class="form-signin" enctype="multipart/form-data">
  <img class="cb-logo mb-3" src="/public/img/logo-care-business.png" alt="Care Business">
  <h1 class="h3 mb-4 font-weight-normal">Envio de Nota Fiscal</h1>

  <label for="inputCnpj" class="sr-only">Insira um CNPJ</label>
  <input type="text"  id="inputCnpj" class="form-control" placeholder="Insira um CNPJ" required autofocus>
  <input type="hidden" name="MAX_FILE_SIZE" value="30000" />

  <div class="custom-file my-3">
    <input type="file" accept="text/xml" class="custom-file-input" name="filenota" id="fileNota" required>
    <label class="custom-file-label" for="filenota">Selecione o arquivo XML</label>
  </div>

  <button id="enviaNota" class="my-5 btn btn-lg btn-primary btn-block" type="submit">Enviar arquivo</button>
  <small class="my-5 mb-3 text-muted">Teste Vaga – Desenvolvedor PHP | Care Business &copy; <?php echo Date('Y'); ?> | <a href="https://www.linkedin.com/in/fabianoone/" target="_blank" rel="noopener noreferrer">Fabiano Oliveira</a></small>
</form>


<?php require_once ABSPATH . '/inc/footer.php'; ?>

<script>
	$('#enviaNota').click(function(e) {
		e.preventDefault();
		saveNota();
	});
	async function saveNota() {
		let formData = new FormData();
		formData.append('action', 'salva_nota_fiscal');
		formData.append('cnpj', $('#inputCnpj').val());
		formData.append('filenota', $('#fileNota')[0].files[0]);
		toastr.options = {
			closeButton: false,
			progressBar: true,
			showDuration: "300",
			hideDuration: "1000",
			timeOut: "5000",
		};
		if (formData.get('filenota') == undefined) {
			toastr.error('Selecione um arquivo XML!');
			return;
		}
		if (formData.get('cnpj') == '') {
			toastr.error('Insira um CNPJ!');
			return;
		}
		if (formData.get('filenota').size > 30000) {
			toastr.error('O arquivo XML deve ter no máximo 30KB!');
			return;
		}
		if (formData.get('filenota').type != 'text/xml') {
			toastr.error('O arquivo XML deve ser do tipo XML!');
			return;
		}
		if (formData.get('cnpj').length != 14) {
			toastr.error('O CNPJ deve ter 14 dígitos!');
			return;
		}
		if (formData.get('cnpj').match(/^[0-9]+$/) == null) {
			toastr.error('O CNPJ deve conter apenas números!');
			return;
		}
		if (formData.get('cnpj') != '09066241000884') {
			toastr.error('O CNPJ deve ser 09066241000884!');
			return;
		}
		$.ajax({
			url: '/handler.php',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function(data) {
				let json = $.parseJSON(data);

				if (json.status == 'success') {
					toastr.success(json.message);
					setTimeout(function() {
						window.location.href = '/exibir_nota.php?cnpj=' + json.nota_fiscal_id;
					}, 5000);
				} 
				if (json.status == 'info') {
					toastr.info(json.message);
				} 
				else {
					toastr.error(json.message);
				}
			},
			error: function(data) {
				toastr.error('Erro ao salvar ou reenviar a mesma nota fiscal!');
			}
		});


	}
	
</script>