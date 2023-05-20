<?php

namespace App\Http\Controllers;


class TestController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->route('get', '/test', 'test')->name('test.test');
    }

    public function test()
    {
        return [
            'random' => rand(0, 99999),
        ];
    }
}
