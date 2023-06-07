<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Illuminate\Mail\Message;
use App\Models\AppMailTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppSettings extends Model
{
    use HasFactory, ModelTrait;

    protected $table = 'app_settings';

    protected $fillable = [
        'name',
        'value',
        'public',
    ];

    static function registerSettings()
    {
        $models = [];
        foreach(config('app_settings') as $type => $values) {
            foreach($values as $value) {
                $model = self::firstOrNew(['name' => $value], [
                    'name' => $value,
                    'value' => config($value),
                ]);
                $model->public = ($type == 'public' ? 1 : 0);
                $model->save();
                $models[] = $model;
            }
        }
        return $models;
    }

    static function listAll($all=false)
    {
        return self::query()
            ->when(!$all, function($q) {
                $q->where('public', 1);
            })
            ->get()
            ->mapWithKeys(function($item) {
                if (is_numeric($item['value'])) {
                    $item['value'] = floatval($item['value']);
                }
                return [ $item['name'] => $item['value'] ];
            })->toArray();
    }

    static function saveAll($data=[])
    {
        foreach(['public', 'private'] as $type) {
            $public = $type == 'public' ? 1 : 0;
            $defaults = config("app_settings.{$type}");
            
            foreach($data as $name => $value) {
                if (!in_array($name, $defaults)) continue;
                $setting = self::firstOrNew([ 'name' => $name ]);
                $setting->fill([
                    'name' => $name,
                    'value' => $value,
                    'public' => $public,
                ]);

                $setting->save();
            }
        }
        return self::listAll(true);
    }
}
