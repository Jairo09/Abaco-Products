<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void {}

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product)
    {

        // Verifica si el stock_quantity ha cambiado y si ha llegado a 15 unidades
        if ($product->isDirty('stock_quantity') && $product->stock_quantity == 15) {

            // Aumenta el precio en un 10% conforme al precio base si no se ha aplicado anteriormente
            if (!$product->price_override) {

                $product->price = $product->price + $product->base_price * 0.10;
                $product->price_override = true;

                // Guardar los cambios en la base de datos
                $product->save();  
            }
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
