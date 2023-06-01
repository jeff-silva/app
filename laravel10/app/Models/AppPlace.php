<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Illuminate\Mail\Message;
use App\Models\AppMailTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppPlace extends Model
{
    use HasFactory, ModelTrait;

    protected $table = 'app_place';

    protected $fillable = [
        'name',
        'route',
        'number',
        'zipcode',
        'reference',
        'district',
        'city',
        'state',
        'state_short',
        'country',
        'country_short',
        'lat',
        'lng',
    ];

    public function mutatorSave()
    {
        $this->name = join(', ', array_filter([
            $this->route,
            $this->number,
            $this->district,
            $this->city,
            $this->state,
        ]));
    }
}
