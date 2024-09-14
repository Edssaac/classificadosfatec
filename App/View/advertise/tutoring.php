<section class="bg-light rounded my-4 mb-5">
    <form action="/cadastrar" method="post" class="py-4 mx-lg-5">
        <div class="text-center mb-5">
            <h3 class="text-danger">Criar Anúncio - Monitoria</h3>
        </div>
        <div class="mb-3 mx-4">
            <label for="title" class="form-label">Título</label>
            <input type="text" id="title" name="title" class="form-control input-grey-color" required>
        </div>
        <div class="mb-3 mx-4">
            <label for="description" class="form-label">Descrição</label>
            <textarea name="description" id="description" rows="5" class="form-control input-grey-color" required></textarea>
        </div>
        <div class="mb-3 mx-4">
            <label for="subject" class="form-label">Matéria</label>
            <input type="text" id="subject" name="subject" class="form-control input-grey-color" required>
        </div>
        <div class="mx-3 row align-items-end">
            <div class="mb-3 col-6 col-md-3">
                <label for="price" class="form-label">Valor</label>
                <input type="text" id="price" name="price" class="form-control input-grey-color" required>
            </div>
            <div class="mb-3 col-6 col-md-3">
                <label for="discount" class="form-label">Desconto</label>
                <input type="text" id="discount" name="discount" class="form-control input-grey-color">
            </div>
            <div class="mb-3 col-6 col-md-3">
                <label for="discount_date" class="form-label">Data Limite de Desconto</label>
                <input type="datetime-local" id="discount_date" name="discount_date" class="form-control input-grey-color">
            </div>
            <div class="mb-3 col-6 col-md-3">
                <label for="total" class="form-label">Total</label>
                <input type="text" id="total" name="total" class="form-control input-grey-color" disabled>
            </div>
        </div>
        <div class="mx-3 row mt-5">
            <div class="mb-3 col-12 col-md-3">
                <label for="expiry_date" class="form-label">Data de Vencimento do Anúncio</label>
                <input type="date" id="expiry_date" name="expiry_date" class="form-control input-grey-color">
            </div>
        </div>
        <div class="mb-3 mx-4 mt-5">
            <div class="d-flex justify-content-start">
                <p class="fw-bold">Horários disponíveis</p>
            </div>
            <div id="available_schedules">
                <div class="row available_schedule">
                    <div class="col-sm-4 col-md-6 mt-2 select-block">
                        <span>Dia da Semana</span>
                        <select name="week_day[]" class="form-control" required>
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
                        <input type="time" name="from[]" class="form-control" required>
                    </div>
                    <div class="col-sm-4 col-md-3 mt-2 input-block">
                        <span>Até</span>
                        <input type="time" name="to[]" class="form-control" required>
                    </div>
                    <hr class="mt-4">
                </div>
            </div>
            <div class="d-flex justify-content-end gap-3 mb-5">
                <button type="button" class="button" id="remove_schedule" name="add_schedule">
                    <i class="fa-solid fa-minus"></i> Remover Horário
                </button>
                <button type="button" class="button" id="add_schedule" name="add_schedule">
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
        $('#price').mask('#.##0,00', {
            reverse: true
        });

        $('#discount').mask('#.##0,00', {
            reverse: true
        });
    });

    $('#add_schedule').on('click', addNewSchedule);
    $('#remove_schedule').on('click', removeSchedule);

    function addNewSchedule() {
        const new_field = $('.available_schedule').first().clone();

        new_field.find('input').val('');

        $('#available_schedules').append(new_field);
    }

    function removeSchedule() {
        const schedules = $('.available_schedule');

        if (schedules.length > 1) {
            schedules.last().remove();
        }
    }

    $('#price').change(function() {
        updateTotal();
    });

    $('#discount').change(function() {
        $('#discount_date').attr('required', $(this).val() != '');

        updateTotal();
    });

    function updateTotal() {
        const price = parseFloat($('#price').val().replace('.', '').replace(',', '.'));
        const discount = parseFloat($('#discount').val().replace('.', '').replace(',', '.'));

        let total = 0;

        if (!isNaN(price) && !isNaN(discount)) {
            total = price - discount;
        } else if (!isNaN(price)) {
            total = price;
        }

        $('#total').val(total.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.'));
    }

    $('form').submit(function(e) {
        e.preventDefault();

        const form_data = $('form').serialize();

        $.ajax({
            type: 'post',
            url: '/cadastrar_monitoria',
            dataType: 'json',
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
            success: function(response) {
                if (response.success) {
                    $('#status').html(
                        `<div class="alert alert-success" role="alert">
                            Monitoria anunciada com sucesso!
                        </div>`
                    );

                    $('form').trigger('reset');

                    setTimeout(function() {
                        window.location.href = '/monitorias';
                    }, 1000);
                } else {
                    $('#status').html(
                        `<div class="alert alert-danger" role="alert">
                            Atenção: ${response.message}
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