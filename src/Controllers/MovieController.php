<?php

namespace App\Controllers;

use Kernel\Controller\Controller;

class MovieController extends Controller
{
    public function index(): void
    {
        $this->view('movie');
    }

    public function create(): void
    {
        $this->view('admin/movie/add');
    }

    public function store()
    {
        $postData = $this->getRequest()->postAll();
        $validated = $this->getRequest()->validate($postData, ['name' => 'required|min:3']);

        if (! $validated)
        {
            foreach ($this->getRequest()->errors() as $field => $errors)
            {
                $this->getSession()->set($field, $errors);
            }
            $this->getRedirect()->to('/admin/movie/add');
        }

        $this->getRedirect()->to('/movie');
    }
}
