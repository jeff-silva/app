<?php

namespace App\Helpers;

class Openapi
{
  public static $tags = [];
  public static $paths = [];

  static function path($methods, $route, $params=[])
  {
    $route = join('/', ['/api', ltrim($route, '/')]);

    $params = (object) array_merge([
        'description' => '',
        'tags' => [],
        'header' => [],
        'path' => [],
        'query' => [],
        'body' => [],
    ], $params);

    if (! isset(self::$paths[ $route ])) {
        self::$paths[ $route ] = [];
    }

    foreach($params->tags as $tag) {
        if (! in_array($tag, self::$tags)) {
            self::$tags[] = $tag;
        }
    }

    foreach($methods as $method) {
        if (! isset(self::$paths[ $route ][ $method ])) {
            $routeData = [
                'tags' => $params->tags,
                'operationId' => false,
                // 'summary' => $params->description,
                // 'description' => $params->description,
                'security' => ['bearer_token' => []],
                'parameters' => [],
            ];

            foreach(['header', 'path', 'query'] as $place) {
                foreach($params->$place as $key => $val) {
                    $routeData['parameters'][] = [
                        'in' => $place,
                        'name' => $key,
                        'required' => false,
                        'schema' => [
                            'type' => gettype($val),
                            'example' => $val,
                        ],
                    ];
                }
            }

            if ($method == 'post') {
                $routeData['requestBody'] = [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [],
                            ],
                        ],
                    ],
                ];

                foreach($params->body as $key => $val) {
                    $routeData['requestBody']['content']['application/json']['schema']['properties'][ $key ] = [
                        'type' => gettype($val),
                        'example' => $val,
                    ];
                }
            }

            $routeData['responses'] = [
                '200' => [
                    'description' => 'Success',
                    // 'content' => true,
                ],
                '404' => [
                    'description' => 'Not found',
                    // 'content' => true,
                ],
                '500' => [
                    'description' => 'Error',
                    // 'content' => true,
                ],
            ];

            self::$paths[ $route ][ $method ] = $routeData;
        }
    }
  }

  public static function get()
  {
    $openapi = [
      'openapi' =>  '3.0.0',
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
      'tags' => self::$tags,
      'paths' => self::$paths,
    ];

    return $openapi;
  }
}