<?php

namespace App\Controllers;

use Kernel\Controller\Controller;

class MovieController extends Controller
{
    public function index(): void
    {
        $this->view('movie');
    }
}