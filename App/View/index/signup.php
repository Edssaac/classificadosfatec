<section class="bg-light rounded my-4 mb-5">
    <form action="/cadastrar" method="post" class="py-4 mx-lg-5">
        <div class="text-center mb-5">
            <h3 class="text-danger">Criar Conta</h3>
        </div>
        <div class="mb-3 mx-4">
            <label for="name" class="form-label">Nome</label>
            <input type="text" id="name" name="name" class="form-control input-grey-color" autocomplete="false" required>
        </div>
        <div class="mb-3 mx-4">
            <label for="birth_date" class="form-label">Data de Nascimento</label>
            <input type="date" id="birth_date" name="birth_date" class="form-control input-grey-color" required>
        </div>
        <div class="mb-3 mx-4">
            <label for="phone" class="form-label">Telefone</label>
            <input type="text" id="phone" name="phone" class="form-control input-grey-color" autocomplete="false" required>
        </div>
        <div class="mb-3 mx-4">
            <label for="institution" class="form-label">Instituição</label>
            <select class="form-select input-grey-color" id="institution" name="institution" required>
                <option selected>Selecione sua Fatec</option>
            </select>
        </div>
        <div class="mb-3 mx-4">
            <label for="email" class="form-label">
                E-mail Institucional <span class="form-text"><small>(@fatec.sp.gov.br)</small></span>
            </label>
            <input type="email" id="email" name="email" class="form-control input-grey-color" autocomplete="false" required>
        </div>
        <div class="mb-3 mx-4">
            <label for="password" class="form-label">Senha</label>
            <input type="password" id="password" name="password" class="form-control input-grey-color" required>
        </div>
        <div class="mb-3 mx-4">
            <label for="confirm_password" class="form-label">Confirmar Senha</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control input-grey-color" required>
        </div>
        <div id="status" class="mx-3 my-3"></div>
        <div class="d-grid gap-2 col-6 mx-auto">
            <button type="submit" class="button-input text-light">Cadastrar</button>
        </div>
    </form>
</section>

<script>
    $(document).ready(function() {
        $('#phone').mask('(99) 9999-99999');

        $.getScript('assets/js/fatecs.js', function() {
            for (let fatec in fatecs) {
                $('#institution').append(`<option value="${fatec}"> ${fatecs[fatec]} </option>`);
            }
        });
    });

    $('form').submit(function(e) {
        e.preventDefault();

        const form_data = $('form').serialize();

        $.ajax({
            type: 'post',
            url: '/registrar',
            dataType: 'json',
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
            success: function(response) {
                if (response.success) {
                    $('#status').html(
                        `<div class="alert alert-success" role="alert">
                            Cadastro realizado com sucesso!<br>
                            Acesse seu e-mail para realizar a confirmação do cadastro.
                        </div>`
                    );

                    $('form').trigger('reset');

                    setTimeout(function() {
                        window.location.href = '/entrar';
                    }, 1000);
                } else {
                    $('#status').html(
                        `<div class="alert alert-danger" role="alert">
                        Atenção: ${response.message}
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