<section class="bg-light rounded my-4 mb-5">
    <div id="quadro_anuncios" class="row mx-3 py-3">
        <?= $this->view->anuncios ?>
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
                            <h4 class="modal-title">Filtrar Anúncios</h4>
                        </div>
                        <div class="mb-3 mx-4">
                            <p>Tipo de Anúncio</p>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="produto" name="tipo[]" value="produto">
                                    <label class="form-check-label" for="produto">Produtos</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="monitoria" name="tipo[]" value="monitoria">
                                    <label class="form-check-label" for="monitoria">Monitorias</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="solicitacao" name="tipo[]" value="solicitacao">
                                    <label class="form-check-label" for="solicitacao">Solicitações</label>
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
        const data = $('form').serializeArray();

        data.push({
            name: 'filtrar',
            value: "<?= $this->view->pesquisar ?>"
        });

        if ($('input:checked').length === 0) {
            return;
        }

        $.ajax({
            type: 'POST',
            url: '/filtrar_pesquisa',
            dataType: 'html',
            data: data,
            success: function(data) {
                $('form').trigger('reset');
                $('#quadro_anuncios').html(data);
            }
        });
    });
</script>