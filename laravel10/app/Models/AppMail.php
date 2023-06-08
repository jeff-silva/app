<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Illuminate\Mail\Message;
use App\Models\AppMailTemplate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppMail extends Model
{
    use HasFactory, ModelTrait;

    protected $table = 'app_mail';

    protected $fillable = [
        'name',
        'email_to',
        'subject',
        'body',
        'send_attempt',
        'send_group',
        'sent',
        'read',
        'email_template',
    ];

    static function send($data=[])
    {
        $data = (object) array_merge([
            'email' => '',
            'subject' => '',
            'body' => '',
        ], $data);

        $header = config('mail.header');
        $footer = config('mail.footer');

        $data->body = $header . $data->body . $footer;

        return !!Mail::html($data->body, function(Message $message) use ($data) {
            $message->to($data->email)->subject($data->subject);
        });
    }

    /*
    AppMail::sendTemplate(['user1@grr.la', 'user2@grr.la', 'user3@grr.la'], 'app-user-welcome', [
        'user' => \App\Models\AppUser::query()->find(1),
    ]);
    */
    static function sendTemplate($emails, $slug, $params=[])
    {
        $emails = is_array($emails) ? $emails : [ $emails ];
        $data = AppMailTemplate::getTemplateData($slug);
        $model = AppMailTemplate::where(['slug' => $data['slug']])->first();

        $errors = [];
        foreach($data['params'] as $dataParamKey => $dataParamValue) {
            if (!isset($params[ $dataParamKey ])) {
                $errors[] = "Param {$dataParamKey} required";
            }
            foreach($params as $paramKey => $paramValue) {
                if ($dataParamKey != $paramKey) continue;
                if ($paramClass = get_class($paramValue) AND $paramClass != $dataParamValue) {
                    $errors[] = "Param {$paramKey}: classes {$paramClass} and {$dataParamValue} are not same";
                }
            }
        }

        if (!empty($errors)) {
            throw new \Exception('ai');
        }

        $bladeCompile = function($html, $data=[]) {
            $html = \Blade::compileString($html);
            ob_start() and extract($data, EXTR_SKIP);
            try { eval('?>'.$html); } catch (\Exception $e) {}
            return ob_get_clean();
        };

        $sents = [];
        $send_group = uniqid();
        foreach($emails as $email) {
            $subject = call_user_func($bladeCompile, $data['subject'], $params);
            $body = call_user_func($bladeCompile, $data['body'], $params);

            $sent = self::send([
                'email' => $email,
                'subject' => $subject,
                'body' => $body,
            ]);

            $sents[] = self::create([
                'name' => "To {$email}",
                'email_to' => $email,
                'subject' => $subject,
                'body' => $body,
                'send_attempt' => 1,
                'send_group' => $send_group,
                'sent' => $sent,
                'read' => 0,
            ]);
        }

        return $sents;
    }
}
