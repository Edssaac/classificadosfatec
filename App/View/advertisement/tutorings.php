<section class="bg-light rounded my-4 mb-5">
    <div id="tutoring_frame" class="row mx-3 py-3">
        <?php if ($this->view->tutoring_quantity === 0) { ?>
            <div class="alert alert-primary" role="alert">
                Não existe nenhuma monitoria sendo anunciada no momento.
            </div>
        <?php } else { ?>
            <?php foreach ($this->view->tutorings as $tutoring) { ?>
                <div class="col-12 col-lg-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <i class="fa-regular fa-circle-user user-default-size"></i>
                                </div>
                                <div class="col-10">
                                    <p class="my-0">
                                        <?= $tutoring['name'] ?>
                                        <br>
                                        <small><?= $tutoring['ad_date'] ?></small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row g-4 text-center h-100">
                                <div class="col-md-12">
                                    <div>
                                        <h5 class="card-title"><?= $tutoring['title'] ?></h5>
                                        <div class="row my-3 text-center">
                                            <?php if ($tutoring['promotion']) { ?>
                                                <div class="col-6" title="preço">
                                                    <i class="fa-solid fa-dollar-sign"></i>
                                                    <small>R$</small>
                                                    <span class="text-danger">
                                                        <s><?= $tutoring['price'] ?></s>
                                                    </span>
                                                </div>
                                                <div class="col-6" title="promoção">
                                                    <i class="fa-solid fa-tag"></i>
                                                    <small>R$</small>
                                                    <span class="text-success">
                                                        <?= $tutoring['discount'] ?>
                                                    </span>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-6" title="preço">
                                                    <i class="fa-solid fa-dollar-sign"></i>
                                                    <small>R$</small>
                                                    <span class="text-success"><?= $tutoring['price'] ?></span>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <hr>
                                        <p class="card-text text-justify-content mb-3"><?= $tutoring['description'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="text-start">
                                <p class="mb-0"><b>Matéria: </b><?= $tutoring['subject'] ?></p>
                            </div>
                            <a href="/monitorias/<?= $tutoring['ad_id'] ?>">
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
                            <h4 class="modal-title">Filtrar Monitorias</h4>
                        </div>
                        <div class="mb-3 mx-4">
                            <label for="subject" class="form-label">Matéria</label>
                            <input type="text" class="form-control input-grey-color" id="subject" name="subject">
                        </div>
                        <div class="mb-3 mx-4">
                            <p>Dias Disponíveis</p>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="segunda" name="days[]" value="1">
                                    <label class="form-check-label" for="segunda">Segunda-Feira</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="terca" name="days[]" value="2">
                                    <label class="form-check-label" for="terca">Terça-Feira</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="quarta" name="days[]" value="3">
                                    <label class="form-check-label" for="quarta">Quarta-Feira&emsp;</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="quinta" name="days[]" value="4">
                                    <label class="form-check-label" for="quinta">Quinta-Feira</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="sexta" name="days[]" value="5">
                                    <label class="form-check-label" for="sexta">Sexta-Feira&emsp;&nbsp;&nbsp;</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="sabado" name="days[]" value="6">
                                    <label class="form-check-label" for="sabado">Sábado&emsp;&emsp;</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="domingo" name="days[]" value="7">
                                    <label class="form-check-label" for="domingo">Domingo&emsp;&emsp;</label>
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
            url: '/filtrar_monitoria',
            dataType: 'json',
            data: form_data,
            success: function(response) {
                const tutorings = response.tutorings;

                let html = '';

                if (tutorings.length) {
                    for (tutoring of tutorings) {
                        let precification = '';

                        if (tutoring.promotion) {
                            precification = `
                                <div class='col-6' title='preço'>
                                    <i class='fa-solid fa-dollar-sign'></i>
                                    <small>R$</small>
                                    <span class='text-danger'><s>${tutoring.price}</s></span>
                                </div>
                                <div class='col-6' title='promoção'>
                                    <i class='fa-solid fa-tag'></i>
                                    <small>R$</small>
                                    <span class='text-success'>${tutoring.discount}</span>
                                </div>
                            `;
                        } else {
                            precification = `
                                <div class='col-6' title='preço'>
                                    <i class='fa-solid fa-dollar-sign'></i>
                                    <small>R$</small>
                                    <span class='text-success'>${tutoring.price}</span>
                                </div>
                            `;
                        }

                        html += `
                            <div class='col-12 col-lg-6 mb-3'>
                                <div class='card h-100'>
                                    <div class='card-header'>
                                        <div class='row align-items-center'>
                                            <div class='col-2'>
                                                <i class='fa-regular fa-circle-user user-default-size'></i>
                                            </div>
                                            <div class='col-10'>
                                                <p class='my-0'>${tutoring.name}
                                                    <br>
                                                    <small>${tutoring.ad_date}</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='card-body pb-0'>
                                        <div class='row g-4 text-center h-100'>
                                            <div class='col-md-12'>
                                                <div>
                                                    <h5 class='card-title'>${tutoring.title}</h5>
                                                    <div class='row my-3 text-center'>
                                                        ${precification}
                                                    </div>
                                                    <hr>
                                                    <p class='card-text text-justify-content mb-3'>${tutoring.description}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='card-footer text-end'>
                                        <div class='text-start'>
                                            <p class='mb-0'><b>Matéria: </b>${tutoring.subject}</p>
                                        </div>
                                        <a href='/monitorias/${tutoring.ad_id}'>
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
                            Nenhum produto cadastrado corresponde a esse filtro.
                        </div>
                    `;
                }

                $('form').trigger('reset');
                $('#tutoring_frame').html(html);
            }
        });
    });
</script>