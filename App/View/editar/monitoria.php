<section class="bg-light rounded my-4 mb-5">
    <form action="/monitoria" method="post" class="py-4 mx-lg-5">
        <div class="text-center mb-5">
            <h3 class="text-danger">Editar Anúncio - Monitoria</h3>
        </div>
        <div class="mb-3 mx-4">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" id="titulo" name="titulo" class="form-control input-cinza" required value="<?= $this->view->monitoria["titulo"] ?>">
        </div>
        <div class="mb-3 mx-4">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" id="descricao" rows="5" class="form-control input-cinza" required><?= $this->view->monitoria["descricao"] ?></textarea>
        </div>
        <div class="mb-3 mx-4">
            <label for="materia" class="form-label">Matéria</label>
            <input type="text" id="materia" name="materia" class="form-control input-cinza" required value="<?= $this->view->monitoria["materia"] ?>">
        </div>
        <div class="mx-3 row align-items-end">
            <div class="mb-3 col-6 col-md-3">
                <label for="valor" class="form-label">Valor</label>
                <input type="text" id="valor" name="valor" class="form-control input-cinza" required value="<?= number_format($this->view->monitoria["valor"], 2) ?>">
            </div>
            <div class="mb-3 col-6 col-md-3">
                <label for="desconto" class="form-label">Desconto</label>
                <input type="text" id="desconto" name="desconto" class="form-control input-cinza" value="<?= number_format($this->view->monitoria["desconto"], 2) ?>">
            </div>
            <div class="mb-3 col-6 col-md-3">
                <label for="data_desconto" class="form-label">Data Limite de Desconto</label>
                <input type="datetime-local" id="data_desconto" name="data_desconto" max="2999-12-31T23:59" class="form-control input-cinza" value="<?= $this->view->monitoria["data_desconto"] ?>">
            </div>
            <div class="mb-3 col-6 col-md-3">
                <label for="total" class="form-label">Total</label>
                <input type="text" id="total" name="total" class="form-control input-cinza" disabled>
            </div>
        </div>
        <div class="mx-3 row my-5">
            <div class="mb-3 col-12 col-md-3">
                <label for="data_vencimento" class="form-label">Data de Vencimento do Anúncio</label>
                <input type="date" id="data_vencimento" name="data_vencimento" min="<?= date("Y-m-d") ?>" max="2999-12-31" class="form-control input-cinza" value="<?= $this->view->monitoria["data_vencimento"] ?>">
            </div>
        </div>
        <div class="mb-3 mx-4 mt-5">
            <div class="d-flex justify-content-start">
                <p class="fw-bold">Horários disponíveis</p>
            </div>
            <div id="horarios_disponiveis">
                <?php foreach ($this->view->horarios as $horario) { ?>
                    <div class="row horario_disponivel">
                        <div class="col-sm-4 col-md-6 mt-2 select-block">
                            <label class="form-label">Dia da Semana</label>
                            <select name="dia_semana[]" class="form-control" required>
                                <option value="1" <?= $horario["dia"] == "1" ? "selected" : "" ?>>Domingo</option>
                                <option value="2" <?= $horario["dia"] == "2" ? "selected" : "" ?>>Segunda-Feira</option>
                                <option value="3" <?= $horario["dia"] == "3" ? "selected" : "" ?>>Terça-Feira</option>
                                <option value="4" <?= $horario["dia"] == "4" ? "selected" : "" ?>>Quarta-Feira</option>
                                <option value="5" <?= $horario["dia"] == "5" ? "selected" : "" ?>>Quinta-Feira</option>
                                <option value="6" <?= $horario["dia"] == "6" ? "selected" : "" ?>>Sexta-Feira</option>
                                <option value="7" <?= $horario["dia"] == "7" ? "selected" : "" ?>>Sábado</option>
                            </select>
                        </div>
                        <div class="col-sm-4 col-md-3 mt-2 input-block">
                            <label class="form-label">Das</label>
                            <input type="time" name="de_horario[]" class="form-control" required value="<?= $horario["de"] ?>">
                        </div>
                        <div class="col-sm-4 col-md-3 mt-2 input-block">
                            <label for="time_to" class="form-label">Até</label>
                            <input type="time" name="ate_horario[]" class="form-control" required value="<?= $horario["ate"] ?>">
                        </div>

                        <hr class="mt-4">
                    </div>
                <?php } ?>
            </div>
            <div class="d-flex justify-content-end gap-3 mb-5">
                <button type="button" class="button" id="remover_horario" name="adicionar_horario">
                    <i class="fa-solid fa-minus"></i> Remover Horário
                </button>

                <button type="button" class="button" id="adicionar_horario" name="adicionar_horario">
                    <i class="fa-solid fa-plus text-dark"></i> Novo Horário
                </button>
            </div>
        </div>
        <div id="status" class="mx-3 my-3"></div>
        <div class="d-grid gap-2 col-6 mx-auto">
            <button type="submit" class="button-input text-light">Atualizar</button>
            <button type="button" id="excluir" class="button-input text-light">Excluir</button>
        </div>
    </form>

</section>

<script>
    $(document).ready(function() {
        $('#valor').mask('#.##0,00', {
            reverse: true
        });

        $('#desconto').mask('#.##0,00', {
            reverse: true
        });

        atualizarTotal();
    });

    document.querySelector('#adicionar_horario').addEventListener('click', adicionarNovoHorario);

    function adicionarNovoHorario() {
        const novoCampo = document.querySelector('.horario_disponivel').cloneNode(true);
        const campos = novoCampo.querySelectorAll('input');

        campos.forEach(function(campo) {
            campo.value = '';
        });

        document.querySelector('#horarios_disponiveis').appendChild(novoCampo);
    }

    document.querySelector('#remover_horario').addEventListener('click', removerHorario);

    function removerHorario() {
        const horarios = document.querySelectorAll('.horario_disponivel');

        if (horarios.length > 1) {
            $(horarios[horarios.length - 1]).remove()
        }
    }

    $('#valor').change(function() {
        atualizarTotal();
    });

    $('#desconto').change(function() {
        $('#data_desconto').attr('required', $(this).val() != '');

        atualizarTotal();
    });

    function atualizarTotal() {
        const valor = parseFloat($('#valor').val().replace('.', '').replace(',', '.'));
        const desconto = parseFloat($('#desconto').val().replace('.', '').replace(',', '.'));

        let total = 0;

        if (!isNaN(valor) && !isNaN(desconto)) {
            total = valor - desconto;
        } else if (!isNaN(valor)) {
            total = valor;
        }

        $('#total').val(total.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.'));
    }

    $('#excluir').on('click', function() {
        if (confirm('Tem certeza de que deseja excluir essa monitoria?')) {
            $.ajax({
                type: 'POST',
                url: '/excluir_monitoria',
                dataType: 'html',
                data: 'cod_anuncio=<?= $this->view->monitoria['cod_anuncio'] ?>',
                beforeSend: function() {
                    $('#status').html(
                        `<div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>`
                    );
                },
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.sucesso) {
                        $('#status').html(
                            `<div class="alert alert-success" role="alert">
                                Monitoria excluída com sucesso!
                            </div>`
                        );

                        setTimeout(function() {
                            window.location.href = '/monitorias/';
                        }, 1500);
                    } else {
                        $('#status').html(
                            `<div class="alert alert-danger" role="alert">
                                Atenção: ${data.mensagem}
                            </div>`
                        );
                    }
                },
                error: function(xhr) {
                    $('#status').html(
                        `<div class="alert alert-danger" role="alert">
                            Não foi possível excluir a monitoria.
                        </div>`
                    );
                },
            });
        }
    });

    $('form').submit(function(e) {
        e.preventDefault();

        let form_data = $('form').serializeArray();

        form_data.push({
            name: 'cod_anuncio',
            value: '<?= $this->view->monitoria['cod_anuncio'] ?>'
        });

        $.ajax({
            type: 'POST',
            url: '/editar_monitoria',
            data: form_data,
            dataType: 'html',
            beforeSend: function() {
                $('#status').html(
                    `<div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>`
                );
            },
            success: function(data) {
                data = JSON.parse(data);

                if (data.sucesso) {
                    $('#status').html(
                        `<div class="alert alert-success" role="alert">
                            Monitoria atualizada com sucesso!
                        </div>`
                    );

                    setTimeout(function() {
                        window.location.href = '/monitorias/<?= $this->view->monitoria['cod_anuncio'] ?>';
                    }, 1500);
                } else {
                    $('#status').html(
                        `<div class="alert alert-danger" role="alert">
                            Atenção: ${data.mensagem}
                        </div>`
                    );
                }
            },
            error: function() {
                $('#status').html(
                    `<div class="alert alert-danger" role="alert">
                        Não foi possível atualizar a monitoria.
                    </div>`
                );
            }
        });
    });
</script>