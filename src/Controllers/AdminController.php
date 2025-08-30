<?php

namespace App\Controllers;

use App\Services\CategoryService;
use App\Services\MovieService;
use Kernel\Controller\Controller;

class AdminController extends Controller
{
    public function index(): void
    {
        $categories = CategoryService::all($this->db());
        $movies = MovieService::all($this->db());
        $this->view('admin/index', compact('categories', 'movies'));
    }
}