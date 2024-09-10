<section class="bg-light rounded my-4 mb-5">
    <div id="quadro_solicitacoes" class="row mx-3 py-3">
        <?php if ($this->view->quantidade_solicitados === 0) { ?>
            <div class="alert alert-primary" role="alert">
                Não existe nenhuma solicitação sendo anunciada no momento.
            </div>
        <?php } else { ?>
            <?php foreach ($this->view->solicitados as $solicitado) { ?>
                <div class="col-12 col-lg-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <i class="fa-regular fa-circle-user usuario"></i>
                                </div>
                                <div class="col-10">
                                    <p class="my-0"><?= $solicitado['nome'] ?>
                                        <br><small><?= $solicitado['data'] ?></small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row g-4 text-center h-100">
                                <div class="col-md-12">
                                    <div>
                                        <h5 class="card-title"><?= $solicitado['titulo'] ?></h5>
                                        <hr>
                                        <div>
                                            <?php if (strlen($solicitado['descricao']) > 250) { ?>
                                                <p class="card-text justificado mb-3"><?= substr(nl2br($solicitado['descricao']), 0, 250) ?>...</p>
                                            <?php } else { ?>
                                                <p class="card-text justificado mb-3"><?= nl2br($solicitado['descricao']) ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="text-start">
                                <p class="mb-0"><b>Categoria: </b><?= ucfirst($solicitado['tipo']) ?></p>
                            </div>
                            <a href="/solicitados/<?= $solicitado['cod_solicitacao'] ?>">
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
                            <h4 class="modal-title">Filtrar Solicitações</h4>
                        </div>
                        <div class="mb-3 mx-4">
                            <p>Tipo de Solicitação</p>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="produto" name="tipo[]" value="produto">
                                    <label class="form-check-label" for="produto">Produtos</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="monitoria" name="tipo[]" value="monitoria">
                                    <label class="form-check-label" for="monitoria">Monitorias</label>
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
        const form_data = $('form').serialize();

        $.ajax({
            type: 'POST',
            url: '/filtrar_solicitacao',
            dataType: 'html',
            data: form_data,
            success: function(data) {
                $('form').trigger('reset');

                $('#quadro_solicitacoes').html(data);
            }
        });
    });
</script>