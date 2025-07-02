<?php
namespace App\Repository;

interface UserRepositoryInterface
{
    public function getUserProducts(int $userId);
    public function editProduct(int $productId);
    public function getAllCategories();
    public function getUserProductIds(int $userId);
    public function saveEditedProduct(int $productId, array $data);
    public function getUser(int $userId);
    public function updateUser(int $userId, array $data);
    public function getFavorites(int $userId);
    public function getUserActiveProducts(int $userId);
    public function getUserRates(int $userId);
    public function rateUser(array $data);
    public function getOrdersHistory(int $userId);
}