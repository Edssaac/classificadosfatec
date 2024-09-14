<section class="bg-light rounded my-4 mb-5">
    <form action="/solicitar" method="post" class="py-4 mx-lg-5">
        <div class="text-center mb-5">
            <h3 class="text-danger">Solicitar Anúncio</h3>
        </div>
        <div class="mb-3 mx-4">
            <label for="title" class="form-label">Título</label>
            <input type="text" id="title" name="title" class="form-control input-grey-color" required>
        </div>
        <div class="mb-3 mx-4">
            <label for="description" class="form-label">Descrição</label>
            <textarea name="description" id="description" rows="5" class="form-control input-grey-color" required></textarea>
        </div>
        <div class="mx-3 row my-5">
            <div class="col-12 col-md-3">
                <label for="expiry_date" class="form-label">Data de Vencimento da Solicitação</label>
                <input type="date" id="expiry_date" name="expiry_date" class="form-control input-grey-color">
            </div>
        </div>
        <div class="mx-3 row align-items-end">
            <div class="mb-3 col-sm-12 col-md-4">
                <div class="form-check">
                    <label class="form-check-label" for="product">Produto</label>
                    <input class="form-check-input" type="radio" name="type" id="product" value="produto" checked>
                </div>
                <div class="form-check">
                    <label class="form-check-label" for="tutoring">Monitoria</label>
                    <input class="form-check-input" type="radio" name="type" id="tutoring" value="monitoria">
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
            type: 'post',
            url: '/cadastrar_solicitacao',
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
                            Solicitação anunciada com sucesso!
                        </div>`
                    );

                    $('form').trigger('reset');

                    setTimeout(function() {
                        window.location.href = '/solicitados';
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
                        Não foi possível anunciar a solicitação.
                    </div>`
                );
            }
        });
    });
</script>