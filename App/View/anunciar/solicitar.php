<section class="bg-light rounded my-4 mb-5">
    <form action="/solicitar" method="post" enctype="multipart/form-data" class="py-4 mx-lg-5">
        <div class="text-center mb-5">
            <h3 class="text-danger">Solicitar Anúncio</h3>
        </div>
        <div class="mb-3 mx-4">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" id="titulo" name="titulo" class="form-control input-cinza" required>
        </div>
        <div class="mb-3 mx-4">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" id="descricao" rows="5" class="form-control input-cinza" required></textarea>
        </div>
        <div class="mx-3 row my-5">
            <div class="col-12 col-md-3">
                <label for="data_vencimento" class="form-label">Data de Vencimento da Solicitação</label>
                <input type="date" id="data_vencimento" name="data_vencimento" class="form-control input-cinza">
            </div>
        </div>
        <div class="mx-3 row align-items-end">
            <div class="mb-3 col-sm-12 col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo" id="produto" value="produto" checked>
                    <label class="form-check-label" for="produto">Produto</label>
                </div>
                <div class="form-check">
                    <label class="form-check-label" for="monitoria">Monitoria</label>
                    <input class="form-check-input" type="radio" name="tipo" id="monitoria" value="monitoria">
                </div>
            </div>
        </div>
        <div id="status" class="mx-3 my-3"></div>
        <div class="d-grid gap-2 col-6 mx-auto">
            <button type="submit" class="button-input text-light">Solicitar</button>
        </div>
    </form>
</section>

<script>
    $('form').submit(function(e) {
        e.preventDefault();

        const form_data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: '/cadastrar_solicitacao',
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
                            Solicitação anunciada com sucesso!
                        </div>`
                    );

                    $('form').trigger('reset');

                    setTimeout(function() {
                        window.location.href = '/solicitados';
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
                        Não foi possível anunciar a solicitação.
                    </div>`
                );
            }
        });
    });
</script>