<section class="bg-light rounded my-4 mb-5">
    <form action="/solicitar" method="post" class="py-4 mx-lg-5">
        <div class="text-center mb-5">
            <h3 class="text-danger">Editar Solicitação de Anúncio</h3>
        </div>
        <div class="mb-3 mx-4">
            <label for="title" class="form-label">Título</label>
            <input type="text" id="title" name="title" class="form-control input-grey-color" required value="<?= $this->view->solicitation['title'] ?>">
        </div>
        <div class="mb-3 mx-4">
            <label for="description" class="form-label">Descrição</label>
            <textarea name="description" id="description" rows="5" class="form-control input-grey-color" required><?= $this->view->solicitation['description'] ?></textarea>
        </div>
        <div class="mx-3 row my-5">
            <div class="mb-3 col-12 col-md-3">
                <label for="expiry_date" class="form-label">Data de Vencimento do Anúncio</label>
                <input type="date" id="expiry_date" name="expiry_date" class="form-control input-grey-color" value="<?= $this->view->solicitation['expiry_date'] ?>">
            </div>
        </div>
        <div class="mx-3 row align-items-end">
            <div class="mb-3 col-sm-12 col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" id="product" value="produto" <?= $this->view->solicitation['type'] == 'produto' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="product">Produto</label>
                </div>
                <div class="form-check">
                    <label class="form-check-label" for="tutoring">Monitoria</label>
                    <input class="form-check-input" type="radio" name="type" id="tutoring" value="monitoria" <?= $this->view->solicitation['type'] == 'monitoria' ? 'checked' : '' ?>>
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
                type: 'post',
                url: '/excluir_solicitacao',
                dataType: 'json',
                data: 'solicitation_id=<?= $this->view->solicitation['solicitation_id'] ?>',
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
                                Solicitação excluída com sucesso!
                            </div>`
                        );

                        setTimeout(function() {
                            window.location.href = '/solicitados/';
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

        form_data.append('solicitation_id', <?= $this->view->solicitation['solicitation_id'] ?>)

        $.ajax({
            type: 'post',
            url: '/editar_solicitacao',
            dataType: 'json',
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
            success: function(response) {
                if (response.success) {
                    $('#status').html(
                        `<div class="alert alert-success" role="alert">
                            Solicitação atualizada com sucesso!
                        </div>`
                    );

                    setTimeout(function() {
                        window.location.href = '/solicitados/<?= $this->view->solicitation['solicitation_id'] ?>';
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
                        Não foi possível atualizar a solicitação.
                    </div>`
                );
            },
        });
    });
</script>