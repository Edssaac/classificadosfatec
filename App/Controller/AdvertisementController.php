<?php

namespace App\Controller;

use App\Controller;
use App\Model\TutoringModel;
use App\Model\ProductModel;
use App\Model\SolicitationModel;

class AdvertisementController extends Controller
{
    public function products()
    {
        $this->authenticatePage();

        $product = new ProductModel();
        $products = $product->getProducts();

        $this->view->product_quantity = count($products);

        foreach ($products as &$product) {
            $product['price'] = str_replace('.', ',', $product['price']);

            if ($product['discount'] && (strtotime($product['discount_date']) > time())) {
                $product['promotion'] = true;
                $product['discount'] = str_replace('.', ',', (floatval($product['price']) - floatval($product['discount'])));
            } else {
                $product['promotion'] = false;
            }

            if (strlen($product['description']) > 250) {
                $product['description'] = substr($product['description'], 0, 250) . '...';
            }

            $product['photo_name'] = $_ENV['IMAGE_BASE_PATH'] . $product['photo_name'];
        }

        $this->view->products = $products;

        $this->view->filter = true;

        $this->render('products');
    }

    public function product()
    {
        $this->authenticatePage();

        $product = new ProductModel();

        $ad_id = array_filter(explode('/', $_SERVER['REQUEST_URI']))[2];

        $this->view->product = $product->getProduct($ad_id);

        if (!isset($this->view->product['ad_id'])) {
            header('Location: /produtos');
            exit;
        }

        $this->view->product['price'] = str_replace('.', ',', $this->view->product['price']);

        if ($this->view->product['discount'] && (strtotime($this->view->product['discount_date']) > time())) {
            $this->view->product['promotion'] = true;
            $this->view->product['discount'] = str_replace('.', ',', (floatval($this->view->product['price']) - floatval($this->view->product['discount'])));
        } else {
            $this->view->product['promotion'] = false;
        }

        if (strlen($this->view->product['description']) > 250) {
            $this->view->product['description'] = substr($this->view->product['description'], 0, 250) . '...';
        }

        $this->view->product['photo_name'] = $_ENV['IMAGE_BASE_PATH'] . $this->view->product['photo_name'];

        $this->view->product['condition'] = ucfirst($this->view->product['condition']);
        $this->view->product['operation'] = ucfirst($this->view->product['operation']);

        $this->view->product['phone'] = preg_replace('/[^0-9]/', '', $this->view->product['phone']);

        if ($this->view->product['expiry_date']) {
            $this->view->product['expiry_date'] = date_format(date_create($this->view->product['expiry_date']), 'd/m/Y');
        }

        $this->render('product');
    }

    public function tutorings()
    {
        $this->authenticatePage();

        $tutoring = new TutoringModel();
        $tutorings = $tutoring->getTutorings();

        $this->view->tutoring_quantity = count($tutorings);

        foreach ($tutorings as &$tutoring) {
            $tutoring['price'] = str_replace('.', ',', $tutoring['price']);

            if ($tutoring['discount'] && (strtotime($tutoring['discount_date']) > time())) {
                $tutoring['promotion'] = true;
                $tutoring['discount'] = str_replace('.', ',', (floatval($tutoring['price']) - floatval($tutoring['discount'])));
            } else {
                $tutoring['promotion'] = false;
            }

            if (strlen($tutoring['description']) > 250) {
                $tutoring['description'] = substr($tutoring['description'], 0, 250) . '...';
            }
        }

        $this->view->tutorings = $tutorings;

        $this->view->filter = true;

        $this->render('tutorings');
    }

    public function tutoring()
    {
        $this->authenticatePage();

        $tutoring = new TutoringModel();

        $ad_id = array_filter(explode('/', $_SERVER['REQUEST_URI']))[2];

        $this->view->tutoring = $tutoring->getTutoring($ad_id);

        if (!isset($this->view->tutoring['ad_id'])) {
            header('Location: /monitorias');
            exit;
        }

        $this->view->schedules = json_decode($this->view->tutoring['schedules'], true);

        $this->view->days = [
            1 => 'Domingo',
            2 => 'Segunda',
            3 => 'Terça',
            4 => 'Quarta',
            5 => 'Quinta',
            6 => 'Sexta',
            7 => 'Sábado'
        ];

        $this->view->tutoring['price'] = str_replace('.', ',', $this->view->tutoring['price']);

        if ($this->view->tutoring['discount'] && (strtotime($this->view->tutoring['discount_date']) > time())) {
            $this->view->tutoring['promotion'] = true;
            $this->view->tutoring['discount'] = str_replace('.', ',', (floatval($this->view->tutoring['price']) - floatval($this->view->tutoring['discount'])));
        } else {
            $this->view->tutoring['promotion'] = false;
        }

        if (strlen($this->view->tutoring['description']) > 250) {
            $this->view->tutoring['description'] = substr($this->view->tutoring['description'], 0, 250) . '...';
        }

        $this->view->tutoring['phone'] = preg_replace('/[^0-9]/', '', $this->view->tutoring['phone']);

        if ($this->view->tutoring['expiry_date']) {
            $this->view->tutoring['expiry_date'] = date_format(date_create($this->view->tutoring['expiry_date']), 'd/m/Y');
        }

        $this->render('tutoring');
    }

    public function solicitations()
    {
        $this->authenticatePage();

        $solicitation = new SolicitationModel();
        $solicitations = $solicitation->getSolicitations();

        $this->view->solicitation_quantity = count($solicitations);

        foreach ($solicitations as &$solicitation) {
            $solicitation['type'] = ucfirst($solicitation['type']);

            if (strlen($solicitation['description']) > 250) {
                $solicitation['description'] = substr($solicitation['description'], 0, 250) . '...';
            }
        }

        $this->view->solicitations = $solicitations;

        $this->view->filter = true;

        $this->render('solicitations');
    }

    public function solicitation()
    {
        $this->authenticatePage();

        $solicitation = new SolicitationModel();

        $solicitation_id = array_filter(explode('/', $_SERVER['REQUEST_URI']))[2];

        $this->view->solicitation = $solicitation->getSolicitation($solicitation_id);

        if (!isset($this->view->solicitation['solicitation_id'])) {
            header('Location: /solicitados');
            exit;
        }

        $this->view->solicitation['type'] = ucfirst($this->view->solicitation['type']);

        if (strlen($this->view->solicitation['description']) > 250) {
            $this->view->solicitation['description'] = substr($this->view->solicitation['description'], 0, 250) . '...';
        }

        $this->view->solicitation['phone'] = preg_replace('/[^0-9]/', '', $this->view->solicitation['phone']);

        if ($this->view->solicitation['expiry_date']) {
            $this->view->solicitation['expiry_date'] = date_format(date_create($this->view->solicitation['expiry_date']), 'd/m/Y');
        }

        $this->render('solicitation');
    }
}
