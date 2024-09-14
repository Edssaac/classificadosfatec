<section class="bg-light rounded my-4 mb-5">
    <div id="product_frame" class="row mx-3 py-3">
        <?php if ($this->view->product_quantity === 0) { ?>
            <div class="alert alert-primary" role="alert">
                Não existe nenhum produto sendo anunciado no momento.
            </div>
        <?php } else { ?>
            <?php foreach ($this->view->products as $product) { ?>
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
                            <h4 class="modal-title">Filtrar Produtos</h4>
                        </div>
                        <div class="mb-3 mx-4">
                            <p>Estado do Produto</p>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="novo" name="condition[]" value="novo">
                                    <label class="form-check-label" for="novo">Novo</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="seminovo" name="condition[]" value="seminovo">
                                    <label class="form-check-label" for="seminovo">Seminovo</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="usado" name="condition[]" value="usado">
                                    <label class="form-check-label" for="usado">Usado</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 mx-4">
                            <p>Operação</p>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="venda" name="operation[]" value="venda">
                                    <label class="form-check-label" for="venda">Venda</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="troca" name="operation[]" value="troca">
                                    <label class="form-check-label" for="troca">Troca</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="ambos" name="operation[]" value="ambos">
                                    <label class="form-check-label" for="ambos">Ambos</label>
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
            url: '/filtrar_produto',
            dataType: 'json',
            data: form_data,
            success: function(response) {
                const products = response.products;

                let html = '';

                if (products.length) {
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
                } else {
                    html = `
                        <div class='alert alert-primary' role='alert'>
                            Nenhum produto cadastrado corresponde a esse filtro.
                        </div>
                    `;
                }

                $('form').trigger('reset');
                $('#product_frame').html(html);
            }
        });
    });
</script>