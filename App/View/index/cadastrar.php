<section class="bg-light rounded my-4 mb-5">
    <form action="/cadastrar" method="post" class="py-4 mx-lg-5">
        <div class="text-center mb-5">
            <h3 class="text-danger">Criar Conta</h3>
        </div>
        <div class="mb-3 mx-4">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" id="nome" name="nome" class="form-control input-cinza" required>
        </div>
        <div class="mb-3 mx-4">
            <label for="data_nascimento" class="form-label">Data de Nascimento</label>
            <input type="date" id="data_nascimento" name="data_nascimento" max="2999-12-31" class="form-control input-cinza" required>
        </div>
        <div class="mb-3 mx-4">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" id="telefone" name="telefone" class="form-control input-cinza" required>
        </div>
        <div class="mb-3 mx-4">
            <label for="instituicao" class="form-label">Instituição</label>
            <select class="form-select input-cinza" id="instituicao" name="instituicao" required>
                <option selected>Selecione sua Fatec</option>
            </select>
        </div>
        <div class="mb-3 mx-4">
            <label for="email" class="form-label">
                E-mail Institucional <span class="form-text"><small>(@fatec.sp.gov.br)</small></span>
            </label>
            <input type="email" id="email" name="email" class="form-control input-cinza" autocomplete="false" required>
        </div>
        <div class="mb-3 mx-4">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" id="senha" name="senha" class="form-control input-cinza" required>
        </div>
        <div class="mb-3 mx-4">
            <label for="senha_confirmacao" class="form-label">Confirmar Senha</label>
            <input type="password" id="senha_confirmacao" name="senha_confirmacao" class="form-control input-cinza" required>
        </div>
        <div id="status" class="mx-3 my-3"></div>
        <div class="d-grid gap-2 col-6 mx-auto">
            <button type="submit" class="button-input text-light">Cadastrar</button>
        </div>
    </form>
</section>

<script>
    $(document).ready(function() {
        $('#telefone').mask('(99) 9999-99999');

        $.getScript('assets/js/fatecs.js', function() {
            for (let fatec in fatecs) {
                $('#instituicao').append(`<option value="${fatec}"> ${fatecs[fatec]} </option>`);
            }
        });
    });

    $('form').submit(function(e) {
        e.preventDefault();

        const form_data = $('form').serialize();

        $.ajax({
            type: 'POST',
            url: '/registrar',
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
                            Cadastro realizado com sucesso!<br>
                            Acesse seu e-mail para realizar a confirmação do cadastro.
                        </div>`
                    );

                    $('form').trigger('reset');

                    setTimeout(function() {
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
                        Não foi possível realizar o cadastro.
                    </div>`
                );
            }
        });
    });
</script>