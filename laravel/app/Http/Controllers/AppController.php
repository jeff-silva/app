<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function onInit()
    {
        $this->routeMatch(['get'], '/app/openapi', 'openapi')->name('app.openapi');
    }

    // https://editor.swagger.io/
    // https://editor-next.swagger.io/
    public function openapi()
    {
        $json = [
            'openapi' => '3.0.0',
            'info' => [
                'title' => config('app.name'),
                'description' => 'app description',
                'version' => '1.0.0',
                'termsOfService' => 'http://swagger.io/terms/',
                'contact' => [
                    'email' => 'support@grr.la',
                ],
            ],
            'servers' => [
                [
                    'url' => \URL::to('/'),
                ],
            ],
            'tags' => [
                [
                    'name' => 'app',
                ],
            ],
            'paths' => [],
        ];

        $response_types = [
            [
                'type' => 'application/json',
            ],
            [
                'type' => 'application/xml',
            ],
            [
                'type' => 'application/x-www-form-urlencoded',
            ],
        ];

        foreach(app()->routes->getRoutes() as $route) {
            if (! \Str::startsWith($route->uri, 'api')) continue;
            $method = strtolower(collect($route->methods)->first());

            $model = '\App\Models\\'. preg_replace('/.+\\\(.+?)Controller.+/', '$1', $route->action['controller']);
            $model = class_exists($model)? app($model): false;
            $tags = $model? [ $model->getPlural() ]: ['App'];

            $item = [
                'tags' => $tags,
                'operationId' => $route->action['as'],
            ];

            if (in_array($method, ['post', 'put'])) {
                $item['requestBody'] = [
                    'description' => '$description',
                    'required' => true,
                    'content' => (object) [],
                ];
            }

            $paramsNames = $route->parameterNames();
            if (!empty($paramsNames) OR $model) {
                $item['parameters'] = [];

                foreach($paramsNames as $name) {
                    $item['parameters'][] = [
                        'name' => $name,
                        'in' => 'path',
                        'required' => true,
                        'schema' => [
                            'type' => 'string',
                        ],
                    ];
                }

                if ($model AND \Str::endsWith($route->uri, 'search')) {
                    foreach(array_keys((array) $model->searchParamsDefault()) as $name) {
                        $item['parameters'][] = [
                            'name' => $name,
                            'in' => 'query',
                            'schema' => [
                                'type' => 'string',
                            ],
                        ];
                    }
                }
            }

            $item['responses'] = [
                '200' => [
                    'description' => 'success',
                    'content' => (object) [],
                ],
                '404' => [
                    'description' => 'not found',
                ],
                '500' => [
                    'description' => 'internal error',
                ],
            ];

            // $item['security'] = [
            //     [
            //         'auth' => [
            //             'write',
            //             'read',
            //         ]
            //     ],
            // ];

            // foreach($response_types as $type) {
            //     $schema = [
            //         '$ref' => '#/components/schemas/Pet',
            //     ];
            //     $item['requestBody']['content'][ $type['type'] ] = [
            //         'schema' => $schema,
            //     ];
            //     $item['responses']['200']['content'][ $type['type'] ] = [
            //         'schema' => $schema,
            //     ];
            // }

            // if ($model) {
            //     dd($model->getSingular(), $item);
            // }

            // if (\Str::endsWith($route->uri, '{id}')) {
            //     dd(get_class_methods($route));
            //     // dd($route->parameterNames());
            // }

            $json['paths']["/{$route->uri}"][ $method ] = $item;
        }

        return $json;
    }
}
