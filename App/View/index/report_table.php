<section class="bg-light rounded my-4 mb-5">
    <div class="mx-3 py-4">
        <h3 class="text-center text-danger mb-4">Relatório de Usuários</h3>
        <a href="/relatorio" title="Retornar">
            <i class="fa-solid fa-arrow-left text-danger"></i>
        </a>
        <div class="table-responsive mt-4">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <?php foreach ($this->view->table['headers'] as $header) { ?>
                            <th scope="col"><?= $header ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->view->table['lines'] as $line) { ?>
                        <tr>
                            <?php foreach ($line as $data) { ?>
                                <td><?= $data ?></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</section>