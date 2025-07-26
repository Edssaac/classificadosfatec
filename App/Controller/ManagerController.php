<?php

namespace App\Controller;

use App\Controller;
use App\Model\ProductModel;
use App\Model\TutoringModel;
use App\Model\SolicitationModel;
use Library\Upload;

class ManagerController extends Controller
{
    public function loadProduct()
    {
        $this->authenticatePage();

        $product = new ProductModel();

        $ad_id = array_filter(explode('/', $_SERVER['REQUEST_URI']))[3];
        $this->view->product = $product->getProduct($ad_id);

        if (empty($this->view->product) || $this->view->product['user_id'] != $this->user_id) {
            header('Location: /produtos');
            exit;
        }

        $this->render('product');
    }

    public function editProduct()
    {
        $this->authenticatePage();

        $product = new ProductModel();

        $photo = Upload::image($_FILES);

        $_POST['price'] = $this->numberFormat($_POST['price']);
        $_POST['discount'] = $this->numberFormat($_POST['discount']);
        $_POST['photo_name'] = $photo['name'];
        $_POST['photo_token'] = $photo['sha'];

        if ($product->updateProduct($_POST)) {
            $success = true;
        } else {
            $success = false;
            $this->error = 'Não foi possível atualizar o produto!';
        }
        $this->output([
            'success' => $success,
            'message' => $this->error
        ]);
    }

    public function deleteProduct()
    {
        $this->authenticatePage();

        $product = new ProductModel();

        if ($product->deleteProduct($_POST['ad_id'])) {
            $success = true;
        } else {
            $success = false;
            $this->error = 'Não foi possível atualizar o produto!';
        }

        $this->output([
            'success' => $success,
            'message' => $this->error
        ]);
    }

    public function loadTutoring()
    {
        $this->authenticatePage();

        $tutoring = new TutoringModel();

        $ad_id = array_filter(explode('/', $_SERVER['REQUEST_URI']))[3];
        $this->view->tutoring = $tutoring->getTutoring($ad_id);

        if (empty($this->view->tutoring) || $this->view->tutoring['user_id'] != $this->user_id) {
            header('Location: /monitorias');
            exit;
        }

        $this->view->schedules = json_decode($this->view->tutoring['schedules'], true);

        $this->render('tutoring');
    }

    public function editTutoring()
    {
        $this->authenticatePage();

        $tutoring = new TutoringModel();

        $schedules = [];

        foreach ($_POST['week_day'] as $key => $day) {
            $schedules[$key] = [
                'day'   => $day,
                'from'  => $_POST['from'][$key],
                'to'    => $_POST['to'][$key]
            ];
        }

        $_POST['price'] = $this->numberFormat($_POST['price']);
        $_POST['discount'] = $this->numberFormat($_POST['discount']);
        $_POST['schedules'] = json_encode($schedules);

        if ($tutoring->updateTutoring($_POST)) {
            $success = true;
        } else {
            $success = false;
            $this->error = 'Não foi possível atualizar anúncio!';
        }

        $this->output([
            'success' => $success,
            'message' => $this->error
        ]);
    }

    public function deleteTutoring()
    {
        $this->authenticatePage();

        $tutoring = new TutoringModel();

        if ($tutoring->deleteTutoring($_POST['ad_id'])) {
            $success = true;
        } else {
            $success = false;
            $this->error = 'Não foi possível excluir a monitoria!';
        }

        $this->output([
            'success' => $success,
            'message' => $this->error
        ]);
    }

    public function loadSolicitation()
    {
        $this->authenticatePage();

        $solicitation = new SolicitationModel();

        $solicitation_id = array_filter(explode('/', $_SERVER['REQUEST_URI']))[3];
        $this->view->solicitation = $solicitation->getSolicitation($solicitation_id);

        if (empty($this->view->solicitation) || $this->view->solicitation['user_id'] != $this->user_id) {
            header('Location: /solicitados');
            exit;
        }

        $this->render('solicitation');
    }

    public function editSolicitation()
    {
        $this->authenticatePage();

        $solicitation = new SolicitationModel();

        if ($solicitation->updateSolicitation($_POST)) {
            $success = true;
        } else {
            $success = false;
            $this->error = 'Não foi possível atualizar solicitação!';
        }

        $this->output([
            'success' => $success,
            'message' => $this->error
        ]);
    }

    public function deleteSolicitation()
    {
        $this->authenticatePage();

        $solicitation = new SolicitationModel();

        if ($solicitation->deleteSolicitation($_POST['solicitation_id'])) {
            $success = true;
        } else {
            $success = false;
            $this->error = 'Não foi possível excluir solicitação!';
        }

        $this->output([
            'success' => $success,
            'message' => $this->error
        ]);
    }
}
