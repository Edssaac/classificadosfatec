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
                                <?= $this->view->product['name'] ?>
                                <br>
                                <small><?= $this->view->product['ad_date'] ?></small>
                                <?php if ($this->view->product['expiry_date']) { ?>
                                    <br>
                                    <small>Disponível até: <?= $this->view->product['expiry_date'] ?></small>
                                <?php } ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-body pb-0">
                    <div class="row g-4 align-items-start">
                        <div class="col-md-4 text-center">
                            <img src="<?= $this->view->product['photo_name'] ?>" class="img-fluid rounded m-lg-2" alt="produto">
                        </div>
                        <div class="col-md-8">
                            <div>
                                <h5 class="card-title"><?= $this->view->product['title'] ?></h5>
                                <div class="row my-3 text-center">
                                    <?php if ($this->view->product['promotion']) { ?>
                                        <div class="col-6" title="preço">
                                            <i class="fa-solid fa-dollar-sign"></i>
                                            <small>R$</small>
                                            <span class="text-danger">
                                                <s><?= $this->view->product['price'] ?></s>
                                            </span>
                                        </div>
                                        <div class="col-6" title="promoção">
                                            <i class="fa-solid fa-tag"></i>
                                            <small>R$</small>
                                            <span class="text-success">
                                                <?= $this->view->product['discount'] ?>
                                            </span>
                                        </div>
                                    <?php } else { ?>
                                        <div class="col-6" title="preço">
                                            <i class="fa-solid fa-dollar-sign"></i>
                                            <small>R$</small>
                                            <span class="text-success"><?= $this->view->product['price'] ?></span>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div>
                                    <p>
                                        <small>Quantidade: <?= $this->view->product['quantity'] ?></small>
                                        <br>
                                        <small>Estado: <?= $this->view->product['condition'] ?></small>
                                        <br>
                                        <small>Situação: <?= $this->view->product['operation'] ?></small>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <p class="card-text text-justify-content"><?= $this->view->product['description'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row g-2">
                        <?php if ($this->view->product['user_id'] !== $_SESSION['user_id']) { ?>
                            <div class="col-12 col-lg-4 order-3 order-lg-0">
                                <a href="/produtos">
                                    <button type="button" class="button-input text-light w-100">Voltar</button>
                                </a>
                            </div>
                            <div class="col-6 col-lg-4">
                                <a href="https://wa.me/55<?= $this->view->product['phone'] ?>" target="_blank">
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
                                <a href="/produtos">
                                    <button type="button" class="button-input text-light w-100">Voltar</button>
                                </a>
                            </div>
                            <div class="col-12 col-lg-6">
                                <a href="https://wa.me/55<?= $this->view->product['phone'] ?>" target="_blank">
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
                        <button type="button" id="questions" class="button-input text-light selected">Dúvidas</button>
                    </div>
                    <div class="col-6">
                        <button type="button" id="ratings" class="button-input text-light">Avaliações</button>
                    </div>
                </div>
                <div class="mb-3">
                    <textarea name="comment" id="comment" rows="5" class="form-control input-grey-color text-justify-content"></textarea>
                </div>
                <div class="my-3">
                    <div id="question_block">
                        <div class="row">
                            <div class="col-12 col-lg-7"></div>
                            <div class="col-7 col-lg-2 text-end" id="spinner-question"></div>
                            <div class="col-5 col-lg-3 text-end">
                                <button type="button" id="question" class="button-input text-light">Perguntar</button>
                            </div>
                        </div>
                    </div>
                    <div id="review_block" class="d-none">
                        <div class="row">
                            <div class="col-12 col-lg-7 text-danger user-select-none">
                                <i class="fa-regular fa-star"></i>
                                <i class="fa-regular fa-star"></i>
                                <i class="fa-regular fa-star"></i>
                                <i class="fa-regular fa-star"></i>
                                <i class="fa-regular fa-star"></i>
                            </div>
                            <div class="col-7 col-lg-2 text-end" id="spinner-rate"></div>
                            <div class="col-5 col-lg-3 text-end">
                                <button type="button" id="rate" class="button-input text-light">Comentar</button>
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
                <div id="questions-block"></div>
                <div id="reviews-block" class="d-none"></div>
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
        questions();
        ratings();

        $.getScript('/assets/js/reasons.js', function() {
            for (let reason in reasons) {
                $('#reason').append(`<option value="${reason}"> ${reasons[reason]} </option>`);
            }
        });
    });

    let question_text = '';
    let review_text = '';

    $('#questions').click(function() {
        $('#ratings').toggleClass('selected', false);
        $('#questions').toggleClass('selected', true);

        $('#review_block').toggleClass('d-none', true);
        $('#question_block').toggleClass('d-none', false);

        $('#reviews-block').toggleClass('d-none', true);
        $('#questions-block').toggleClass('d-none', false);

        <?php if ($this->view->product['user_id'] !== $_SESSION['user_id']) { ?>
            review_text = $('#comment').val();
            $('#comment').val(question_text);
        <?php } else { ?>
            $('#comment').toggleClass('d-none', false);
            $('#review_block').toggleClass('d-none', true);
        <?php } ?>

        autosize();
    });

    $('#ratings').click(function() {
        $('#questions').toggleClass('selected', false);
        $('#ratings').toggleClass('selected', true);

        $('#question_block').toggleClass('d-none', true);
        $('#review_block').toggleClass('d-none', false);

        $('#questions-block').toggleClass('d-none', true);
        $('#reviews-block').toggleClass('d-none', false);

        <?php if ($this->view->product['user_id'] !== $_SESSION['user_id']) { ?>
            question_text = $('#comment').val();
            $('#comment').val(review_text);
        <?php } else { ?>
            $('#comment').toggleClass('d-none', true);
            $('#review_block').toggleClass('d-none', true);
        <?php } ?>

        autosize();
    });

    const stars = document.querySelectorAll('#review_block .fa-star');

    let old_rating = 0;

    stars.forEach((estrela, i) => {
        estrela.onclick = function() {
            let new_rating = i + 1;

            if (new_rating === old_rating) {
                new_rating = 0;
            }

            stars.forEach((estrela, j) => {
                if (new_rating >= j + 1) {
                    estrela.classList.replace('fa-regular', 'fa-solid');
                } else {
                    estrela.classList.replace('fa-solid', 'fa-regular');
                }
            });

            old_rating = new_rating;
        }
    });

    function resetStars() {
        stars.forEach((estrela) => {
            estrela.classList.replace('fa-solid', 'fa-regular');
            old_rating = 0;
        });
    }

    $('#question').on('click', question);

    function question() {
        let text_content = $.trim($('#comment').val());

        if (text_content === '') {
            return;
        }

        $('#question').prop('disabled', true);

        $.ajax({
            type: 'post',
            url: '/comentar_duvida',
            dataType: 'json',
            data: 'ad_id=' + <?= $this->view->product['ad_id'] ?> + '&text_content=' + text_content,
            beforeSend: function() {
                $('#spinner-question').html(
                    `<div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>`
                );
            },
            success: function() {
                $('#comment').val('');

                questions();
            },
            complete: function() {
                $('#question').prop('disabled', false);
                $('#spinner-question').html('');
            }
        });
    }

    function answer() {
        let duvida = this.dataset.duvida;
        let answer = $.trim($('.answer-' + duvida).val());

        if (answer === '') {
            return;
        }

        $.ajax({
            type: 'post',
            url: '/responder_duvida',
            dataType: 'json',
            data: 'duvida=' + duvida + '&answer=' + answer,
            beforeSend: function() {
                $('#status-' + duvida).html(
                    `<div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>`
                );
            },
            success: function() {
                questions();
            }
        });
    }

    function questions() {
        $.ajax({
            type: 'post',
            url: '/duvidas',
            dataType: 'html',
            data: 'ad_id=' + <?= $this->view->product['ad_id'] ?>,
            success: function(response) {
                $('#questions-block').html(response);
            },
            complete: function() {
                $('#status').html('');

                $('.answer').each(function(index) {
                    $(this).on('click', answer);
                });

                autosize();
            }
        });
    }

    $('#rate').on('click', rate);

    function rate() {
        let text_content = $.trim($('#comment').val());

        if (text_content === '') {
            return;
        }

        $('#rate').prop('disabled', true);

        $.ajax({
            type: 'post',
            url: '/avaliar',
            dataType: 'json',
            data: 'ad_id=' + <?= $this->view->product['ad_id'] ?> + '&text_content=' + text_content + '&rating=' + old_rating,
            beforeSend: function() {
                $('#spinner-rate').html(
                    `<div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>`
                );
            },
            success: function() {
                $('#comment').val('');

                ratings();
                resetStars();
            },
            complete: function() {
                $('#rate').prop('disabled', false);
                $('#spinner-rate').html('');
            }
        });
    }

    function ratings() {
        $.ajax({
            type: 'post',
            url: '/avaliacoes',
            dataType: 'html',
            data: 'ad_id=' + <?= $this->view->product['ad_id'] ?>,
            success: function(response) {
                $('#reviews-block').html(response);
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

        $('#questions-block textarea').on('input', function() {
            this.style.cssText = 'height:auto; padding:0;';
            this.style.cssText = 'height:' + (this.scrollHeight + 14) + 'px;';
        });
    }
</script>