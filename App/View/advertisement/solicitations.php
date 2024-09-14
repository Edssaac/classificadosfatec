<section class="bg-light rounded my-4 mb-5">
    <div id="solicitation_frame" class="row mx-3 py-3">
        <?php if ($this->view->solicitation_quantity === 0) { ?>
            <div class="alert alert-primary" role="alert">
                Não existe nenhuma solicitação sendo anunciada no momento.
            </div>
        <?php } else { ?>
            <?php foreach ($this->view->solicitations as $solicitation) { ?>
                <div class="col-12 col-lg-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <i class="fa-regular fa-circle-user user-default-size"></i>
                                </div>
                                <div class="col-10">
                                    <p class="my-0"><?= $solicitation['name'] ?>
                                        <br><small><?= $solicitation['solicitation_date'] ?></small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row g-4 text-center h-100">
                                <div class="col-md-12">
                                    <div>
                                        <h5 class="card-title"><?= $solicitation['title'] ?></h5>
                                        <hr>
                                        <p class="card-text text-justify-content mb-3"><?= $solicitation['description'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="text-start">
                                <p class="mb-0"><b>Categoria: </b><?= $solicitation['type'] ?></p>
                            </div>
                            <a href="/solicitados/<?= $solicitation['solicitation_id'] ?>">
                                <button type="button" class="button-input text-light">Ver Mais</button>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>

    <div class="modal fade" id="modalFilter" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalFilterLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-red-color">
                    <button type="button" class="btn-close d-block" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/filtrar" method="POST">
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            <h4 class="modal-title">Filtrar Solicitações</h4>
                        </div>
                        <div class="mb-3 mx-4">
                            <p>Tipo de Solicitação</p>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="product" name="type[]" value="produto">
                                    <label class="form-check-label" for="product">Produtos</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="tutoring" name="type[]" value="monitoria">
                                    <label class="form-check-label" for="tutoring">Monitorias</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" id="filter" class="button-input text-light" data-bs-dismiss="modal">Filtrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    $('#filter').on('click', function(e) {
        const form_data = $('form').serialize();

        $.ajax({
            type: 'post',
            url: '/filtrar_solicitacao',
            dataType: 'json',
            data: form_data,
            success: function(response) {
                const solicitations = response.solicitations;

                let html = '';

                if (solicitations.length) {
                    for (solicitation of solicitations) {
                        html += `
                            <div class='col-12 col-lg-6 mb-3'>
                                <div class='card h-100'>
                                    <div class='card-header'>
                                        <div class='row align-items-center'>
                                            <div class='col-2'>
                                                <i class='fa-regular fa-circle-user user-default-size'></i>
                                            </div>
                                            <div class='col-10'>
                                                <p class='my-0'>${solicitation.name}
                                                    <br>
                                                    <small>${solicitation.solicitation_date}</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='card-body pb-0'>
                                        <div class='row g-4 text-center h-100'>
                                            <div class='col-md-12'>
                                                <div>
                                                    <h5 class='card-title'>${solicitation.title}</h5>
                                                    <hr>
                                                    <p class='card-text text-justify-content mb-3'>${solicitation.description}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='card-footer text-end'>
                                        <div class='text-start'>
                                            <p class='mb-0'><b>Categoria: </b>${solicitation.type}</p>
                                        </div>
                                        <a href='/solicitados/${solicitation.solicitation_id}'>
                                            <button type='button' class='button-input text-light'>Ver Mais</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                } else {
                    html = `
                        <div class='alert alert-primary' role='alert'>
                            Nenhuma solicitação cadastrada corresponde a esse filtro.
                        </div>
                    `;
                }

                $('form').trigger('reset');
                $('#solicitation_frame').html(html);
            }
        });
    });
</script>