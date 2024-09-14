<section class="bg-light rounded my-4 mb-5">
    <form action="/produto" method="post" enctype="multipart/form-data" class="py-4 mx-lg-5">
        <div class="text-center mb-5">
            <h3 class="text-danger">Editar Anúncio - Produto</h3>
        </div>
        <div class="mb-3 mx-4">
            <label for="title" class="form-label">Título</label>
            <input type="text" id="title" name="title" class="form-control input-grey-color" required value="<?= $this->view->product['title'] ?>">
        </div>
        <div class=" mb-3 mx-4">
            <label for="description" class="form-label">Descrição</label>
            <textarea name="description" id="description" rows="5" class="form-control input-grey-color" required><?= $this->view->product['description'] ?></textarea>
        </div>
        <div class="mx-3 row align-items-end">
            <div class="mb-3 col-6 col-md-3">
                <label for="price" class="form-label">Valor</label>
                <input type="text" id="price" name="price" class="form-control input-grey-color" required value="<?= number_format($this->view->product['price'], 2) ?>">
            </div>
            <div class="mb-3 col-6 col-md-3">
                <label for="discount" class="form-label">Desconto</label>
                <input type="text" id="discount" name="discount" class="form-control input-grey-color" value="<?= number_format($this->view->product['discount'], 2) ?>">
            </div>
            <div class="mb-3 col-6 col-md-3">
                <label for="discount_date" class="form-label">Data Limite de Desconto</label>
                <input type="datetime-local" id="discount_date" name="discount_date" class="form-control input-grey-color" value="<?= $this->view->product['discount_date'] ?>">
            </div>
            <div class="mb-3 col-6 col-md-3">
                <label for="total" class="form-label">Total</label>
                <input type="text" id="total" name="total" class="form-control input-grey-color" disabled>
            </div>
        </div>
        <div class="mx-3 row my-5">
            <div class="mb-3 col-12 col-md-3">
                <label for="expiry_date" class="form-label">Data de Vencimento do Anúncio</label>
                <input type="date" id="expiry_date" name="expiry_date" class="form-control input-grey-color" value="<?= $this->view->product['expiry_date'] ?>">
            </div>
        </div>
        <div class="mb-3 mx-4">
            <label for="photo" class="form-label">Imagem do Produto</label>
            <input type="file" accept="image/*" id="photo" name="photo" class="form-control input-grey-color" required>
        </div>
        <div class="mx-3 row align-items-end">
            <div class="mb-3 col-sm-6 col-md-4">
                <label for="quantity" class="form-label">Quantidade</label>
                <input type="number" id="quantity" name="quantity" min="1" class="form-control input-grey-color" required value="<?= $this->view->product['quantity'] ?>">
            </div>
            <div class="mb-3 col-sm-6 col-md-4">
                <label for="condition" class="form-label">Estado do Produto</label>
                <select class="form-select input-grey-color" id="condition" name="condition" required>
                    <option value="1" <?= $this->view->product['condition'] == 'novo' ? 'selected' : '' ?>>Novo</option>
                    <option value="2" <?= $this->view->product['condition'] == 'seminovo' ? 'selected' : '' ?>>Seminovo</option>
                    <option value="3" <?= $this->view->product['condition'] == 'usado' ? 'selected' : '' ?>>Usado</option>
                </select>
            </div>
            <div class="mb-3 col-sm-12 col-md-4">
                <div class="form-check">
                    <label class="form-check-label" for="sale">Venda</label>
                    <input class="form-check-input" type="radio" name="operation" id="sale" value="venda" <?= $this->view->product['operation'] == 'venda' ? "checked" : '' ?>>
                </div>
                <div class="form-check">
                    <label class="form-check-label" for="change">Troca</label>
                    <input class="form-check-input" type="radio" name="operation" id="change" value="troca" <?= $this->view->product['operation'] == 'troca' ? "checked" : '' ?>>
                </div>
                <div class="form-check">
                    <label class="form-check-label" for="both">Ambos</label>
                    <input class="form-check-input" type="radio" name="operation" id="both" value="ambos" <?= $this->view->product['operation'] == 'ambos' ? "checked" : '' ?>>
                </div>
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
        if (confirm('Tem certeza de que deseja excluir esse produto?')) {
            $.ajax({
                type: 'post',
                url: '/excluir_produto',
                dataType: 'json',
                data: 'ad_id=<?= $this->view->product['ad_id'] ?>',
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
                                Produto excluído com sucesso!
                            </div>`
                        );

                        setTimeout(function() {
                            window.location.href = "/produtos/";
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
                            Não foi possível excluir o produto.
                        </div>`
                    );
                },
            });
        }
    });

    $('form').submit(function(e) {
        e.preventDefault();

        let form_data = new FormData(this);

        form_data.append('ad_id', '<?= $this->view->product['ad_id'] ?>');

        $.ajax({
            type: 'post',
            url: '/editar_produto',
            dataType: 'json',
            data: form_data,
            processData: false,
            contentType: false,
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
                            Produto atualizado com sucesso!
                        </div>`
                    );

                    setTimeout(function() {
                        window.location.href = "/produtos/<?= $this->view->product['ad_id'] ?>";
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
                        Não foi possível atualizar o produto.
                    </div>`
                );
            }
        });
    });
</script>