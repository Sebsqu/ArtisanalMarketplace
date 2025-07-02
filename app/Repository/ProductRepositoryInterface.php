<?php
namespace App\Repository;

interface ProductRepositoryInterface
{
    public function getAllProducts(array $filters = [], int $perPage = 10);
    public function getAllFavorites(int $id);
    public function getAllCategories();
    public function addProduct(array $data);
    public function showProduct(int $id);
    public function incrementViews(int $id);
    public function productRates(int $id);
    public function addToFavorite(int $id, int $userId);
    public function rateProduct(array $data);
    public function findProduct(int $id);
    public function findUser(int $id);
}