<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Observers\ProductObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

use Tests\TestCase;

class ProductObserverTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Registrar el observer
        Product::observe(ProductObserver::class);

         
    }

    public function test_product_price_is_updated_when_stock_quantity_reaches_15()
    {
        // Crear un producto con un stock_quantity inicial y precio
        $product = Product::factory()->create([
            'name' => "Product Example",
            'description' => "Description Example",
            'base_price' => 100,
            'price' => 200,
            'stock_quantity' => 10,
            'price_override' => false,
        ]);
    
        // Actualizar el stock_quantity a 15
        $product->update([
            'stock_quantity' => 15,
        ]);
    
        // Verificar que el precio ha sido aumentado en un 10% del precio base
        $expectedPrice = 200 + (100 * 0.10);
        $this->assertEquals($expectedPrice, $product->price);
    
        // Verificar que el price_override se ha establecido en true
        $this->assertTrue($product->price_override);
    }

    public function test_product_price_is_not_updated_if_price_override_is_true()
    {
        // Crear un producto con un stock_quantity inicial y precio
        $product = Product::factory()->create([
            'name' => "Product Example",
            'description' => "Description Example",
            'base_price' => 100,
            'price' => 200,
            'stock_quantity' => 10,
            'price_override' => true,
        ]);

        // Actualizar el stock_quantity a 15
        $product->update(['stock_quantity' => 15]);

        Log::info('Product Updated cuando override es true:', [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'base_price' => $product->base_price,
            'price' => $product->price,
            'stock_quantity' => $product->stock_quantity,
            'price_override' => $product->price_override,
        ]);

        // Verificar que el precio no ha cambiado
        $this->assertEquals(200, $product->price);


        // Verificar que el price_override sigue siendo true
        $this->assertTrue($product->price_override);
    }

    public function test_product_price_is_not_updated_if_stock_quantity_does_not_reach_15()
    {
        // Crear un producto con un stock_quantity inicial y precio
        $product = Product::factory()->create([
            'stock_quantity' => 14,
            'base_price' => 100,
            'price' => 100,
            'price_override' => false,
        ]);

        // Actualizar el stock_quantity a 14
        $product->update(['stock_quantity' => 14]);


        // Verificar que el precio no ha cambiado
        $this->assertEquals(100, $product->price);

        // Verificar que el price_override sigue siendo false
        $this->assertFalse($product->price_override);
    }
}
