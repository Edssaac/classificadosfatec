<?php if ($this->view->login_warning) { ?>
    <div class="alert alert-warning mx-3 mt-3" role="alert">
        Entre em sua <a href="/entrar" class="text-success">conta</a>
        ou <a href="/cadastrar" class="text-success">cadastre</a> uma nova para ter acesso a essa área.
    </div>
<?php }  ?>

<section class="bg-light rounded my-4">
    <?php if ($this->view->product_quantity > 0) { ?>
        <div class="swiper mySwiper mb-5">
            <div class="swiper-wrapper">
                <?php foreach ($this->view->products as $product) { ?>
                    <div class="swiper-slide pb-3">
                        <div class="w-100">
                            <h4 class="text-left mt-3">
                                <?php if ($this->view->login) { ?>
                                    <a href="/produtos/<?= $product['ad_id'] ?>"><?= $product['title'] ?></a>
                                <?php } else { ?>
                                    <?= $product['title'] ?>
                                <?php } ?>
                            </h4>
                            <hr>
                        </div>
                        <div class="swiper-portrait">
                            <?php if ($this->view->login) { ?>
                                <h4 class="text-left mt-3">
                                    <a href="/produtos/<?= $product['ad_id'] ?>">
                                        <img src="<?= $product['photo_name'] ?>" alt="anuncio">
                                    </a>
                                </h4>
                            <?php } else { ?>
                                <img src="<?= $product['photo_name'] ?>" alt="anuncio">
                            <?php } ?>
                        </div>
                        <div class="w-100">
                            <hr>
                            <p class="px-4 py-3 text-justify-content"><?= $product['description'] ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    <?php } ?>
</section>