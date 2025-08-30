<?php

namespace App\Controllers;

use App\Services\CategoryService;
use Kernel\Controller\Controller;
use Kernel\Storage\Storage;

class MovieController extends Controller
{
    public function create(): void
    {
        $categories = CategoryService::all($this->db());
        $this->view('admin/movies/add', compact('categories'));
    }

    public function store()
    {
        dd($this->getRequest()->postAll(), $this->getRequest()->file('image'));
        $img = $this->getRequest()->file('image');
        if (! is_null($img))
        {
            $filePath = $img->move('movies');
            Storage::url($filePath);
        }

        dd();
        $postData = $this->getRequest()->postAll();
        $validated = $this->getRequest()->validate($postData, [
            'title' => 'required|min:3',

        ]);

        if (! $validated) {
            foreach ($this->getRequest()->errors() as $field => $errors) {
                $this->getSession()->set($field, $errors);
            }
            $this->getRedirect()->to('/admin/movie/add');
        }

        $this->getRedirect()->to('/movie');
    }
}
