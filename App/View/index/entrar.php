<section class="bg-light rounded my-4 mb-5">
    <form action="/entrar" method="post" class="py-4 mx-lg-5">
        <div class="text-center mb-5">
            <h3 class="text-danger">Faça seu login!</h3>
            <small>ainda não possui cadastro? <a href="/cadastrar" class="text-danger">Registre-se</a> aqui!</small>
        </div>
        <div class="mb-3 mx-4">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" id="email" name="email" class="form-control input-cinza" autocomplete="false" required>
        </div>
        <div class="mb-3 mx-4">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" id="senha" name="senha" class="form-control input-cinza" required>
        </div>
        <input type="hidden" name="hash" value="<?= $this->view->hash ?>">
        <div class="text-center">
            <small>Esqueceu sua senha? <a href="/redefinir" class="text-danger">Redefinir senha</a>.</small>
        </div>
        <div id="status" class="mx-3 my-3"></div>
        <div class="d-grid gap-2 col-6 mx-auto">
            <button type="submit" id="entrar" class="button-input text-light">Entrar</button>
        </div>
    </form>
</section>

<script>
    $(document).ready(function() {
        if (parseInt(localStorage.tentativas) <= 0) {
            localStorage.tentativas = 3;
        }
    });

    $('form').submit(function(e) {
        e.preventDefault();

        const form_data = $('form').serializeArray();

        if (localStorage.tentativas === undefined) {
            localStorage.tentativas = 3;
        } else if ((parseInt(localStorage.tentativas) <= 0)) {
            data.push({
                name: 'bloquear',
                value: true
            });
        } else {
            localStorage.tentativas = parseInt(localStorage.tentativas) - 1;
        }

        $.ajax({
            type: 'POST',
            url: '/autenticar',
            data: form_data,
            dataType: 'html',
            beforeSend: function() {
                if (parseInt(localStorage.tentativas) >= 0) {
                    $('#status').html(
                        `<div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>`
                    );
                }
            },
            success: function(data) {
                const response = JSON.parse(data);

                if (response.sucesso) {
                    localStorage.clear();
                    window.location.href = '/';
                } else if (response.sucesso === null) {
                    $('#status').html(
                        `<div class="alert alert-danger" role="alert">
                            Atenção: ${response.mensagem}
                        </div>`
                    );
                } else {
                    if (parseInt(localStorage.tentativas) > 0) {
                        $('#status').html(
                            `<div class="alert alert-warning" role="alert">
                                Atenção: ${response.mensagem}
                                <br>
                                Você tem <b>${localStorage.tentativas}</b> tentativas restantes!
                            </div>`
                        );
                    } else {
                        $('#status').html(
                            `<div class="alert alert-danger" role="alert">
                                Acesso bloqueado! Redefina sua senha.
                            </div>`
                        );

                        $('#entrar').click();
                        $('#entrar').remove();
                    }
                }
            },
            error: function() {
                $('#status').html(
                    `<div class="alert alert-danger" role="alert">
                        Não foi possível se conectar.
                    </div>`
                );
            }
        });
    });
</script>