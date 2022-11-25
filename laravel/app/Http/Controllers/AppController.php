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
            'components' => [
                'securitySchemes' => [
                    'bearer_token' => [
                        'type' => 'http',
                        'description' => 'Login with email and password to get the authentication token',
                        'name' => 'Token based Based',
                        'in' => 'header',
                        'bearerFormat' => 'JWT',
                        'scheme' => 'bearer',
                    ],
                ],
            ],
            'tags' => [
                [
                    'name' => 'App',
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
            if (! isset($route->action['controller'])) continue;
            $method = strtolower(collect($route->methods)->first());

            list($controller_name, $controller_method) = explode('@', $route->action['controller']);
            $model_name = str_replace('Controller', '', \Arr::first(explode('@', \Arr::last(explode('\\', $route->action['controller'])))));

            $model = "\App\Models\\{$model_name}";
            $model = class_exists($model)? app($model): false;
            // if ($model) {
            //     $_parse_annotations = function($doc) {
            //         preg_match_all('/@([a-z]+?)\s+(.*?)\n/i', $doc, $annotations);
            //         if(!isset($annotations[1]) OR count($annotations[1]) == 0) return [];
            //         return array_combine(array_map("trim",$annotations[1]), array_map("trim",$annotations[2]));
            //     };

            //     $comment = (new \ReflectionClass($controller_name))->getMethod($controller_method)->getDocComment();
            //     // $comment = preg_replace('/\/\*\*|\n\s+\*\/|\n\s+\*/', '', $comment);
            //     dd($comment, $_parse_annotations($comment), $model, $route);
            // }

            $item = [
                'tags' => [ $model_name ],
                'operationId' => $route->action['as'],
            ];

            if (in_array($method, ['post', 'put'])) {
                $item['requestBody'] = [
                    'description' => '$description',
                    'required' => true,
                    'content' => (object) [],
                ];
            }

            $item['security'] = [[ 'bearer_token' => [] ]];

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

            $json['paths']["/{$route->uri}"][ $method ] = $item;
        }

        return $json;
    }
}
