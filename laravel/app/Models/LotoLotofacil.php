<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotoLotofacil extends LotoMegasena
{
    use HasFactory, \App\Traits\Model;

    protected $singular = 'Sorteio Lotofácil';
    protected $plural = 'Sorteios Lotofácil';
    protected $table = 'loto_lotofacil';

    public function lotoSettings()
    {
        return (object) [
            'url_download' => 'https://servicebus2.caixa.gov.br/portaldeloterias/api/resultados?modalidade=Lotofacil',
            'table_number_start' => 1,
            'table_number_final' => 25,
            'table_number_line' => 5,
        ];
    }

    public function lotoParseRow($values)
    {
        if (sizeof($values) < 15) return;
        $save['contest'] = $values[0];
        $save['date'] = implode('-', array_reverse(explode('/', $values[1]))) . ' 00:00:00';
        $save['name'] = "Sorteio Nº {$save['contest']} em {$values[1]}";

        $numbers = [];
        foreach(range(2, 16) as $i) {
            $numbers[] = str_pad(intval($values[$i]), 2, '0', STR_PAD_LEFT);
        }
        
        $save['numbers'] = implode(' ', $numbers);
        
        return $save;
    }
}
