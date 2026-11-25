<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (int) $this->price,
            'compare_at_price' => $this->compare_at_price ? (int) $this->compare_at_price : null,
            'stock' => $this->stock,
            'category' => $this->category ? ['id' => $this->category->id, 'name' => $this->category->name] : null,
            'seller' => $this->seller ? ['id' => $this->seller->id, 'store_name' => $this->seller->store_name] : ['store_name' => 'Official Store'],
        ];
    }
}
