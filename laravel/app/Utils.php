<?php

namespace App;

class Utils
{

  static function error($status, $message, $fields=[], $extra=[])
  {
    $json = [
      'status' => ($status? $status: 500),
      'message' => ($message? $message: 'Error'),
      'fields' => $fields,
    ];
    if ($extra) $json['extra'] = $extra;
    throw new \Exception(json_encode($json));
  }

  static function dd()
  {
    // \Log::shareContext('This is some useful information.');

    // $out = new \Symfony\Component\Console\Output\ConsoleOutput();
    // $out->writeln(print_r(['test' => true], true));

    // \Illuminate\Support\Facades\Log::channel('stderr')->build(['Something happened!']);

    foreach(func_get_args() as $data) {
      print_r($data);
      error_log(print_r($data, true) . PHP_EOL);
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
        $model = '\App\Models\\' . $file->getFilenameWithoutExtension();
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

}