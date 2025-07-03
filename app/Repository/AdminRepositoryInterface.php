<?php
namespace App\Repository;

interface AdminRepositoryInterface
{
    public function getAllProducts(int $perPage);
    public function getAllCategories();
    public function editProduct(int $id);
    public function saveEditedProduct(int $id, array $data);
    public function getAllUsers(int $perPage, array $filters);
    public function adminEditUser(int $id);
    public function saveEditedUser(int $id, array $data);
    public function getOrders();
}