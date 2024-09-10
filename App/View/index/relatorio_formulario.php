<section class="bg-light rounded my-4">
    <div class="mx-3 py-4">
        <h3 class="text-center text-danger mb-4">Relatório de Usuários</h3>
        <form action="/relatorio" method="post" class="py-4 mx-lg-5">
            <div class="form-check mb-3 mx-4">
                <input class="form-check-input" type="checkbox" value="1" name="cb_nome" id="cb_nome">
                <label class="form-check-label" for="cb_nome">
                    Nome Completo
                </label>
            </div>
            <div class="form-check mb-3 mx-4">
                <input class="form-check-input" type="checkbox" value="1" name="cb_data_nascimento" id="cb_data_nascimento">
                <label class="form-check-label" for="cb_data_nascimento">
                    Data de Nascimento
                </label>
            </div>
            <div class="form-check mb-3 mx-4">
                <input class="form-check-input" type="checkbox" value="1" name="cb_telefone" id="cb_telefone">
                <label class="form-check-label" for="cb_telefone">
                    Telefone
                </label>
            </div>
            <div class="form-check mb-3 mx-4">
                <input class="form-check-input" type="checkbox" value="1" name="cb_email" id="cb_email">
                <label class="form-check-label" for="cb_email">
                    Email
                </label>
            </div>
            <div class="form-check mb-3 mx-4">
                <input class="form-check-input" type="checkbox" value="1" name="cb_data_acesso" id="cb_data_acesso">
                <label class="form-check-label" for="cb_data_acesso">
                    Último Acesso
                </label>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" class="button-input text-light">Gerar</button>
            </div>
        </form>
    </div>
</section>