<?php


use Illuminate\Http\Request;
use SwooleTW\Http\Websocket\Facades\Websocket;

/*
|--------------------------------------------------------------------------
| Websocket Routes
|--------------------------------------------------------------------------
|
| Here is where you can register websocket events for your application.
|
*/

Websocket::on('connect', function ($websocket, Request $request) {
    $websocket->emit('message', 'welcome');
    dump('connect', $websocket, $request);
});

Websocket::on('disconnect', function ($websocket) {
    // called while socket on disconnect
    dump('disconnect', $websocket);
});

Websocket::on('message', function ($websocket, $data) {
    $websocket->emit('message', $data);
    dump('message', $websocket, $data);
});

Websocket::on('example', function ($websocket, $data) {
    $websocket->emit('message', $data);
    dump('example', $websocket, $data);
});
