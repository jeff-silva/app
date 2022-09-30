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
                    'file' => ($e->getFile() .':'. $e->getLine()),
                    'message' => $e->getMessage(),
                    'fields' => new \stdClass,
                    'debug' => [],
                    'data' => request()->all(),
                ];

                if ('production' == config('app.env')) {
                    // unset($resp['file']);
                    // unset($resp['debug']);
                }
                else {
                    $resp['debug'] = array_map(function($debug) {
                        return "{$debug['file']}:{$debug['line']}";
                    }, debug_backtrace());
                }

                $json = json_decode($e->getMessage(), true);
                if (is_array($json)) {
                    $resp['message'] = 'Erros de validação';
                    $resp['fields'] = $json;
                }
                else { $resp['message'] = $e->getMessage(); }
                
                return response()->json($resp, 500);
            });
        }
        
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
