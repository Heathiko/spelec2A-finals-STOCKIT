<?php

declare(strict_types=1);

namespace Core\Http;

final class Response{
    public function __construct(
        private string $body = '',
        private int $status = 200,
        private array $headers = ['Content-Type' => 'text/html; charset=utf-8'],
    ) {
    }

    public static function html(string $body, int $status = 200): self {
        return new self(body: $body, status: $status);
    }

    public static function redirect(string $location, int $status = 302): self{
        return new self(
            body: '',
            status: $status,
            headers: ['Location' => $location],
        );
    }

    public function send(): void{
        http_response_code($this->status);

        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        } 
        echo $this->body;
    }
}
