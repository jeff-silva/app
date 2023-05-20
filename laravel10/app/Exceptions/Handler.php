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
    // protected $dontFlash = [
    //     'current_password',
    //     'password',
    //     'password_confirmation',
    // ];

    public function register(): void
    {
        // $this->reportable(function (Throwable $e) {
        //     // return response()->json([ 12345 ], 400);
        // });

        $this->renderable(function (\Exception $e, $request) {
            $response = [
                'status' => 400,
                'message' => $e->getMessage(),
                'fields' => [],
            ];

            if (is_array($data = json_decode($e->getMessage(), true))) {
                $response = array_merge($response, $data);
            }

            return response()->json($response, $response['status']);
        });
    }
}
