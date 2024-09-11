<section class="bg-light rounded my-4 mb-5">
    <form action="/nova_senha" method="post" class="py-4 mx-lg-5">
        <div class="text-center mb-5">
            <h3 class="text-danger">Redefinir Senha</h3>
        </div>
        <div class="mb-3 mx-4">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" id="senha" name="senha" class="form-control input-cinza" required>
        </div>
        <div class="mb-3 mx-4">
            <label for="senha_confirmacao" class="form-label">Confirmar Senha</label>
            <input type="password" id="senha_confirmacao" name="senha_confirmacao" class="form-control input-cinza" required>
        </div>
        <input type="hidden" id="token" name="token" class="form-control input-cinza" value="<?= $this->view->token ?>" required>
        <div id="status" class="mx-3 my-3"></div>
        <div class="d-grid gap-2 col-6 mx-auto">
            <button type="submit" class="button-input text-light">Enviar</button>
        </div>
    </form>
</section>

<script>
    $('form').submit(function(e) {
        e.preventDefault();

        const form_data = $('form').serialize();

        $.ajax({
            type: 'POST',
            url: '/redefinir_senha',
            dataType: 'html',
            data: form_data,
            beforeSend: function() {
                $('#status').html(
                    `<div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>`
                );
            },
            success: function(data) {
                const response = JSON.parse(data);

                if (response.sucesso) {
                    $('#status').html(
                        `<div class="alert alert-success" role="alert">
                            Sua senha foi alterada com sucesso!
                        </div>`
                    );

                    $('form').trigger('reset');

                    setTimeout(function() {
                        localStorage.clear();
                        window.location.href = '/entrar';
                    }, 1500);
                } else {
                    $('#status').html(
                        `<div class="alert alert-danger" role="alert">
                            Atenção: ${response.mensagem}
                        </div>`
                    );
                }
            },
            error: function() {
                $('#status').html(
                    `<div class="alert alert-danger" role="alert">
                        Atenção: Não foi possível enviar o e-mail.
                    </div>`
                );
            }
        });
    });
</script>