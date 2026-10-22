<?php

namespace App\Controllers;

class Products extends BaseController
{
    public function index()
    {
        $db = db_connect();
        $categories = $db->table('products')
            ->select('category')
            ->distinct()
            ->where('is_available', 1)
            ->orderBy('category')
            ->get()->getResultArray();
        $categoryNames = array_column($categories, 'category');
        $selectedCategory = $this->request->getGet('category');

        if (! is_string($selectedCategory) || ! in_array($selectedCategory, $categoryNames, true)) {
            $selectedCategory = '';
        }

        $productsQuery = $db->table('products')
            ->where('is_available', 1)
            ->orderBy('category')
            ->orderBy('name');

        if ($selectedCategory !== '') {
            $productsQuery->where('category', $selectedCategory);
        }

        return view('catalog/index', [
            'products' => $productsQuery->get()->getResultArray(),
            'categories' => $categoryNames,
            'selectedCategory' => $selectedCategory,
        ]);
    }
}
