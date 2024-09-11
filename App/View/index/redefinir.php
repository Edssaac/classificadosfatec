<section class="bg-light rounded my-4 mb-5">
    <form action="/redefinir" method="post" class="py-4 mx-lg-5">
        <div class="text-center mb-5">
            <h3 class="text-danger">Redefinir Senha</h3>
        </div>
        <div class="mb-3 mx-4">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" id="email" name="email" class="form-control input-cinza" required>
        </div>
        <div class="mb-3 mx-4">
            <label for="data_nascimento" class="form-label">Data de Nascimento</label>
            <input type="date" id="data_nascimento" name="data_nascimento" max="2999-12-31" class="form-control input-cinza" required>
        </div>
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
                            Foi enviado uma mensagem para seu e-mail, acesse-o para redefinir a senha!
                        </div>`
                    );
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