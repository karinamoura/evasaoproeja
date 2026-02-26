<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        // Detectar automaticamente a URL base quando estiver rodando via ngrok ou proxy reverso
        if (app()->runningInConsole() === false && request()) {
            $request = request();

            // Verificar se está atrás de um proxy (ngrok, etc)
            $isProxied = $request->hasHeader('X-Forwarded-Host') ||
                        $request->hasHeader('X-Forwarded-Proto') ||
                        str_contains($request->getHost(), 'ngrok');

            if ($isProxied) {
                // Usar a URL completa da requisição atual
                $scheme = $request->getScheme();
                $host = $request->getHost();
                $port = $request->getPort();

                // Priorizar headers do proxy
                if ($request->hasHeader('X-Forwarded-Proto')) {
                    $scheme = $request->header('X-Forwarded-Proto');
                }

                if ($request->hasHeader('X-Forwarded-Host')) {
                    $host = $request->header('X-Forwarded-Host');
                }

                // Construir a URL base
                $baseUrl = $scheme . '://' . $host;

                // Adicionar porta apenas se não for padrão (80 para HTTP, 443 para HTTPS)
                if ($port && $port != 80 && $port != 443) {
                    $baseUrl .= ':' . $port;
                }

                // Forçar o Laravel a usar esta URL para gerar assets
                URL::forceRootUrl($baseUrl);
            }
        }
    }
}
