<?php

namespace App\ProductDomain\Repository;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    public function get_all(): Collection
    {
        return Product::all();
    }

    public function get_id_product($id)
    {
        return Product::find($id);
    }
}
