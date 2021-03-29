<?php

namespace App\Twig;

use Symfony\Component\String\Slugger\SluggerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('pluralize', [$this, 'pluralize']),
        ];
    }

    public function pluralize(int $count,string $singular,?string $plural = null) : string
    {
        $plural ??= $singular . "s";

        return $count===1 ? $count . " " . $singular : $count . " " . $plural ;
    }
}
