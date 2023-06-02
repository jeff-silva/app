<?php

namespace App;

use phpDocumentor\Reflection\DocBlockFactory;

class Utils
{

  static function success($data)
  {
    return response()->json($data, 200);
  }

  static function error($status, $message=false, $fields=[])
  {
    return response()->json([
      'message' => $message,
      'fields' => $fields,
    ], $status);
  }

  static function throwError($status, $message, $fields=[])
  {
    throw new \Exception(json_encode([
      'status' => $status,
      'message' => $message,
      'fields' => $fields,
    ]), $status);
  }

  static function dd()
  {
    // \Log::shareContext('This is some useful information.');

    // $out = new \Symfony\Component\Console\Output\ConsoleOutput();
    // $out->writeln(print_r(['test' => true], true));

    // \Illuminate\Support\Facades\Log::channel('stderr')->build(['Something happened!']);

    foreach(func_get_args() as $data) {
      print_r($data);
      error_log(json_stringify($data) . PHP_EOL);
    }
  }


  static function getFiles($folder)
  {
    return \File::allFiles(base_path(trim($folder, '/')));
  }


  static function getModels($params = [])
  {
    $params = (object) array_merge([
      'instances' => false,
    ], $params);

    $models = [];
    foreach(self::getFiles('/app/Models') as $file) {
      try {
        $model = 'App\Models\\' . $file->getFilenameWithoutExtension();
        if ($params->instances) {
          $model = app($model);
        }
        $models[] = $model;
      }
      catch(\Exception $e) {
        // 
      }
    }

    return collect($models);
  }

  // https://github.com/phpDocumentor/ReflectionDocBlock/blob/master/examples/02-interpreting-tags.php
  static function reflectorMethodComment($class, $method)
  {
    $comment = (new \ReflectionClass($class))->getMethod($method)->getDocComment();
    if (! $comment) return false;
    return DocBlockFactory::createInstance()->create($comment);
  }


  static function cachedControllers()
  {
    return \Cache::remember('routes/api', 60*60, function () {
      return array_filter(array_map(function($file) {
        if ($file == app_path('/Http/Controllers/Controller.php')) return false;
        $file = str_replace(app_path(), 'App', $file);
        $file = str_replace('.php', '', $file);
        $file = str_replace('/', '\\', $file);
        return $file;
      }, glob(app_path('/Http/Controllers/*.php'))));
    });
  }

}