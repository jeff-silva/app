<?php

namespace App;

class Utils
{

  static function error($status, $message, $fields=[])
  {
    throw new \Exception(json_encode([
      'status' => $status,
      'message' => $message,
      'fields' => $fields,
    ]));
  }

  static function dd()
  {
    foreach(func_get_args() as $data) {
      echo print_r($data, true);
    }
  }

}