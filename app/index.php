<?php

declare(strict_types=1);

require __DIR__ . '/config/bootstrap.php';

$route = trim($_GET['route'] ?? 'home/index', '/');
$segments = array_values(array_filter(explode('/', $route), static fn ($segment) => $segment !== ''));

$controllerSegment = $segments[0] ?? 'home';
$actionSegments = array_slice($segments, 1);
$actionSegment = $actionSegments ? implode('_', $actionSegments) : 'index';

$controllerClass = ucfirst($controllerSegment) . 'Controller';
$actionMethod = lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $actionSegment))));

try {
    if (!class_exists($controllerClass)) {
        throw new RuntimeException("Controller {$controllerClass} not found");
    }

    $controller = new $controllerClass();

    if (!method_exists($controller, $actionMethod)) {
        throw new RuntimeException("Action {$actionMethod} not found on {$controllerClass}");
    }

    $controller->{$actionMethod}();
} catch (Throwable $exception) {
    http_response_code(404);
    include base_path('views/layout/header.php');
    $message = $exception->getMessage();
    include base_path('views/errors/404.php');
    include base_path('views/layout/footer.php');
}

