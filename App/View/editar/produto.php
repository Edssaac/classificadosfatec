<section class="bg-light rounded my-4 mb-5">

    <form action="/produto" method="post" enctype="multipart/form-data" class="py-4 mx-lg-5">
        <div class="text-center mb-5">
            <h3 class="text-danger">Editar Anúncio - Produto</h3>
        </div>

        <div class="mb-3 mx-4">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" id="titulo" name="titulo" class="form-control input-cinza" required value="<?= $this->view->produto["titulo"] ?>">
        </div>

        <div class=" mb-3 mx-4">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" id="descricao" rows="5" class="form-control input-cinza" required><?= $this->view->produto["descricao"] ?></textarea>
        </div>

        <div class="mx-3 row align-items-end">
            <div class="mb-3 col-6 col-md-3">
                <label for="valor" class="form-label">Valor</label>
                <input type="text" id="valor" name="valor" class="form-control input-cinza" required value="<?= number_format($this->view->produto["valor"], 2) ?>">
            </div>
            <div class="mb-3 col-6 col-md-3">
                <label for="desconto" class="form-label">Desconto</label>
                <input type="text" id="desconto" name="desconto" class="form-control input-cinza" value="<?= number_format($this->view->produto["desconto"], 2) ?>">
            </div>
            <div class="mb-3 col-6 col-md-3">
                <label for="data_desconto" class="form-label">Data Limite de Desconto</label>
                <input type="datetime-local" id="data_desconto" name="data_desconto" max="2999-12-31T23:59" class="form-control input-cinza" value="<?= $this->view->produto["data_desconto"] ?>">
            </div>
            <div class="mb-3 col-6 col-md-3">
                <label for="total" class="form-label">Total</label>
                <input type="text" id="total" name="total" class="form-control input-cinza" disabled>
            </div>
        </div>

        <div class="mx-3 row my-5">
            <div class="mb-3 col-12 col-md-3">
                <label for="data_vencimento" class="form-label">Data de Vencimento do Anúncio</label>
                <input type="date" id="data_vencimento" name="data_vencimento" min="<?= date("Y-m-d") ?>" max="2999-12-31" class="form-control input-cinza" value="<?= $this->view->produto["data_vencimento"] ?>">
            </div>
        </div>

        <div class="mb-3 mx-4">
            <label for="foto" class="form-label">Imagem do Produto</label>
            <input type="file" accept="image/*" id="foto" name="foto" class="form-control input-cinza" required>
        </div>

        <div class="mx-3 row align-items-end">
            <div class="mb-3 col-sm-6 col-md-4">
                <label for="quantidade" class="form-label">Quantidade</label>
                <input type="number" id="quantidade" name="quantidade" min="1" class="form-control input-cinza" required value="<?= $this->view->produto["quantidade"] ?>">
            </div>
            <div class="mb-3 col-sm-6 col-md-4">
                <label for="estado" class="form-label">Estado do Produto</label>
                <select class="form-select input-cinza" id="estado" name="estado" required>
                    <option value="1" <?= $this->view->produto["estado"] == "1" ? "selected" : "" ?>>Novo</option>
                    <option value="2" <?= $this->view->produto["estado"] == "2" ? "selected" : "" ?>>Seminovo</option>
                    <option value="3" <?= $this->view->produto["estado"] == "3" ? "selected" : "" ?>>Usado</option>
                </select>
            </div>
            <div class="mb-3 col-sm-12 col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="operacao" id="venda" value="V" <?= $this->view->produto["operacao"] == "V" ? "checked" : "" ?>>
                    <label class="form-check-label" for="venda">Venda</label>
                </div>
                <div class="form-check">
                    <label class="form-check-label" for="troca">Troca</label>
                    <input class="form-check-input" type="radio" name="operacao" id="troca" value="T" <?= $this->view->produto["operacao"] == "T" ? "checked" : "" ?>>
                </div>
                <div class="form-check">
                    <label class="form-check-label" for="ambos">Ambos</label>
                    <input class="form-check-input" type="radio" name="operacao" id="ambos" value="A" <?= $this->view->produto["operacao"] == "A" ? "checked" : "" ?>>
                </div>
            </div>
        </div>

        <div id="status" class="mx-3 my-3">
        </div>

        <div class="d-grid gap-2 col-6 mx-auto">
            <button type="submit" class="button-input text-light">Atualizar</button>
            <button type="button" id="excluir" class="button-input text-light">Excluir</button>
        </div>
    </form>

</section>

<script>
    $(document).ready(function() {
        $("#valor").mask("#.##0,00", {
            reverse: true
        });
        $("#desconto").mask("#.##0,00", {
            reverse: true
        });
        atualizarTotal();
    });


    // Interação com o campo de desconto:
    $("#valor").change(function() {
        atualizarTotal();
    });

    $("#desconto").change(function() {
        $("#data_desconto").attr("required", $(this).val() != "");
        atualizarTotal();
    });

    function atualizarTotal() {
        var valor = parseFloat($("#valor").val().replace('.', '').replace(',', '.'));
        var desconto = parseFloat($("#desconto").val().replace('.', '').replace(',', '.'));
        var total = 0;

        if (!isNaN(valor) && !isNaN(desconto)) {
            total = valor - desconto;
        } else if (!isNaN(valor)) {
            total = valor;
        }

        $("#total").val(total.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
    }


    $("#excluir").on("click", function() {
        if (confirm("Tem certeza de que deseja excluir esse produto?")) {

            $.ajax({
                type: 'POST',
                url: "/excluir_produto",
                dataType: "html",
                data: "cod_anuncio=<?= $this->view->produto["cod_anuncio"] ?>",
                beforeSend: function() {
                    $("#status").html(
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
                        $("#status").html(
                            `<div class="alert alert-success" role="alert">
                        Produto excluído com sucesso!
                    </div>`
                        );

                        setTimeout(function() {
                            window.location.href = "/produtos/";
                        }, 1500);
                    } else {
                        $("#status").html(
                            `<div class="alert alert-danger" role="alert">
                        Atenção: ${data.mensagem}
                    </div>`
                        );
                    }
                },
                error: function(xhr) {
                    $("#status").html(
                        `<div class="alert alert-danger" role="alert">
                        Não foi possível excluir o produto.
                    </div>`
                    );
                },
            });
        }
    });

    // Final da interação.
    $("form").submit(function(e) {
        e.preventDefault();
        var data = new FormData(this);
        data.append("cod_anuncio", "<?= $this->view->produto["cod_anuncio"] ?>");

        $.ajax({
            type: 'POST',
            url: "/editar_produto",
            dataType: "JSON",
            data: data,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $("#status").html(
                    `<div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>`
                );
            },
            success: function(data) {
                if (data.sucesso) {
                    $("#status").html(
                        `<div class="alert alert-success" role="alert">
                        Produto atualizado com sucesso!
                    </div>`
                    );

                    setTimeout(function() {
                        window.location.href = "/produtos/<?= $this->view->produto["cod_anuncio"] ?>";
                    }, 1500);
                } else {
                    $("#status").html(
                        `<div class="alert alert-danger" role="alert">
                        Atenção: ${data.mensagem}
                    </div>`
                    );
                }
            },
            error: function(xhr) {
                $("#status").html(
                    `<div class="alert alert-danger" role="alert">
                        Não foi possível atualizar o produto.
                    </div>`
                );
            },
            complete: function() {

            },
        });
    });
</script>