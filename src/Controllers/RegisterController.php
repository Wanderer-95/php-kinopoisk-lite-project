<?php

namespace App\Controllers;

use Kernel\Controller\Controller;

class RegisterController extends Controller
{
    public function register(): void
    {
        $this->view('register');
    }

    public function store(): void
    {
        $rules = [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:4', 'confirmation'],
        ];


        $data = $this->getRequest()->postAll();

        $name = $this->getRequest()->input('name');
        $email = $this->getRequest()->input('email');
        $password = $this->getRequest()->input('password');
        $passwordConfirmation = $this->getRequest()->input('password_confirmation');

        $validated = $this->getRequest()->validate(
            ['name' => $name, 'email' => $email, 'password' => $password, 'password_confirmation' => $passwordConfirmation],
            $rules
        );

        if (! $validated) {
            foreach ($this->getRequest()->errors() as $field => $errors) {
                $this->getSession()->set($field, $errors);
            }
            $this->getRedirect()->to('/register');
            die();
        }

        unset($data['password_confirmation']);
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $id = $this->db()->insert('users', $data);
        $this->getSession()->set('message', "User $id registered!");
        $this->getRedirect()->to('/');
    }
}
