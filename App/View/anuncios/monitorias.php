<section class="bg-light rounded my-4 mb-5">
    <div id="quadro_monitorias" class="row mx-3 py-3">
        <?php if ($this->view->quantidade_monitorias === 0) { ?>
            <div class="alert alert-primary" role="alert">
                Não existe nenhuma monitoria sendo anunciada no momento.
            </div>
        <?php } else { ?>
            <?php foreach ($this->view->monitorias as $monitoria) { ?>
                <div class="col-12 col-lg-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <i class="fa-regular fa-circle-user usuario"></i>
                                </div>
                                <div class="col-10">
                                    <p class="my-0">
                                        <?= $monitoria['nome'] ?>
                                        <br>
                                        <small><?= $monitoria['data_anunciada'] ?></small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row g-4 text-center h-100">
                                <div class="col-md-12">
                                    <div>
                                        <h5 class="card-title"><?= $monitoria['titulo'] ?></h5>
                                        <div class="row my-3 text-center">
                                            <?php if ($monitoria['desconto'] && (strtotime($monitoria['data_desconto']) > time())) { ?>
                                                <div class="col-6" title="preço">
                                                    <i class="fa-solid fa-dollar-sign"></i>
                                                    <small>R$</small>
                                                    <span class="text-danger">
                                                        <s><?= preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $monitoria['valor'])) ?></s>
                                                    </span>
                                                </div>
                                                <div class="col-6" title="promoção">
                                                    <i class="fa-solid fa-tag"></i>
                                                    <small>R$</small>
                                                    <span class="text-success">
                                                        <?= preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $monitoria['valor'] - $monitoria['desconto'])) ?>
                                                    </span>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-6" title="preço">
                                                    <i class="fa-solid fa-dollar-sign"></i>
                                                    <small>R$</small>
                                                    <span class="text-success"><?= preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $monitoria['valor'])) ?></span>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <hr>
                                        <?php if (strlen($monitoria['descricao']) > 250) { ?>
                                            <p class="card-text justificado mb-3"><?= substr(nl2br($monitoria['descricao']), 0, 250) ?>...</p>
                                        <?php } else { ?>
                                            <p class="card-text justificado mb-3"><?= nl2br($monitoria['descricao']) ?></p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="text-start">
                                <p class="mb-0"><b>Matéria: </b><?= $monitoria['materia'] ?></p>
                            </div>
                            <a href="/monitorias/<?= $monitoria['cod_anuncio'] ?>">
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
                            <h4 class="modal-title">Filtrar Monitorias</h4>
                        </div>
                        <div class="mb-3 mx-4">
                            <label for="materia" class="form-label">Matéria</label>
                            <input type="text" class="form-control input-cinza" id="materia" name="materia">
                        </div>
                        <div class="mb-3 mx-4">
                            <p>Dias Disponíveis</p>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="segunda" name="dias[]" value="1">
                                    <label class="form-check-label" for="segunda">Segunda-Feira</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="terca" name="dias[]" value="2">
                                    <label class="form-check-label" for="terca">Terça-Feira</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="quarta" name="dias[]" value="3">
                                    <label class="form-check-label" for="quarta">Quarta-Feira&emsp;</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="quinta" name="dias[]" value="4">
                                    <label class="form-check-label" for="quinta">Quinta-Feira</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="sexta" name="dias[]" value="5">
                                    <label class="form-check-label" for="sexta">Sexta-Feira&emsp;&nbsp;&nbsp;</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="sabado" name="dias[]" value="6">
                                    <label class="form-check-label" for="sabado">Sábado&emsp;&emsp;</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="domingo" name="dias[]" value="7">
                                    <label class="form-check-label" for="domingo">Domingo&emsp;&emsp;</label>
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
            url: '/filtrar_monitoria',
            dataType: 'html',
            data: form_data,
            success: function(data) {
                $('form').trigger('reset');
                $('#quadro_monitorias').html(data);
            }
        });
    });
</script>