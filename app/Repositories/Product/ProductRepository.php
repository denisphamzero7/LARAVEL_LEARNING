<?php

namespace App\Repositories\Product;

 use App\Repositories\BaseRepository;
 use App\Models\Product;
 use App\Repositories\Product\ProductRepositoryInterface;

 class ProductRepository extends BaseRepository implements ProductRepositoryInterface
 {
      // lấy model
      public function getModel()
      {
          return Product::class;
      }

      /**
       * Retrieve all products
       *
       * @return \Illuminate\Database\Eloquent\Collection
       */
      public function getProducts()
      {
          // assuming the base repository has a model property
          return $this->model->all();
      }
 }
