<?php

namespace App\Controllers;

use App\Services\CategoryService;
use Kernel\Controller\Controller;

class CategoryController extends Controller
{
    public function create(): void
    {
        $this->view('admin/categories/add');
    }

    public function store(): void
    {
        $rules = [
            'title' => ['required', 'min:3', 'max:255']
        ];

        $title = $this->getRequest()->input('title');
        $validated = $this->getRequest()->validate(
            ['title' => $title],
            $rules
        );

        if (! $validated) {
            foreach ($this->getRequest()->errors() as $field => $errors) {
                $this->getSession()->set($field, $errors);
            }
            $this->getRedirect()->to('/admin/categories/add');
        }

        CategoryService::store($this->db(), [
            'title' => $title
        ]);

        $this->getSession()->set('success-add-category', 'Категория успешно добавлена!');

        $this->getRedirect()->to('/admin/categories/add');
    }

    public function edit(): void
    {
        $id = $this->getRequest()->input('id');
        $category = CategoryService::first($this->db(), ['id' => $id]);
        if (! $category) {
            $this->getSession()->set('category-error-update', 'Не удалось найти выбранную категорию!');
            $this->getRedirect()->to('/admin');
        }
        $this->view('admin/categories/update', ['category' => $category]);
    }

    public function update(): void
    {
        $id = $this->getRequest()->input('id');
        $category = CategoryService::first($this->db(), ['id' => $id]);
        if (! $category) {
            $this->getSession()->set('category-error-update', 'Не удалось найти выбранную категорию!');
            $this->getRedirect()->to('/admin');
        }
        $rules = [
            'title' => ['required', 'min:3', 'max:255']
        ];

        $title = $this->getRequest()->input('title');
        $validated = $this->getRequest()->validate(
            ['title' => $title],
            $rules
        );

        if (! $validated) {
            foreach ($this->getRequest()->errors() as $field => $errors) {
                $this->getSession()->set($field, $errors);
            }
            $this->getRedirect()->to('/admin/categories/update?id=' . $id);
        }

        $category = CategoryService::update($this->db(), ['title' => $title], [
            'id' => $id
        ]);

        if (! $category) {
            $this->getSession()->set('category-update', 'Не удалось обновить категорию!');
            $this->getRedirect()->to('/admin/categories/update?id=' . $id);
        }

        $this->getSession()->set('category-update', 'Категория успешно обновлена!');
        $this->getRedirect()->to('/admin/categories/update?id=' . $id);
    }

    public function destroy(): void
    {
        $this->db()->delete('categories', ['id' => $this->getRequest()->input('id')]);
        $this->getRedirect()->to('/admin');
    }
}