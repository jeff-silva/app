<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotoMegasena extends Model
{
    use HasFactory, \App\Traits\Model;

    protected $singular = 'Sorteio Megasena';
    protected $plural = 'Sorteios Megasena';
    protected $table = 'loto_megasena';
    protected $tableFields = [
        'id' => 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT',
        'name' => 'VARCHAR(255) DEFAULT NULL',
        'contest' => 'INT(10) DEFAULT NULL',
        'date' => 'DATETIME DEFAULT NULL',
        'numbers' => 'VARCHAR(255) DEFAULT NULL',
        'created_at' => 'DATETIME DEFAULT NULL',
        'updated_at' => 'DATETIME DEFAULT NULL',
    ];
    protected $tableFks = [];

    protected $fillable = [
        'id',
        'name',
        'contest',
        'date',
        'numbers',
    ];

    public function lotoSettings()
    {
        return (object) [
            'url_download' => 'https://servicebus2.caixa.gov.br/portaldeloterias/api/resultados?modalidade=Mega-Sena',
            'table_number_start' => 1,
            'table_number_final' => 60,
            'table_number_line' => 10,
        ];
    }

    public function lotoParseRow($values)
    {
        if (sizeof($values) < 15) return;
        $save['contest'] = $values[0];
        $save['date'] = implode('-', array_reverse(explode('/', $values[1]))) . ' 00:00:00';
        $save['name'] = "Sorteio Nº {$save['contest']} em {$values[1]}";
        $save['numbers'] = implode(' ', [
            str_pad(intval($values[2]), 2, '0', STR_PAD_LEFT),
            str_pad(intval($values[3]), 2, '0', STR_PAD_LEFT),
            str_pad(intval($values[4]), 2, '0', STR_PAD_LEFT),
            str_pad(intval($values[5]), 2, '0', STR_PAD_LEFT),
            str_pad(intval($values[6]), 2, '0', STR_PAD_LEFT),
            str_pad(intval($values[7]), 2, '0', STR_PAD_LEFT),
        ]);
        return $save;
    }

    public function lotoImport()
    {
        $settings = $this->lotoSettings();
        $html = json_decode((new \GuzzleHttp\Client)->get($settings->url_download, [
            'verify' => false,
            'allow_redirects' => true,
        ])->getBody()->getContents())->html;

        $dom = new \DOMDocument;
        $dom->loadHTML("<html><body>{$html}</body></html>");
        
        $return = [];
        foreach($dom->getElementsByTagName('tr') as $tr_index => $tr) {
            $values = [];
            foreach($tr->getElementsByTagName('td') as $td_index => $td) {
                $values[] = $td->nodeValue;
            }
            if ($save = $this->lotoParseRow($values, $tr_index)) {
                $model = self::firstOrNew(['contest' => $save['contest']], $save);
                $model->fill($save);
                $model->save();
                $return[] = $model;
            }
        }
        return $return;
    }
}
