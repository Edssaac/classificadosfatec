<section class="bg-light rounded my-4 mb-5">
    <div id="quadro_produtos" class="row mx-3 py-3">
        <?php if ($this->view->quantidade_produtos === 0) { ?>
            <div class="alert alert-primary" role="alert">
                Não existe nenhum produto sendo anunciado no momento.
            </div>
        <?php } else { ?>
            <?php foreach ($this->view->produtos as $produto) { ?>
                <div class="col-12 col-lg-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <i class="fa-regular fa-circle-user usuario"></i>
                                </div>
                                <div class="col-10">
                                    <p class="my-0"><?= $produto['nome'] ?>
                                        <br><small><?= $produto['data_anunciada'] ?></small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row g-4 text-center h-100">
                                <div class="col-md-4 align-self-center">
                                    <img src="https://raw.githubusercontent.com/Edssaac/cf_storage/main/produtos/<?= $produto['foto_name'] ?>" class="img-fluid rounded m-lg-2" alt="produto">
                                </div>
                                <div class="col-md-8">
                                    <div>
                                        <h5 class="card-title"><?= $produto['titulo'] ?></h5>
                                        <div class="row my-3 text-center">
                                            <?php if ($produto['desconto'] && (strtotime($produto['data_desconto']) > time())) { ?>
                                                <div class="col-6" title="preço">
                                                    <i class="fa-solid fa-dollar-sign"></i>
                                                    <small>R$</small>
                                                    <span class="text-danger">
                                                        <s><?= preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $produto['valor'])) ?></s>
                                                    </span>
                                                </div>
                                                <div class="col-6" title="promoção">
                                                    <i class="fa-solid fa-tag"></i>
                                                    <small>R$</small>
                                                    <span class="text-success">
                                                        <?= preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $produto['valor'] - $produto['desconto'])) ?>
                                                    </span>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-6" title="preço">
                                                    <i class="fa-solid fa-dollar-sign"></i>
                                                    <small>R$</small>
                                                    <span class="text-success"><?= preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $produto['valor'])) ?></span>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <hr>
                                        <?php if (strlen($produto['descricao']) > 250) { ?>
                                            <p class="card-text justificado mb-3"><?= substr(nl2br($produto['descricao']), 0, 250) ?>...</p>
                                        <?php } else { ?>
                                            <p class="card-text justificado mb-3"><?= nl2br($produto['descricao']) ?></p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <a href="/produtos/<?= $produto['cod_anuncio'] ?>">
                                <button type="button" class="button-input text-light">Ver Mais</button>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>

    <div class="modal fade" id="modalFiltrar" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalFiltrarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-vermelho">
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
                                    <input class="form-check-input" type="checkbox" id="novo" name="estado[]" value="novo">
                                    <label class="form-check-label" for="novo">Novo</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="seminovo" name="estado[]" value="seminovo">
                                    <label class="form-check-label" for="seminovo">Seminovo</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="usado" name="estado[]" value="usado">
                                    <label class="form-check-label" for="usado">Usado</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 mx-4">
                            <p>Operação</p>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="venda" name="operacao[]" value="venda">
                                    <label class="form-check-label" for="venda">Venda</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="troca" name="operacao[]" value="troca">
                                    <label class="form-check-label" for="troca">Troca</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="ambos" name="operacao[]" value="ambos">
                                    <label class="form-check-label" for="ambos">Ambos</label>
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
        var data = $('form').serialize();

        $.ajax({
            type: 'POST',
            url: '/filtrar_produto',
            dataType: 'html',
            data: data,
            success: function(data) {
                $('form').trigger('reset');

                $('#quadro_produtos').html(data);
            }
        });
    });
</script>