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
        return self::insertLine($path, $needle, $replace,true);
    }

    public static function insertLineBefore($path, $needle, $replace)
    {
        return self::insertLine($path, $needle, $replace,false);
    }

    public static function insertLine($path, $needle, $replace, $after = true)
    {
        $content = file_get_contents($path);
        if(Str::contains($content, $replace)){
            return "The line already exists";
        }
        $replacedContent = collect(explode("\n",$content))->map(
            function ($line) use($needle, $replace, $after){
                if(Str::contains($line, $needle)){
                    if($after){
                        return "$line\n" . Str::replaceFirst($needle, $replace, $line);
                    }else{
                        return Str::replaceFirst($needle, $replace, $line) . "\n$line";
                    }

                }
                return $line;
            }
        )->join("\n");
        file_put_contents($path, $replacedContent);
        return "Replaced Content!";
    }
}
