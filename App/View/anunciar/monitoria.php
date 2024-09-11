<section class="bg-light rounded my-4 mb-5">
    <form action="/cadastrar" method="post" class="py-4 mx-lg-5">
        <div class="text-center mb-5">
            <h3 class="text-danger">Criar Anúncio - Monitoria</h3>
        </div>
        <div class="mb-3 mx-4">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" id="titulo" name="titulo" class="form-control input-cinza" required>
        </div>
        <div class="mb-3 mx-4">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" id="descricao" rows="5" class="form-control input-cinza" required></textarea>
        </div>
        <div class="mb-3 mx-4">
            <label for="materia" class="form-label">Matéria</label>
            <input type="text" id="materia" name="materia" class="form-control input-cinza" required>
        </div>
        <div class="mx-3 row align-items-end">
            <div class="mb-3 col-6 col-md-3">
                <label for="valor" class="form-label">Valor</label>
                <input type="text" id="valor" name="valor" class="form-control input-cinza" required>
            </div>
            <div class="mb-3 col-6 col-md-3">
                <label for="desconto" class="form-label">Desconto</label>
                <input type="text" id="desconto" name="desconto" class="form-control input-cinza">
            </div>
            <div class="mb-3 col-6 col-md-3">
                <label for="data_desconto" class="form-label">Data Limite de Desconto</label>
                <input type="datetime-local" id="data_desconto" name="data_desconto" class="form-control input-cinza">
            </div>
            <div class="mb-3 col-6 col-md-3">
                <label for="total" class="form-label">Total</label>
                <input type="text" id="total" name="total" class="form-control input-cinza" disabled>
            </div>
        </div>
        <div class="mx-3 row mt-5">
            <div class="mb-3 col-12 col-md-3">
                <label for="data_vencimento" class="form-label">Data de Vencimento do Anúncio</label>
                <input type="date" id="data_vencimento" name="data_vencimento" class="form-control input-cinza" required>
            </div>
        </div>
        <div class="mb-3 mx-4 mt-5">
            <div class="d-flex justify-content-start">
                <p class="fw-bold">Horários disponíveis</p>
            </div>
            <div id="horarios_disponiveis">
                <div class="row horario_disponivel">
                    <div class="col-sm-4 col-md-6 mt-2 select-block">
                        <span>Dia da Semana</span>
                        <select name="dia_semana[]" class="form-control" required>
                            <option value="1">Domingo</option>
                            <option value="2" selected>Segunda-Feira</option>
                            <option value="3">Terça-Feira</option>
                            <option value="4">Quarta-Feira</option>
                            <option value="5">Quinta-Feira</option>
                            <option value="6">Sexta-Feira</option>
                            <option value="7">Sábado</option>
                        </select>
                    </div>
                    <div class="col-sm-4 col-md-3 mt-2 input-block">
                        <span>Das</span>
                        <input type="time" name="de_horario[]" class="form-control" required>
                    </div>
                    <div class="col-sm-4 col-md-3 mt-2 input-block">
                        <span>Até</span>
                        <input type="time" name="ate_horario[]" class="form-control" required>
                    </div>
                    <hr class="mt-4">
                </div>
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
            <button type="submit" class="button-input text-light">Anunciar</button>
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
    });

    $('#adicionar_horario').on('click', adicionarNovoHorario);
    $('#remover_horario').on('click', removerHorario);

    function adicionarNovoHorario() {
        const novoCampo = $('.horario_disponivel').first().clone();

        novoCampo.find('input').val('');

        $('#horarios_disponiveis').append(novoCampo);
    }

    function removerHorario() {
        const horarios = $('.horario_disponivel');

        if (horarios.length > 1) {
            horarios.last().remove();
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

    $('form').submit(function(e) {
        e.preventDefault();

        const form_data = $('form').serialize();

        $.ajax({
            type: 'POST',
            url: "/cadastrar_monitoria",
            dataType: 'html',
            data: form_data,
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
                let response = JSON.parse(data);

                if (response.sucesso) {
                    $('#status').html(
                        `<div class="alert alert-success" role="alert">
                            Monitoria anunciada com sucesso!
                        </div>`
                    );

                    $('form').trigger('reset');

                    setTimeout(function() {
                        window.location.href = '/monitorias';
                    }, 1500);
                } else {
                    $('#status').html(
                        `<div class="alert alert-danger" role="alert">
                            Atenção: ${response.mensagem}
                        </div>`
                    );
                }
            },
            error: function() {
                $('#status').html(
                    `<div class="alert alert-danger" role="alert">
                        Não foi possível anunciar a monitoria.
                    </div>`
                );
            }
        });
    });
</script>