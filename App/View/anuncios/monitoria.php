<section class="bg-light rounded my-4 mb-5">
    <div class="row mx-3 py-3">
        <div class="col-12 col-lg-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-2">
                            <i class="fa-regular fa-circle-user usuario"></i>
                        </div>
                        <div class="col-10">
                            <p class="my-0"><?= $this->view->monitoria['nome'] ?>
                                <br><small><?= $this->view->monitoria['data_anunciada'] ?></small>
                                <?php if ($this->view->monitoria['data_vencimento'] !== "NULL") { ?>
                                    <br><small>Disponível até: <?= date_format(date_create($this->view->monitoria['data_vencimento']), "d/m/Y") ?></small>
                                <?php } ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-body pb-0">
                    <div class="row g-4 align-items-start">
                        <div class="col-md-12">
                            <div>
                                <h5 class="card-title"><?= $this->view->monitoria['titulo'] ?></h5>
                                <div class="row my-3 text-center">
                                    <?php if ($this->view->monitoria['desconto'] && (strtotime($this->view->monitoria['data_desconto']) > time())) { ?>
                                        <div class="col-6" title="preço">
                                            <i class="fa-solid fa-dollar-sign"></i>
                                            <small>R$</small>
                                            <span class="text-danger">
                                                <s><?= preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $this->view->monitoria['valor'])) ?></s>
                                            </span>
                                        </div>
                                        <div class="col-6" title="promoção">
                                            <i class="fa-solid fa-tag"></i>
                                            <small>R$</small>
                                            <span class="text-success">
                                                <?= preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $this->view->monitoria['valor'] - $this->view->monitoria['desconto'])) ?>
                                            </span>
                                        </div>
                                    <?php } else { ?>
                                        <div class="col-6" title="preço">
                                            <i class="fa-solid fa-dollar-sign"></i>
                                            <small>R$</small>
                                            <span class="text-success"><?= preg_replace("/(\d)(?=(\d{3})+(?!\d))/", "$1.", str_replace(".", ",", $this->view->monitoria['valor'])) ?></span>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div>
                                    <p><small>Matéria: <?= $this->view->monitoria['materia'] ?></small></p>
                                </div>
                                <div class="mb-3">
                                    <p class="card-text justificado"><?= nl2br($this->view->monitoria['descricao']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Dia da Semana</th>
                                    <th>Início</th>
                                    <th>Término</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($this->view->horarios as $horario) { ?>
                                    <tr>
                                        <td><?= $this->view->dias[$horario['dia']] ?></td>
                                        <td><?= $horario['de'] ?></td>
                                        <td><?= $horario['ate'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row g-2">
                        <?php if ($this->view->monitoria['cod_usuario'] !== $_SESSION['cod_usuario']) { ?>
                            <div class="col-12 col-lg-4 order-3 order-lg-0">
                                <a href="/produtos">
                                    <button type="button" class="button-input text-light w-100">Voltar</button>
                                </a>
                            </div>
                            <div class="col-6 col-lg-4">
                                <a href="https://wa.me/55<?= preg_replace("/[^0-9]/", "", $this->view->monitoria['telefone']); ?>" target="_blank">
                                    <button type="button" class="button-input text-light w-100">
                                        <i class="fa-brands fa-whatsapp"></i> Contatar
                                    </button>
                                </a>
                            </div>
                            <div class="col-6 col-lg-4">
                                <button type="button" class="button-input text-light w-100" data-bs-toggle="modal" data-bs-target="#modalDenunciar">Denunciar</button>
                            </div>
                        <?php } else { ?>
                            <div class="col-12 col-lg-6 order-3 order-lg-0">
                                <a href="/monitorias">
                                    <button type="button" class="button-input text-light w-100">Voltar</button>
                                </a>
                            </div>
                            <div class="col-12 col-lg-6">
                                <a href="https://wa.me/55<?= preg_replace("/[^0-9]/", "", $this->view->monitoria['telefone']); ?>" target="_blank">
                                    <button type="button" class="button-input text-light w-100">
                                        <i class="fa-brands fa-whatsapp"></i> Contatar
                                    </button>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <hr class="d-lg-none mt-5">
        <div class="col-12 col-lg-6 mb-3 bg-white rounded">
            <div class="py-2">
                <div class="row mb-3 text-center">
                    <div class="col-6">
                        <button type="button" id="duvidas" class="button-input text-light selected">Dúvidas</button>
                    </div>
                    <div class="col-6">
                        <button type="button" id="avaliacoes" class="button-input text-light">Avaliações</button>
                    </div>
                </div>
                <div class="mb-3">
                    <textarea name="comentario" id="comentario" rows="5" class="form-control input-cinza justificado"></textarea>
                </div>
                <div class="my-3">
                    <div id="bloco_duvida">
                        <div class="row">
                            <div class="col-12 col-lg-7"></div>
                            <div class="col-7 col-lg-2 text-end" id="spinner-perguntar"></div>
                            <div class="col-5 col-lg-3 text-end">
                                <button type="button" id="perguntar" class="button-input text-light">Perguntar</button>
                            </div>
                        </div>
                    </div>
                    <div id="bloco_avaliacao" class="d-none">
                        <div class="row">
                            <div class="col-12 col-lg-7 text-danger user-select-none">
                                <i class="fa-regular fa-star"></i>
                                <i class="fa-regular fa-star"></i>
                                <i class="fa-regular fa-star"></i>
                                <i class="fa-regular fa-star"></i>
                                <i class="fa-regular fa-star"></i>
                            </div>
                            <div class="col-7 col-lg-2 text-end" id="spinner-avaliar"></div>
                            <div class="col-5 col-lg-3 text-end">
                                <button type="button" id="avaliar" class="button-input text-light">Comentar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div id="status">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div id="bloco_duvidas"></div>
                <div id="bloco_avaliacoes" class="d-none"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDenunciar" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalDenunciarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-vermelho">
                    <button type="button" class="btn-close d-block" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <h4 class="modal-title">Denúncia de Anúncio</h4>
                    </div>
                    <div class="mb-3 mx-4">
                        <label for="motivo" class="form-label">Motivo</label>
                        <select class="form-select input-cinza" id="motivo" name="motivo" required>
                            <option value="0" selected>Outros</option>
                        </select>
                    </div>
                    <div class="mb-3 mx-4">
                        <label for="mensagem" class="form-label">Mensagem</label>
                        <textarea name="mensagem" id="mensagem" rows="5" class="form-control input-cinza" required></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="button-input text-light" id="denunciar" data-bs-dismiss="modal">Enviar</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        duvidas();
        avaliacoes();

        $.getScript('/assets/js/motivos.js', function() {
            for (let motivo in motivos) {
                $('#motivo').append(`<option value="${motivo}"> ${motivos[motivo]} </option>`);
            }
        });
    });

    let texto_duvida = "";
    let texto_avaliacao = "";

    $('#duvidas').click(function() {
        $('#avaliacoes').toggleClass('selected', false);
        $('#duvidas').toggleClass('selected', true);

        $('#bloco_avaliacao').toggleClass('d-none', true);
        $('#bloco_duvida').toggleClass('d-none', false);

        $('#bloco_avaliacoes').toggleClass('d-none', true);
        $('#bloco_duvidas').toggleClass('d-none', false);

        <?php if ($this->view->monitoria['cod_usuario'] !== $_SESSION['cod_usuario']) { ?>
            texto_avaliacao = $('#comentario').val();
            $('#comentario').val(texto_duvida);
        <?php } else { ?>
            $('#comentario').toggleClass('d-none', false);
            $('#bloco_avaliacao').toggleClass('d-none', true);
        <?php } ?>

        autosize();
    });

    $('#avaliacoes').click(function() {
        $('#duvidas').toggleClass('selected', false);
        $('#avaliacoes').toggleClass('selected', true);

        $('#bloco_duvida').toggleClass('d-none', true);
        $('#bloco_avaliacao').toggleClass('d-none', false);

        $('#bloco_duvidas').toggleClass('d-none', true);
        $('#bloco_avaliacoes').toggleClass('d-none', false);

        <?php if ($this->view->monitoria['cod_usuario'] !== $_SESSION['cod_usuario']) { ?>
            texto_duvida = $('#comentario').val();
            $('#comentario').val(texto_avaliacao);
        <?php } else { ?>
            $('#comentario').toggleClass('d-none', true);
            $('#bloco_avaliacao').toggleClass('d-none', true);
        <?php } ?>

        autosize();
    });

    const estrelas = document.querySelectorAll('#bloco_avaliacao .fa-star');

    let classificacao_anterior = 0;

    estrelas.forEach((estrela, i) => {
        estrela.onclick = function() {
            let classificacao_atual = i + 1;

            if (classificacao_atual === classificacao_anterior) {
                classificacao_atual = 0;
            }

            estrelas.forEach((estrela, j) => {
                if (classificacao_atual >= j + 1) {
                    estrela.classList.replace('fa-regular', 'fa-solid');
                } else {
                    estrela.classList.replace('fa-solid', 'fa-regular');
                }
            });

            classificacao_anterior = classificacao_atual;
        }
    });

    function limparEstrelas() {
        estrelas.forEach((estrela, j) => {
            estrela.classList.replace('fa-solid', 'fa-regular');
            classificacao_anterior = 0;
        });
    }

    $('#perguntar').on('click', perguntar);

    function perguntar() {
        let texto = $.trim($('#comentario').val());

        if (texto === '') {
            return;
        }

        $('#perguntar').prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: '/comentar_duvida',
            dataType: 'html',
            data: 'anuncio=' + <?= $this->view->monitoria['cod_anuncio'] ?> + '&texto=' + texto,
            beforeSend: function() {
                $('#spinner-perguntar').html(
                    `<div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>`
                );
            },
            success: function(data) {
                $('#comentario').val('');

                duvidas();
            },
            complete: function() {
                $('#perguntar').prop('disabled', false);
                $('#spinner-perguntar').html('');
            }
        });
    }

    function responder() {
        let duvida = this.dataset.duvida;
        let resposta = $.trim($('.resposta-' + duvida).val());

        if (resposta === '') {
            return;
        }

        $.ajax({
            type: 'POST',
            url: '/responder_duvida',
            dataType: 'html',
            data: 'duvida=' + duvida + '&resposta=' + resposta,
            beforeSend: function() {
                $('#status-' + duvida).html(
                    `<div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>`
                );
            },
            success: function(data) {
                duvidas();
            }
        });
    }

    function duvidas() {
        $.ajax({
            type: 'POST',
            url: '/duvidas',
            dataType: 'html',
            data: 'anuncio=' + <?= $this->view->monitoria['cod_anuncio'] ?>,
            success: function(data) {
                $('#bloco_duvidas').html(data);
            },
            complete: function() {
                $('#status').html('');

                $('.responder').each(function(index) {
                    $(this).on('click', responder);
                });

                autosize();
            }
        });
    }

    $('#avaliar').on('click', avaliar);

    function avaliar() {
        let texto = $.trim($('#comentario').val());

        if (texto === '') {
            return;
        }

        $('#avaliar').prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: '/avaliar',
            dataType: 'html',
            data: 'anuncio=' + <?= $this->view->monitoria['cod_anuncio'] ?> + '&texto=' + texto + '&avaliacao=' + classificacao_anterior,
            beforeSend: function() {
                $('#spinner-avaliar').html(
                    `<div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>`
                );
            },
            success: function(data) {
                $('#comentario').val('');

                avaliacoes();
                limparEstrelas();
            },
            complete: function() {
                $('#avaliar').prop('disabled', false);
                $('#spinner-avaliar').html('');
            }
        });
    }

    function avaliacoes() {
        $.ajax({
            type: 'POST',
            url: '/avaliacoes',
            dataType: 'html',
            data: 'anuncio=' + <?= $this->view->monitoria['cod_anuncio'] ?>,
            success: function(data) {
                $('#bloco_avaliacoes').html(data);
            },
            complete: function() {
                autosize();
            }
        });
    }

    function autosize() {
        $('textarea:disabled').each(function() {
            this.style.cssText = 'height:' + (this.scrollHeight + 4) + 'px';
        });

        $('#bloco_duvidas textarea').on('input', function() {
            this.style.cssText = 'height:auto; padding:0;';
            this.style.cssText = 'height:' + (this.scrollHeight + 14) + 'px;';
        });
    }
</script>