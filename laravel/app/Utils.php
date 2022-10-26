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

}