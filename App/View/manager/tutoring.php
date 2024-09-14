<section class="bg-light rounded my-4 mb-5">
    <form action="/monitoria" method="post" class="py-4 mx-lg-5">
        <div class="text-center mb-5">
            <h3 class="text-danger">Editar Anúncio - Monitoria</h3>
        </div>
        <div class="mb-3 mx-4">
            <label for="title" class="form-label">Título</label>
            <input type="text" id="title" name="title" class="form-control input-grey-color" required value="<?= $this->view->tutoring['title'] ?>">
        </div>
        <div class="mb-3 mx-4">
            <label for="description" class="form-label">Descrição</label>
            <textarea name="description" id="description" rows="5" class="form-control input-grey-color" required><?= $this->view->tutoring['description'] ?></textarea>
        </div>
        <div class="mb-3 mx-4">
            <label for="subject" class="form-label">Matéria</label>
            <input type="text" id="subject" name="subject" class="form-control input-grey-color" required value="<?= $this->view->tutoring['subject'] ?>">
        </div>
        <div class="mx-3 row align-items-end">
            <div class="mb-3 col-6 col-md-3">
                <label for="price" class="form-label">Valor</label>
                <input type="text" id="price" name="price" class="form-control input-grey-color" required value="<?= number_format($this->view->tutoring['price'], 2) ?>">
            </div>
            <div class="mb-3 col-6 col-md-3">
                <label for="discount" class="form-label">Desconto</label>
                <input type="text" id="discount" name="discount" class="form-control input-grey-color" value="<?= number_format($this->view->tutoring['discount'], 2) ?>">
            </div>
            <div class="mb-3 col-6 col-md-3">
                <label for="discount_date" class="form-label">Data Limite de Desconto</label>
                <input type="datetime-local" id="discount_date" name="discount_date" class="form-control input-grey-color" value="<?= $this->view->tutoring['discount_date'] ?>">
            </div>
            <div class="mb-3 col-6 col-md-3">
                <label for="total" class="form-label">Total</label>
                <input type="text" id="total" name="total" class="form-control input-grey-color" disabled>
            </div>
        </div>
        <div class="mx-3 row my-5">
            <div class="mb-3 col-12 col-md-3">
                <label for="expiry_date" class="form-label">Data de Vencimento do Anúncio</label>
                <input type="date" id="expiry_date" name="expiry_date" class="form-control input-grey-color" value="<?= $this->view->tutoring['expiry_date'] ?>">
            </div>
        </div>
        <div class="mb-3 mx-4 mt-5">
            <div class="d-flex justify-content-start">
                <p class="fw-bold">Horários disponíveis</p>
            </div>
            <div id="available_schedules">
                <?php foreach ($this->view->schedules as $scheduled) { ?>
                    <div class="row available_schedule">
                        <div class="col-sm-4 col-md-6 mt-2 select-block">
                            <label class="form-label">Dia da Semana</label>
                            <select name="week_day[]" class="form-control" required>
                                <option value="1" <?= $scheduled['day'] == '1' ? 'selected' : '' ?>>Domingo</option>
                                <option value="2" <?= $scheduled['day'] == '2' ? 'selected' : '' ?>>Segunda-Feira</option>
                                <option value="3" <?= $scheduled['day'] == '3' ? 'selected' : '' ?>>Terça-Feira</option>
                                <option value="4" <?= $scheduled['day'] == '4' ? 'selected' : '' ?>>Quarta-Feira</option>
                                <option value="5" <?= $scheduled['day'] == '5' ? 'selected' : '' ?>>Quinta-Feira</option>
                                <option value="6" <?= $scheduled['day'] == '6' ? 'selected' : '' ?>>Sexta-Feira</option>
                                <option value="7" <?= $scheduled['day'] == '7' ? 'selected' : '' ?>>Sábado</option>
                            </select>
                        </div>
                        <div class="col-sm-4 col-md-3 mt-2 input-block">
                            <label class="form-label">Das</label>
                            <input type="time" name="from[]" class="form-control" required value="<?= $scheduled['from'] ?>">
                        </div>
                        <div class="col-sm-4 col-md-3 mt-2 input-block">
                            <label for="time_to" class="form-label">Até</label>
                            <input type="time" name="to[]" class="form-control" required value="<?= $scheduled['to'] ?>">
                        </div>
                        <hr class="mt-4">
                    </div>
                <?php } ?>
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
            <button type="submit" class="button-input text-light">Atualizar</button>
            <button type="button" id="excluir" class="button-input text-light">Excluir</button>
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

        updateTotal();
    });

    document.querySelector('#add_schedule').addEventListener('click', addNewSchedule);

    function addNewSchedule() {
        const new_field = document.querySelector('.available_schedule').cloneNode(true);
        const fields = new_field.querySelectorAll('input');

        fields.forEach(function(field) {
            field.value = '';
        });

        document.querySelector('#available_schedules').appendChild(new_field);
    }

    document.querySelector('#remove_schedule').addEventListener('click', removeSchedule);

    function removeSchedule() {
        const schedules = document.querySelectorAll('.available_schedule');

        if (schedules.length > 1) {
            $(schedules[schedules.length - 1]).remove()
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

    $('#excluir').on('click', function() {
        if (confirm('Tem certeza de que deseja excluir essa monitoria?')) {
            $.ajax({
                type: 'post',
                url: '/excluir_monitoria',
                dataType: 'json',
                data: 'ad_id=<?= $this->view->tutoring['ad_id'] ?>',
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
                                Monitoria excluída com sucesso!
                            </div>`
                        );

                        setTimeout(function() {
                            window.location.href = '/monitorias/';
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
            name: 'ad_id',
            value: '<?= $this->view->tutoring['ad_id'] ?>'
        });

        $.ajax({
            type: 'post',
            url: '/editar_monitoria',
            data: form_data,
            dataType: 'json',
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
                            Monitoria atualizada com sucesso!
                        </div>`
                    );

                    setTimeout(function() {
                        window.location.href = '/monitorias/<?= $this->view->tutoring['ad_id'] ?>';
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
                        Não foi possível atualizar a monitoria.
                    </div>`
                );
            }
        });
    });
</script>