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
