<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Vite;

/**
 * Class AppServiceProvider
 *
 * Este proveedor de servicios configura la aplicación globalmente.
 * Se usa para definir ajustes como la estructura de respuestas JSON y la optimización de carga de archivos.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra servicios adicionales en la aplicación.
     * Se ejecuta antes de que la aplicación se inicialice completamente.
     */
    public function register(): void
    {
        // Aquí puedes agregar bindings personalizados o servicios adicionales.
    }

    /**
     * Configura la aplicación globalmente cuando Laravel arranca.
     */
    public function boot(): void
    {
        /** 
         * 🔹 Configuración de Vite: 
         * Se establece un prefetch con concurrencia de 3 para mejorar tiempos de carga en frontend.
         */
        $vite = app(Vite::class);
        $vite->prefetch(concurrency: 3);

        /** 
         * 🔹 Configuración de JsonResource: 
         * Se elimina la envoltura `data` en respuestas JSON para hacerlas más limpias.
         */
        JsonResource::withoutWrapping();
    }
}