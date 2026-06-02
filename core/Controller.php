<?php

declare(strict_types=1);

namespace Core;

use Core\Http\Response;
use Core\View\View;

abstract class Controller
{
    protected function view(string $template, array $data = [], int $status = 200): Response
    {
        $html = View::render('layout', [
            'content' => View::render($template, $data),
            'title' => $data['title'] ?? 'Stockit',
        ]);

        return Response::html($html, $status);
    }

    protected function redirect(string $path): Response
    {
        return Response::redirect($path);
    }
}
