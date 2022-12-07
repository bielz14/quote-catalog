<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Functions extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('getLocale', [$this, 'getLocale']),
            new TwigFunction('csrf_token', [$this, 'csrf_token']),
            new TwigFunction('var_dump', [$this, 'var_dump']),
            new TwigFunction('request', [$this, 'request']),
            new TwigFunction('mix', [$this, 'mix']),
        ];
    }

    public static function getLocale()
    {
        return app()->getLocale();
    }

    public static function csrf_token()
    {
        return csrf_token();
    }

    public static function var_dump($param)
    {
        return var_dump($param);
    }

    public static function request($param)
    {
        return request($param);
    }

    public static function mix($param)
    {
        return mix($param, 'build');
    }
}
