<?php

namespace Raultm\Pruebas\Facades;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Str;


class Pruebas extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pruebas';
    }

    public static function insertLineAfter($path, $needle, $replace)
    {
        $content = file_get_contents($path);
        if(Str::contains($content, $replace)){
            return "The line already exists";
        }
        $replacedContent = collect(explode("\n",$content))->map( function ($line) use($needle, $replace){
            if(Str::contains($line, $needle)){
                return "$line\n" . Str::replaceFirst($needle, $replace, $line);
            }
            return $line;
        })
        ->join("\n")
        ;
        file_put_contents($path, $replacedContent);
        return "Replaced Content!";
    }
}
