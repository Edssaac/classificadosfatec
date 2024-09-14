<section class="bg-light rounded my-4 mb-5">
    <form action="/fale_conosco" method="post" class="py-4 mx-lg-5">
        <h3 class="text-center text-danger mb-5">Fale Conosco</h3>
        <div class="mb-3 mx-4">
            <label for="name" class="form-label">Nome</label>
            <input type="text" id="name" name="name" class="form-control input-grey-color" required>
        </div>
        <div class="mb-3 mx-4">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" id="email" name="email" class="form-control input-grey-color" required>
        </div>
        <div class="mb-3 mx-4">
            <label for="email" class="form-label">Telefone</label>
            <input type="text" id="phone" name="phone" class="form-control input-grey-color" required>
        </div>
        <div class="mb-3 mx-4">
            <label for="message" class="form-label">Mensagem</label>
            <textarea name="message" id="message" rows="5" class="form-control input-grey-color" required></textarea>
        </div>
        <div id="status" class="mx-3 my-3"></div>
        <div class="d-grid gap-2 col-6 mx-auto">
            <button type="submit" class="button-input text-light">Enviar</button>
        </div>
    </form>
</section>

<script>
    $(document).ready(function() {
        $('#phone').mask('(99) 9999-99999');
    });

    $('form').submit(function(e) {
        e.preventDefault();

        const form_data = $('form').serialize();

        $.ajax({
            type: 'post',
            url: '/mensagem',
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
            success: function() {
                $('#status').html(
                    `<div class="alert alert-success" role="alert">
                        E-mail enviado com sucesso
                    </div>`
                );
            },
            error: function() {
                $('#status').html(
                    `<div class="alert alert-danger" role="alert">
                        Não foi possível enviar o e-mail.
                    </div>`
                );
            },
            complete: function() {
                $('form').trigger('reset');
            }
        });
    });
</script>