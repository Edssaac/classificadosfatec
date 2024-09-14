<section class="bg-light rounded my-4 mb-5">
    <div class="row mx-3 py-3">
        <div class="col-12 col-lg-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-2">
                            <i class="fa-regular fa-circle-user user-default-size"></i>
                        </div>
                        <div class="col-10">
                            <p class="my-0">
                                <?= $this->view->solicitation['name'] ?>
                                <br>
                                <small><?= $this->view->solicitation['solicitation_date'] ?></small>
                                <?php if ($this->view->solicitation['expiry_date']) { ?>
                                    <br>
                                    <small>Disponível até: <?= $this->view->solicitation['expiry_date'] ?></small>
                                <?php } ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-body pb-0">
                    <div class="row g-4 align-items-start">
                        <div class="col-md-12">
                            <div>
                                <h5 class="card-title"><?= $this->view->solicitation['title'] ?></h5>
                                <div>
                                    <p><small>Categoria: <?= $this->view->solicitation['type'] ?></small></p>
                                </div>
                                <div class="mb-3">
                                    <p class="card-text text-justify-content"><?= $this->view->solicitation['description'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row g-2">
                        <?php if ($this->view->solicitation['user_id'] !== $_SESSION['user_id']) { ?>
                            <div class="col-12 col-lg-4 order-3 order-lg-0">
                                <a href="/solicitados">
                                    <button type="button" class="button-input text-light w-100">Voltar</button>
                                </a>
                            </div>
                            <div class="col-6 col-lg-4">
                                <a href="https://wa.me/55<?= $this->view->solicitation['phone'] ?>" target="_blank">
                                    <button type="button" class="button-input text-light w-100">
                                        <i class="fa-brands fa-whatsapp"></i> Contatar
                                    </button>
                                </a>
                            </div>
                            <div class="col-6 col-lg-4">
                                <button type="button" class="button-input text-light w-100" data-bs-toggle="modal" data-bs-target="#modalDenounce">Denunciar</button>
                            </div>
                        <?php } else { ?>
                            <div class="col-12 col-lg-6 order-3 order-lg-0">
                                <a href="/solicitados">
                                    <button type="button" class="button-input text-light w-100">Voltar</button>
                                </a>
                            </div>
                            <div class="col-12 col-lg-6">
                                <a href="https://wa.me/55<?= $this->view->solicitation['phone'] ?>" target="_blank">
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
                <div class="text-start">
                    <p><b>Comentários</b></p>
                </div>
                <div class="mb-3">
                    <textarea name="comment" id="comment" rows="5" class="form-control input-grey-color text-justify-content"></textarea>
                </div>
                <div class="my-3">
                    <div id="comment-block">
                        <div class="row">
                            <div class="col-12 col-lg-7"></div>
                            <div class="col-7 col-lg-2 text-end" id="spinner-comment"></div>
                            <div class="col-5 col-lg-3 text-end">
                                <button type="button" id="comment" class="button-input text-light">Comentar</button>
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
                <div id="comments-block"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDenounce" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalDenounceLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-red-color">
                    <button type="button" class="btn-close d-block" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <h4 class="modal-title">Denúncia de Anúncio</h4>
                    </div>
                    <div class="mb-3 mx-4">
                        <label for="reason" class="form-label">Motivo</label>
                        <select class="form-select input-grey-color" id="reason" name="reason" required>
                            <option value="0" selected>Outros</option>
                        </select>
                    </div>
                    <div class="mb-3 mx-4">
                        <label for="message" class="form-label">Mensagem</label>
                        <textarea name="message" id="message" rows="5" class="form-control input-grey-color" required></textarea>
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
        comments();

        $.getScript('/assets/js/reasons.js', function() {
            for (let reason in reasons) {
                $('#reason').append(`<option value="${reason}"> ${reasons[reason]} </option>`);
            }
        });
    });

    $('#comment').on('click', comment);

    function comment() {
        let text_content = $.trim($('#comment').val());

        if (text_content === '') {
            return;
        }

        $('#comment').prop('disabled', true);

        $.ajax({
            type: 'post',
            url: '/comentar',
            dataType: 'json',
            data: 'solicitation_id=' + <?= $this->view->solicitation['solicitation_id'] ?> + '&text_content=' + text_content,
            beforeSend: function() {
                $('#spinner-comment').html(
                    `<div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>`
                );
            },
            success: function() {
                $('#comment').val('');

                comments();
            },
            complete: function() {
                $('#comentar').prop('disabled', false);
                $('#spinner-comment').html('');
            }
        });
    }

    function comments() {
        $.ajax({
            type: 'post',
            url: '/comentarios',
            dataType: 'html',
            data: 'solicitation_id=' + <?= $this->view->solicitation['solicitation_id'] ?>,
            success: function(response) {
                $('#comments-block').html(response);
            },
            complete: function() {
                $('#status').html('');

                autosize();
            }
        });
    }

    function autosize() {
        $('textarea:disabled').each(function() {
            this.style.cssText = 'height:' + (this.scrollHeight + 4) + 'px';
        });

        $('#comments-block textarea').on('input', function() {
            this.style.cssText = 'height:auto; padding:0;';
            this.style.cssText = 'height:' + (this.scrollHeight + 14) + 'px;';
        });
    }
</script>