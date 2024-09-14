<?php

namespace App\Controller;

use App\Controller;
use App\Model\AdModel;
use App\Model\ProductModel;
use App\Model\SolicitationModel;
use App\Model\UserModel;

class IndexController extends Controller
{
    public function index()
    {
        $product = new ProductModel();
        $products = $product->getRecentAds(5);

        $this->view->product_quantity = count($products);

        foreach ($products as &$product) {
            if (strlen($product['description']) > 250) {
                $product['description'] = substr($product['description'], 0, 250) . '...';
            }

            $product['photo_name'] = $_ENV['IMAGE_BASE_PATH'] . $product['photo_name'];
        }

        $this->view->products = $products;
        $this->view->login_warning = false;

        $this->sessionManager();

        if (isset($_SESSION['access_attempt']) && $_SESSION['access_attempt']) {
            $this->view->login_warning = true;
            unset($_SESSION['access_attempt']);
        }

        $this->render('index');
    }

    public function signin()
    {
        $this->authenticatePage(true);

        if (isset($_GET['hash'])) {
            $this->view->hash = filter_var($_GET['hash']);
        } else {
            $this->view->hash = '';
        }

        $this->render('signin');
    }

    public function signup()
    {
        $this->authenticatePage(true);
        $this->render('signup');
    }

    public function recover()
    {
        $this->authenticatePage(true);
        $this->render('recover');
    }

    public function resetPassword()
    {
        $this->authenticatePage(true);

        if (!isset($_GET['token']) || empty($_GET['token'])) {
            header('Location: /redefinir');
        }

        $this->view->token = $_GET['token'];

        $this->render('reset_password');
    }

    public function profile()
    {
        $this->authenticatePage();

        $user = new UserModel();
        $ad = new AdModel();
        $solicitation = new SolicitationModel();

        $this->view->user = $user->getProfile($_SESSION['user_id']);

        if ($this->view->user['ratings'] > 0) {
            $this->view->reputation = intval(round($this->view->user['score'] / $this->view->user['ratings']));
        } else {
            $this->view->reputation = 0;
        }

        $this->view->ads = $ad->getAdByUserId($_SESSION['user_id']);
        $this->view->solicitations = $solicitation->getSolicitationsByUserId($_SESSION['user_id']);

        $this->render('profile');
    }

    public function updateProfile()
    {
        $this->authenticatePage();

        $user = new UserModel();

        $_POST['user_id'] = $_SESSION['user_id'];

        if ($user->updateUser($_POST)) {
            $success = true;
        } else {
            $success = false;
            $this->error = 'Não foi possível atualizar o perfil de usuário!';
        }

        $this->output([
            'success' => $success,
            'message' => $this->error
        ]);
    }

    public function report()
    {
        $this->authenticatePage(false, true);

        if (!empty($_POST)) {
            $user = new UserModel();

            $headers = array_merge(['name'], array_keys($_POST));

            $this->view->table['headers'] = array_intersect_key([
                'name'              => 'Nome',
                'cb_birth_date'     => 'Data Nascimento',
                'cb_telephone'      => 'Telefone',
                'cb_email'          => 'Email',
                'cb_last_access'    => 'Último Acesso'
            ], array_flip($headers));

            $this->view->table['lines'] = $user->getUsersReport($headers);

            $this->render('report_table');
        } else {
            $this->render('report_form');
        }
    }
}
