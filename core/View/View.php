<?php

declare(strict_types=1);

namespace Core\View;

final class View{
    private static string $basePath='';
    public static function setBasePath(string $basePath): void{
        self::$basePath=rtrim($basePath,DIRECTORY_SEPARATOR);
    }

    public static function render(string $template, array $data=[]): string{
        $file=self::$basePath.'/'.str_replace('.','/',$template).'.php';
        if(!is_file($file)){
            throw new \RuntimeException("View [{$template}] not found.");
        }
        extract($data,EXTR_SKIP); //takes the array and turns it into vars i can use, EXTR_SKIP means if there are existing variables with the same name, skip them
        ob_start();
        require $file;
        return (string) ob_get_clean();
    }

} 