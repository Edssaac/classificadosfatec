<?php

namespace App;

use stdClass;

abstract class Controller
{
    protected $view;
    protected $error;

    public function __construct()
    {
        $this->view = new stdClass();
    }

    protected function render(string $view, string $layout = 'layout'): void
    {
        $this->view->page = $view;
        $this->view->image_base_path = $_ENV['IMAGE_BASE_PATH'];

        if (!isset($this->view->search)) {
            $this->view->search = '';
        }

        if (!isset($this->view->filter)) {
            $this->view->filter = false;
        }

        $this->view->login = $this->validateSession();
        $this->view->admin = $this->validateAdminstrator();

        if (file_exists("../App/View/{$layout}.php")) {
            require_once("../App/View/{$layout}.php");
        } else {
            $this->content();
        }
    }

    protected function content(): void
    {
        $class = get_class($this);
        $class = str_replace('App\\Controller\\', '', $class);
        $class = str_replace('Controller', '', $class);
        $class = strtolower($class);

        if (file_exists("../App/View/{$class}/{$this->view->page}.php")) {
            require_once("../App/View/{$class}/{$this->view->page}.php");
        }
    }

    protected function numberFormat(string $number): string
    {
        return str_replace(',', '.', str_replace('.', '', $number));
    }

    protected function sessionManager(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    protected function validateSession(): bool
    {
        $this->sessionManager();

        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            return false;
        }

        return true;
    }

    protected function authenticatePage(bool $index = false, bool $admin = false): void
    {
        if ($admin && !$this->validateAdminstrator()) {
            header('Location: /');
            exit;
        } else if ($this->validateSession() && $index) {
            header('Location: /');
            exit;
        } else if (!$this->validateSession() && !$index) {
            $_SESSION['access_attempt'] = true;
            header('Location: /');
            exit;
        }
    }

    protected function validateAdminstrator(): bool
    {
        $this->sessionManager();

        if (isset($_SESSION['admin']) && $_SESSION['admin']) {
            return true;
        }

        return false;
    }

    protected function output(array $content): void
    {
        header('Content-Type: application/json');
        echo json_encode($content);
        exit;
    }
}
