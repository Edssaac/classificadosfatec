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
                <input class="form-check-input" type="checkbox" value="1" name="cb_birth_date" id="cb_birth_date">
                <label class="form-check-label" for="cb_birth_date">
                    Data de Nascimento
                </label>
            </div>
            <div class="form-check mb-3 mx-4">
                <input class="form-check-input" type="checkbox" value="1" name="cb_telephone" id="cb_telephone">
                <label class="form-check-label" for="cb_telephone">
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
                <input class="form-check-input" type="checkbox" value="1" name="cb_last_access" id="cb_last_access">
                <label class="form-check-label" for="cb_last_access">
                    Último Acesso
                </label>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button type="submit" class="button-input text-light">Gerar</button>
            </div>
        </form>
    </div>
</section>