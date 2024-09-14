<?php

namespace App\Controller;

use App\Controller;
use App\Model\ProductModel;
use App\Model\TutoringModel;
use App\Model\SolicitationModel;
use Library\Upload;

class AdvertiseController extends Controller
{
    public function product()
    {
        $this->authenticatePage();
        $this->render('product');
    }

    public function tutoring()
    {
        $this->authenticatePage();
        $this->render('tutoring');
    }

    public function solicitation()
    {
        $this->authenticatePage();
        $this->render('solicitation');
    }

    public function registerProduct()
    {
        $this->authenticatePage();

        $product = new ProductModel();

        $photo = Upload::image($_FILES);

        $_POST['user_id'] = $_SESSION['user_id'];
        $_POST['price'] = $this->numberFormat($_POST['price']);
        $_POST['discount'] = $this->numberFormat($_POST['discount']);
        $_POST['photo_name'] = $photo['name'];
        $_POST['photo_token'] = $photo['sha'];

        if ($product->insertProduct($_POST)) {
            $success = true;
        } else {
            $success = false;
            $this->error = 'Não foi possível cadastrar anúncio!';
        }

        $this->output([
            'success' => $success,
            'message' => $this->error
        ]);
    }

    public function registerTutoring()
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

        $_POST['user_id'] = $_SESSION['user_id'];
        $_POST['price'] = $this->numberFormat($_POST['price']);
        $_POST['discount'] = $this->numberFormat($_POST['discount']);
        $_POST['schedules'] = json_encode($schedules);

        if ($tutoring->insertTutoring($_POST)) {
            $success = true;
        } else {
            $success = false;
            $this->error = 'Não foi possível cadastrar anúncio!';
        }

        $this->output([
            'success' => $success,
            'message' => $this->error
        ]);
    }

    public function registerSolicitation()
    {
        $this->authenticatePage();

        $solicitation = new SolicitationModel();

        $_POST['user_id'] = $_SESSION['user_id'];

        if ($solicitation->insertSolicitation($_POST)) {
            $success = true;
        } else {
            $success = false;
            $this->error = 'Não foi possível cadastrar solicitação!';
        }

        $this->output([
            'success' => $success,
            'message' => $this->error
        ]);
    }
}
