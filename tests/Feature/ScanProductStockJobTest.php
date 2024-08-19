<?php

namespace Tests\Feature;

use App\Jobs\ScanProductStockJob;
use App\Models\Product;
use App\Notifications\LowStockNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\AnonymousNotifiable;
use Tests\TestCase;

class ScanProductStockJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_scan_product_stock_job_logs_info_when_stock_is_low()
    {
        // Mock del log
        Log::shouldReceive('info')
            ->once()
            ->with('Low stock alert: Product ID <1> - Product A');

        // Crear un producto con stock bajo
        $product = Product::factory()->create([
            'id' => 1,
            'name' => 'Product A',
            'stock_quantity' => 10, // stock bajo
        ]);

        // Ejecutar el Job
        $job = new ScanProductStockJob();
        $job->handle();
    }

    public function test_scan_product_stock_job_sends_notification_when_stock_is_low()
    {
        // Mock de la notificación
        Notification::fake();

        // Crear un producto con stock bajo
        $product = Product::factory()->create([
            'name' => 'Product B',
            'stock_quantity' => 5, // stock bajo
        ]);

        // Ejecutar el Job
        $job = new ScanProductStockJob();
        $job->handle();

        // Verificar que la notificación fue enviada al correo especificado
        Notification::assertSentTo(
            new AnonymousNotifiable, // Utiliza AnonymousNotifiable para notificaciones con route()
            LowStockNotification::class,
            function ($notification, $channels, $notifiable) use ($product) {
                // Verificar que la notificación se envió a la dirección de correo correcta
                return $notifiable->routes['mail'] === 'example@example.com' &&
                       $notification->toMail($notifiable)->viewData['products']->contains($product);
            }
        );
    }
    

}

