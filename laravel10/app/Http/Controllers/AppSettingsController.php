<?php

namespace App\Http\Controllers;

use App\Models\AppSettings;
use Illuminate\Http\Request;

class AppSettingsController extends Controller
{
    public function __construct()
    {
        $this->model = new AppSettings;
        $this->middleware('auth:api', ['except' => []]);
        $this->route('get', 'app_settings', 'listAll');
        $this->route('post', 'app_settings', 'saveAll');
    }

    public function listAll()
    {
        return [
            'settings' => AppSettings::getAll(true),
            'options' => [
                'timezones' => timezone_identifiers_list(),
            ],
        ];
    }

    public function saveAll()
    {
        return AppSettings::getAll();
    }
}
