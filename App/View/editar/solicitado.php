<section class="bg-light rounded my-4 mb-5">
    <form action="/solicitar" method="post" class="py-4 mx-lg-5">
        <div class="text-center mb-5">
            <h3 class="text-danger">Editar Solicitação de Anúncio</h3>
        </div>
        <div class="mb-3 mx-4">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" id="titulo" name="titulo" class="form-control input-cinza" required value="<?= $this->view->solicitacao["titulo"] ?>">
        </div>
        <div class="mb-3 mx-4">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" id="descricao" rows="5" class="form-control input-cinza" required><?= $this->view->solicitacao["descricao"] ?></textarea>
        </div>
        <div class="mx-3 row my-5">
            <div class="mb-3 col-12 col-md-3">
                <label for="data_vencimento" class="form-label">Data de Vencimento do Anúncio</label>
                <input type="date" id="data_vencimento" name="data_vencimento" min="<?= date("Y-m-d") ?>" max="2999-12-31" class="form-control input-cinza" value="<?= $this->view->solicitacao["data_vencimento"] ?>">
            </div>
        </div>
        <div class="mx-3 row align-items-end">
            <div class="mb-3 col-sm-12 col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo" id="produto" value="P" <?= $this->view->solicitacao["tipo"] == "P" ? "checked" : "" ?>>
                    <label class="form-check-label" for="produto">Produto</label>
                </div>
                <div class="form-check">
                    <label class="form-check-label" for="monitoria">Monitoria</label>
                    <input class="form-check-input" type="radio" name="tipo" id="monitoria" value="M" <?= $this->view->solicitacao["tipo"] == "M" ? "checked" : "" ?>>
                </div>
            </div>
        </div>
        <div id="status" class="mx-3 my-3"></div>
        <div class="d-grid gap-2 col-6 mx-auto">
            <button type="submit" class="button-input text-light">Atualizar</button>
            <button type="button" id="excluir" class="button-input text-light">Excluir</button>
        </div>
    </form>
</section>

<script>
    $('#excluir').on('click', function() {
        if (confirm('Tem certeza de que deseja excluir essa solicitação?')) {
            $.ajax({
                type: 'POST',
                url: '/excluir_solicitacao',
                dataType: 'html',
                data: 'cod_solicitacao=<?= $this->view->solicitacao['cod_solicitacao'] ?>',
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
                    data = JSON.parse(data);

                    if (data.sucesso) {
                        $('#status').html(
                            `<div class="alert alert-success" role="alert">
                                Solicitação excluída com sucesso!
                            </div>`
                        );

                        setTimeout(function() {
                            window.location.href = '/solicitados/';
                        }, 1500);
                    } else {
                        $('#status').html(
                            `<div class="alert alert-danger" role="alert">
                                Atenção: ${data.mensagem}
                            </div>`
                        );
                    }
                },
                error: function(xhr) {
                    $('#status').html(
                        `<div class="alert alert-danger" role="alert">
                            Não foi possível excluir a solicitação.
                        </div>`
                    );
                },
            });
        }
    });

    $('form').submit(function(e) {
        e.preventDefault();

        let form_data = new FormData(this);

        form_data.append('cod_solicitacao', <?= $this->view->solicitacao['cod_solicitacao'] ?>)

        $.ajax({
            type: 'POST',
            url: '/editar_solicitacao',
            dataType: 'JSON',
            data: form_data,
            processData: false,
            contentType: false,
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
                if (data.sucesso) {
                    $('#status').html(
                        `<div class="alert alert-success" role="alert">
                            Solicitação atualizada com sucesso!
                        </div>`
                    );

                    setTimeout(function() {
                        window.location.href = '/solicitados/<?= $this->view->solicitacao['cod_solicitacao'] ?>';
                    }, 1500);
                } else {
                    $('#status').html(
                        `<div class="alert alert-danger" role="alert">
                            Atenção: ${data.mensagem}
                        </div>`
                    );
                }
            },
            error: function() {
                $('#status').html(
                    `<div class="alert alert-danger" role="alert">
                        Não foi possível atualizar a solicitação.
                    </div>`
                );
            },
        });
    });
</script>