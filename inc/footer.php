
		<script src="<?php echo BASEURL . '/public/assets/js/jquery.min.js'; ?>"></script>
		<script src="<?php echo BASEURL . '/public/assets/js/bootstrap.min.js'; ?>"></script>
		<script src="<?php echo BASEURL . '/public/assets/js/toastr.min.js'; ?>"></script>
		<script>
			// Show uploaded file name in input field
			$(".custom-file-input").on("change", function() {
				var fileName = $(this).val().split("\\").pop();
				$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
			});

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
	</body>
</html>