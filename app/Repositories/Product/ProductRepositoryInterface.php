<?php
  namespace App\Repositories\Product;
  use App\Repositories\RepositoryInterface;
  interface ProductRepositoryInterface extends RepositoryInterface{
     // lấy danh sách sản phẩm
     public function getProducts();
      // lấy chi tiết sản phẩm
  }
