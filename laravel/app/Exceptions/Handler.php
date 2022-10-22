<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        if (request()->is('api/*')) {
            $this->renderable(function (\Exception $e, $request) {
                $resp = [
                    'status' => 500,
                    'message' => $e->getMessage(),
                    'fields' => [],
                ];

                if (is_array($err = json_decode($e->getMessage(), true))) {
                    $resp = array_merge($resp, $err);
                }

                if ('production' != config('app.env')) {
                    $resp['debug'] = array_map(function($debug) {
                        $debug['file'] = isset($debug['file'])? $debug['file']: 'undefined';
                        $debug['line'] = isset($debug['line'])? $debug['line']: 'undefined';
                        return "{$debug['file']}:{$debug['line']}";
                    }, debug_backtrace());
                }
                
                return response()->json($resp, $resp['status']);
            });
        }
        
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
