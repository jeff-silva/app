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

    public function listAll(Request $request)
    {
        return [
            'settings' => AppSettings::listAll(true),
            'options' => [
                'timezones' => timezone_identifiers_list(),
            ],
        ];
    }

    public function saveAll(Request $request)
    {
        $save = [];
        foreach($request->all() as $key => $value) {
            $save[ str_replace('_', '.', $key) ] = $value;
        }

        AppSettings::saveAll($save);
        return $this->listAll($request);
    }
}
