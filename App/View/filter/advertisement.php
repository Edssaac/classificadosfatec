<section class="bg-light rounded my-4 mb-5">
    <div id="advertisement_frame" class="row mx-3 py-3">
        <?php if ($this->view->advertisement['advertisements'] === 0) { ?>
            <div class="alert alert-primary" role="alert">
                Nenhum anúncio cadastrado corresponde a essa pesquisa.
            </div>
        <?php } else { ?>
            <?php foreach ($this->view->advertisement['products'] as $product) { ?>
                <div class="col-12 col-lg-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <i class="fa-regular fa-circle-user user-default-size"></i>
                                </div>
                                <div class="col-10">
                                    <p class="my-0"><?= $product['name'] ?>
                                        <br><small><?= $product['ad_date'] ?></small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row g-4 text-center h-100">
                                <div class="col-md-4 align-self-center">
                                    <img src="<?= $product['photo_name'] ?>" class="img-fluid rounded m-lg-2" alt="produto">
                                </div>
                                <div class="col-md-8">
                                    <div>
                                        <h5 class="card-title"><?= $product['title'] ?></h5>
                                        <div class="row my-3 text-center">
                                            <?php if ($product['promotion']) { ?>
                                                <div class="col-6" title="preço">
                                                    <i class="fa-solid fa-dollar-sign"></i>
                                                    <small>R$</small>
                                                    <span class="text-danger">
                                                        <s><?= $product['price'] ?></s>
                                                    </span>
                                                </div>
                                                <div class="col-6" title="promoção">
                                                    <i class="fa-solid fa-tag"></i>
                                                    <small>R$</small>
                                                    <span class="text-success">
                                                        <?= $product['discount'] ?>
                                                    </span>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-6" title="preço">
                                                    <i class="fa-solid fa-dollar-sign"></i>
                                                    <small>R$</small>
                                                    <span class="text-success"><?= $product['price'] ?></span>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <hr>
                                        <p class="card-text text-justify-content mb-3"><?= $product['description'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <a href="/produtos/<?= $product['ad_id'] ?>">
                                <button type="button" class="button-input text-light">Ver Mais</button>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php foreach ($this->view->advertisement['tutorings'] as $tutoring) { ?>
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
            <?php foreach ($this->view->advertisement['solicitations'] as $solicitation) { ?>
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
                            <h4 class="modal-title">Filtrar Anúncios</h4>
                        </div>
                        <div class="mb-3 mx-4">
                            <p>Tipo de Anúncio</p>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="product" name="type[]" value="product">
                                    <label class="form-check-label" for="product">Produtos</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="tutoring" name="type[]" value="tutoring">
                                    <label class="form-check-label" for="tutoring">Monitorias</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="solicitation" name="type[]" value="solicitation">
                                    <label class="form-check-label" for="solicitation">Solicitações</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" id="filtrar" class="button-input text-light" data-bs-dismiss="modal">Filtrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    $('#filtrar').on('click', function(e) {
        const form_data = $('form').serializeArray();

        form_data.push({
            name: 'search',
            value: "<?= $this->view->search ?>"
        });

        if ($('input:checked').length === 0) {
            return;
        }

        $.ajax({
            type: 'post',
            url: '/filtrar_pesquisa',
            dataType: 'json',
            data: form_data,
            success: function(response) {
                const products = response.products;
                const tutorings = response.tutorings;
                const solicitations = response.solicitations;

                let html = '';

                if ((products.length + products.length + products.length) > 0) {
                    for (product of products) {
                        let precification = '';

                        if (product.promotion) {
                            precification = `
                                <div class='col-6' title='preço'>
                                    <i class='fa-solid fa-dollar-sign'></i>
                                    <small>R$</small>
                                    <span class='text-danger'><s>${product.price}</s></span>
                                </div>
                                <div class='col-6' title='promoção'>
                                    <i class='fa-solid fa-tag'></i>
                                    <small>R$</small>
                                    <span class='text-success'>${product.discount}</span>
                                </div>
                            `;
                        } else {
                            precification = `
                                <div class='col-6' title='preço'>
                                    <i class='fa-solid fa-dollar-sign'></i>
                                    <small>R$</small>
                                    <span class='text-success'>${product.price}</span>
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
                                                <p class='my-0'>${product.name}
                                                    <br>
                                                    <small>${product.ad_date}</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='card-body pb-0'>
                                        <div class='row g-4 text-center h-100'>
                                            <div class='col-md-4 align-self-center'>
                                                <img src='${product.photo_name}' class='img-fluid rounded m-lg-2' alt='produto'>
                                            </div>
                                            <div class='col-md-8'>
                                                <div>
                                                    <h5 class='card-title'>${product.title}</h5>
                                                    <div class='row my-3 text-center'>
                                                        ${precification}
                                                    </div>
                                                    <hr>
                                                    <p class='card-text text-justify-content mb-3'>${product.description}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='card-footer text-end'>
                                        <a href='/produtos/${product.ad_id}'>
                                            <button type='button' class='button-input text-light'>Ver Mais</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;
                    }

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
                            Nenhum anúncio cadastrado corresponde a essa pesquisa.
                        </div>
                    `;
                }

                $('form').trigger('reset');
                $('#advertisement_frame').html(html);
            }
        });
    });
</script>