<?php
namespace Nucleo;

/**
 * Router muy simple basado en rutas y métodos HTTP.
 */
class Router
{
    private array $rutas = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $ruta, callable $accion): void
    {
        $this->rutas['GET'][$this->normalizarRuta($ruta)] = $accion;
    }

    public function post(string $ruta, callable $accion): void
    {
        $this->rutas['POST'][$this->normalizarRuta($ruta)] = $accion;
    }

    public function resolver(string $metodo, string $uri): void
    {
        $ruta = $this->normalizarRuta(parse_url($uri, PHP_URL_PATH) ?? '/');

        if (isset($this->rutas[$metodo][$ruta])) {
            ($this->rutas[$metodo][$ruta])();
            return;
        }

        // Soporte básico para parámetros tipo /recurso/{id}
        foreach ($this->rutas[$metodo] as $rutaRegistrada => $accion) {
            $patron = preg_replace('#\{[^/]+\}#', '([^/]+)', $rutaRegistrada);
            if ($patron === null) {
                continue;
            }
            $patron = '#^' . $patron . '$#';
            if (preg_match($patron, $ruta, $coincidencias)) {
                array_shift($coincidencias);
                ($accion)(...$coincidencias);
                return;
            }
        }

        http_response_code(404);
        echo 'Página no encontrada';
    }

    private function normalizarRuta(string $ruta): string
    {
        $ruta = rtrim($ruta, '/');
        return $ruta === '' ? '/' : $ruta;
    }
}
