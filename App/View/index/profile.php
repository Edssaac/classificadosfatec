<section class="bg-light rounded my-4 mb-5">
    <form action="/atualizar_perfil" method="post" class="py-4 mx-lg-5">
        <div class="text-center mb-5">
            <h3 class="text-danger">Meu Perfil</h3>
        </div>
        <div class="mb-3 mx-4">
            <label for="name" class="form-label">Nome</label>
            <input type="text" id="name" name="name" class="form-control input-grey-color" value="<?= $this->view->user['name'] ?>" autocomplete="false" required>
        </div>
        <div class="mb-3 mx-4">
            <label for="birth_date" class="form-label">Data de Nascimento</label>
            <input type="date" id="birth_date" name="birth_date" class="form-control input-grey-color" value="<?= $this->view->user['birth_date'] ?>" required>
        </div>
        <div class="mb-3 mx-4">
            <label for="phone" class="form-label">Telefone</label>
            <input type="text" id="phone" name="phone" class="form-control input-grey-color" value="<?= $this->view->user['phone'] ?>" autocomplete="false" required>
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
            <input type="email" id="email" name="email" class="form-control input-grey-color" value="<?= $this->view->user['email'] ?>" autocomplete="false" disabled>
        </div>
        <div class="mb-3 mx-4">
            <p class="mb-1">Meus Anúncios</p>
            <ul class="list-group overflow-auto">
                <li class="list-group-item"></li>
                <?php foreach ($this->view->ads as $ad) { ?>
                    <?php if ($ad['type'] === "produto") { ?>
                        <li class="list-group-item"><a href="/produtos/editar/<?= $ad['ad_id'] ?>" target="_blank" class="text-decoration-none"><?= $ad['title'] ?></a></li>
                    <?php } else { ?>
                        <li class="list-group-item"><a href="/monitorias/editar/<?= $ad['ad_id'] ?>" target="_blank" class="text-decoration-none"><?= $ad['title'] ?></a></li>
                    <?php } ?>
                <?php } ?>
                <li class="list-group-item"></li>
            </ul>
        </div>
        <div class="mb-3 mx-4">
            <p class="mb-1">Minhas Solicitações</p>
            <ul class="list-group overflow-auto">
                <li class="list-group-item"></li>
                <?php foreach ($this->view->solicitations as $solicitation) { ?>
                    <li class="list-group-item"><a href="/solicitados/editar/<?= $solicitation['solicitation_id'] ?>" target="_blank" class="text-decoration-none"><?= $solicitation['title'] ?></a></li>
                <?php } ?>
                <li class="list-group-item"></li>
            </ul>
        </div>
        <div class="mb-3 mx-4">
            <p class="mb-1">Reputação:</p>
            <div class="text-danger user-select-none">
                <?php for ($i = 1; $i <= 5; $i++) { ?>
                    <?php if ($i <= $this->view->reputation) { ?>
                        <i class="fa-solid fa-star"></i>
                    <?php } else { ?>
                        <i class="fa-regular fa-star"></i>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <div id="status" class="mx-3 my-3"></div>
        <div class="d-grid gap-2 col-6 mx-auto">
            <button type="submit" class="button-input text-light">Salvar</button>
        </div>
    </form>
</section>

<script>
    $(document).ready(function() {
        $('#phone').mask('(99) 9999-99999');

        $.getScript('assets/js/fatecs.js', function() {
            for (let key in fatecs) {
                $('#institution').append(`<option value="${key}"> ${fatecs[key]} </option>`);
            }

            $('#institution option[value="<?= $this->view->user['institution'] ?>"]').attr('selected', 'selected');
        });
    });

    $('form').submit(function(e) {
        e.preventDefault();

        const form_data = $('form').serialize();

        $.ajax({
            type: 'POST',
            url: '/atualizar_perfil',
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
                            Dados alterados com sucesso!
                        </div>`
                    );

                    $('form').trigger('reset');

                    setTimeout(function() {
                        window.location.href = '/perfil';
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
                        Atenção: Não foi possível atualizar os dados.
                    </div>`
                );
            }
        });
    });
</script>