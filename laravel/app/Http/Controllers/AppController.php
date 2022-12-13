<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// #[\App\Attributes\Route\Resource]
class AppController extends Controller
{
    public function onInit()
    {
        // 
    }

    // https://editor.swagger.io/
    // https://editor-next.swagger.io/
    #[\route('get', 'app/openapi')]
    public function openapi()
    {
        return \App\Helpers\Openapi::get();
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
            $requestBody = [];

            list($controller_name, $controller_method) = explode('@', $route->action['controller']);
            $model_name = str_replace('Controller', '', \Arr::first(explode('@', \Arr::last(explode('\\', $route->action['controller'])))));

            $model = "\App\Models\\{$model_name}";
            $model = class_exists($model)? app($model): false;
            
            if ($doc = \App\Utils::reflectorMethodComment($controller_name, $controller_method)) {
                foreach($doc->getTagsByName('body') as $docParam) {
                    list($field, $value) = array_map('trim', explode('=', (string) $docParam->getDescription()));
                    $requestBody[ $field ] = [
                        'type' => 'string',
                        'format' => 'string',
                        'example' => $value,
                    ];
                }

                foreach($doc->getTagsByName('query') as $docParam) {
                    list($field, $value) = array_map('trim', explode('=', (string) $docParam->getDescription()));
                    $item['parameters'][] = [
                        'name' => $field,
                        'in' => 'query',
                        'schema' => [
                            'type' => 'string',
                            'default' => $value,
                        ],
                    ];
                }

                foreach($doc->getTagsByName('path') as $docParam) {
                    list($field, $value) = array_map('trim', explode('=', (string) $docParam->getDescription()));
                    $item['parameters'][] = [
                        'name' => $field,
                        'in' => 'path',
                        'schema' => [
                            'type' => 'string',
                            'default' => $value,
                        ],
                    ];
                }
            }

            $item = [
                'tags' => [ $model_name ],
                'operationId' => $route->action['as'],
            ];

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

            // Index
            if ($model AND \Str::endsWith($route->action['as'], '.index')) {
                foreach($model->searchParamsDefault()->all() as $name => $value) {
                    $item['parameters'][] = [
                        'name' => $name,
                        'in' => 'query',
                        'schema' => [
                            'type' => 'string',
                            'default' => $value,
                        ],
                    ];
                }
            }
            
            // Store or update
            else if ($model AND \Str::endsWith($route->action['as'], ['.store', '.update'])) {
                $item['requestBody'] = [
                    'description' => '',
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'required' => [],
                                'properties' => [],
                            ],
                        ],
                    ],
                ];
                
                foreach($model->getFillable() as $name) {
                    if ($name == 'id') continue;
                    $requestBody[ $name ] = [
                        'type' => 'string',
                        'format' => 'string',
                        'example' => '',
                    ];
                }
            }

            if (! empty($requestBody)) {
                $item['requestBody'] = [
                    'description' => '',
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'required' => [],
                                'properties' => [],
                            ],
                        ],
                    ],
                ];

                foreach($requestBody as $name => $value) {
                    $item['requestBody']['content']['application/json']['schema']['properties'][ $name ] = $value;
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

            $json['paths']["/{$route->uri}"][ $method ] = $item;
        }

        return $json;
    }
}
