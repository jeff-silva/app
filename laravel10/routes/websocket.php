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

Websocket::on('open', function ($websocket, $request) {
    Log::info('open' . $websocket->getSender());
});

Websocket::on('connect', function ($websocket, Request $request) {
    // called while socket on connect
    Log::info('connect' . $websocket->getSender());
});

Websocket::on('disconnect', function ($websocket) {
    // called while socket on disconnect
    Log::info('disconnect' . $websocket->getSender());
});

Websocket::on('example', function ($websocket, $data) {
    $websocket->emit('message', $data);
});
