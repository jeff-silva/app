<?php

function dd() {
  foreach(func_get_args() as $data) {
    echo '<pre>'. print_r($data, true) .'</pre>';
  }
}

if ($_SERVER['QUERY_STRING'] === '' || empty($_COOKIE['adminer_permanent'])) {
  $_POST['auth'] = [
      'driver'    => 'server',
      'server'    => getenv('DB_HOST'),
      'username'  => getenv('DB_USERNAME'),
      'password'  => getenv('DB_PASSWORD'),
      'db'        => getenv('DB_DATABASE'),
      'permanent' => 1,
  ];
}


// include __DIR__ . '/editor-4.8.1-mysql-en.php';
include __DIR__ . '/adminer-4.8.1-en.php';