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

    static function getAll()
    {
        return self::get()->mapWithKeys(function($item) {
            return [ $item['name'] => $item['value'] ];
        })->toArray();
    }
}
