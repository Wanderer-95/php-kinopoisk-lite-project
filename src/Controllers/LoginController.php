<?php

namespace App\Controllers;

use Kernel\Controller\Controller;

class LoginController extends Controller
{
    public function login(): void
    {
        $this->view('login');
    }

    public function store()
    {
        $email = $this->getRequest()->input('email');
        $password = $this->getRequest()->input('password');

        if (! $this->auth()->attempt($email, $password))
        {
            $this->getSession()->set('error', 'Не удалось войти в аккаунт, пожалуйста проверьте свои данные!');
            $this->getRedirect()->to('/login');
            die();
        }

        $this->getRedirect()->to('/');
    }

    public function logout(): void
    {
        $this->auth()->logout();

        $this->getRedirect()->to('/login');
    }
}
