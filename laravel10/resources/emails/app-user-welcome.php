<?php return [
  'name' => 'Welcome user e-mail',
  'subject' => 'Welcome {{ $user->name }}',

  'content' => '
    Hello {{ $user->name }}. How are you? <br>
    It is a pleasure to welcome you. <br>
    We hope to see you often here.
  ',

  'params' => [
    'user' => \App\Models\AppUser::class,
  ],
];