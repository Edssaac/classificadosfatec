<?php

namespace App\Controller;

use App\Controller;
use App\Model\ProductModel;
use App\Model\TutoringModel;
use App\Model\SolicitationModel;

class FilterController extends Controller
{
    public function filterProducts()
    {
        $this->authenticatePage();

        $product_model = new ProductModel();

        $filters = [];

        if (!empty($_POST['condition'])) {
            $filters['condition'] = $_POST['condition'];
        }

        if (!empty($_POST['operation'])) {
            $filters['operation'] = $_POST['operation'];
        }

        $products = $product_model->getProducts($filters);

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

        $this->output([
            'products' => $products
        ]);
    }

    public function filterTutorings()
    {
        $this->authenticatePage();

        $tutoring_model = new TutoringModel();

        $filters = [];

        if (!empty($_POST['subject'])) {
            $filters['subject'] = $_POST['subject'];
        }

        if (!empty($_POST['days'])) {
            $filters['days'] = $_POST['days'];
        }

        $tutorings = $tutoring_model->getTutorings($filters);

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

        $this->output([
            'tutorings' => $tutorings
        ]);
    }

    public function filterSolicitations()
    {
        $this->authenticatePage();

        $solicitation_model = new SolicitationModel();

        $filters = [];

        if (!empty($_POST['type'])) {
            $filters['type'] = $_POST['type'];
        }

        $solicitations = $solicitation_model->getSolicitations($filters);

        foreach ($solicitations as &$solicitation) {
            $solicitation['type'] = ucfirst($solicitation['type']);

            if (strlen($solicitation['description']) > 250) {
                $solicitation['description'] = substr($solicitation['description'], 0, 250) . '...';
            }
        }

        $this->output([
            'solicitations' => $solicitations
        ]);
    }

    public function search()
    {
        $this->authenticatePage();

        $product_model = new ProductModel();
        $tutoring_model = new TutoringModel();
        $solicitation_model = new SolicitationModel();

        $filters = [];

        if (!empty($_POST['search'])) {
            $filters['title'] = $_POST['search'];
        }

        $products = $product_model->getProducts($filters);
        $tutorings = $tutoring_model->getTutorings($filters);
        $solicitations = $solicitation_model->getSolicitations($filters);

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

        foreach ($solicitations as &$solicitation) {
            $solicitation['type'] = ucfirst($solicitation['type']);

            if (strlen($solicitation['description']) > 250) {
                $solicitation['description'] = substr($solicitation['description'], 0, 250) . '...';
            }
        }

        $this->view->filter = true;
        $this->view->search = $_POST['search'] ?? '';
        $this->view->advertisement['products'] = $products;
        $this->view->advertisement['tutorings'] = $tutorings;
        $this->view->advertisement['solicitations'] = $solicitations;
        $this->view->advertisement['advertisements'] = count($products) + count($tutorings) + count($solicitations);

        $this->render('advertisement');
    }

    public function filterSearch()
    {
        $this->authenticatePage();

        $filters = [];

        if (!empty($_POST['search'])) {
            $filters['title'] = $_POST['search'];
        }

        if (isset($_POST['type'])) {
            if (in_array('product', $_POST['type'])) {
                $product_model = new ProductModel();
                $products = $product_model->getProducts($filters);

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
            }

            if (in_array('tutoring', $_POST['type'])) {
                $tutoring_model = new TutoringModel();
                $tutorings = $tutoring_model->getTutorings($filters);

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
            }

            if (in_array('solicitation', $_POST['type'])) {
                $solicitation_model = new SolicitationModel();
                $solicitations = $solicitation_model->getSolicitations($filters);

                foreach ($solicitations as &$solicitation) {
                    $solicitation['type'] = ucfirst($solicitation['type']);

                    if (strlen($solicitation['description']) > 250) {
                        $solicitation['description'] = substr($solicitation['description'], 0, 250) . '...';
                    }
                }
            }
        }

        $this->output([
            'products' => $products ?? [],
            'tutorings' => $tutorings ?? [],
            'solicitations' => $solicitations ?? []
        ]);
    }
}
