<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    // public function report(Exception $exception)
    // {
    //     parent::report($exception);
    // }

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            return response()->json([ 12345 ], 400);
        });

        $this->renderable(function (\Exception $e, Request $request) {
            // return response()->json([ 12345 ], 400);
            return [ 12345 ];
        });
    }

    // protected function context(): array
    // {
    //     return array_merge(parent::context(), [
    //         'foo' => 'bar',
    //     ]);
    // }

    // public function render($request, Exception $e)
    // {
    //     return parent::render($request, $e);
    // }
}
