<?php return [
  'name' => 'Password recover',
  'subject' => 'Password recovery request',

  'content' => join("\n", [
    'Hello {{ $user->name }}.<br>',
    'A password change has been requested for your account. <br>',
    'If it wasn\'t you, please ignore this email. <br>',
    'Your password recovery code is <strong>{{ $user->remember_token }}</strong>.',
  ]),

  'params' => [
    'user' => \App\Models\AppUser::class,
  ],

  'vars' => [
    '{{ $user->getRememberToken() }}'
  ],
];