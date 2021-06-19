<?php


namespace App\Servicos;

use App\Models\Ponto;

class Gerador
{
    public static function codigo($bytes=4)
    {
        $codigo = null;
        do {
            try {
                $codigo = strtoupper(substr(bin2hex(random_bytes($bytes)), 0));
                $existe = Ponto::where('codigo', $codigo)->first();
            } catch (\Exception $e) {
                $codigo = strtoupper(substr(bin2hex(random_bytes($bytes)), 0));
                $existe = Ponto::where('codigo', $codigo)->first();
            }
        } while ($existe !== null);
        return $codigo;
    }
}
