<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\Notification;

class ScanProductStockJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Filtrar los productos con stock menor a 20
        $lowStockProducts = Product::where('stock_quantity', '<', 20)->get();

        // Loguear los productos con bajo stock
        foreach ($lowStockProducts as $product) {
            Log::info("Low stock alert: Product ID <{$product->id}> - {$product->name}");
        }

        // Verificar si hay productos con bajo stock antes de enviar la notificación
        if ($lowStockProducts->isNotEmpty()) {
            // Envía la notificación solo con los productos con bajo stock
            Notification::route('mail', 'example@example.com')
                ->notify(new LowStockNotification($lowStockProducts));
        }
    }
}

