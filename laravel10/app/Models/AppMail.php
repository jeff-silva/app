<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Illuminate\Mail\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppMailQueue extends Model
{
    use HasFactory, ModelTrait;

    protected $table = 'app_mail';

    protected $fillable = [
        'slug',
        'name',
        'subject',
        'content',
    ];

    static function send($emails, $slug, $params=[])
    {
        $emails = is_array($emails) ? $emails : [ $emails ];
        $data = self::getTemplateData($slug);
        $model = self::where(['slug' => $data['slug']])->first();

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

        $data['subject'] = call_user_func($bladeCompile, $data['subject'], $params);
        $data['content'] = call_user_func($bladeCompile, $data['content'], $params);

        $mail = \Mail::raw($data['content'], function (Message $message) use($emails, $data) {
            $message->subject($data['subject'])->to($emails);
        });

        // \Mail::send([], [], function ($message) use ($emails, $data) {
        //     $message->to($emails)->subject($data['subject'])->setBody($data['content'], 'text/html');
        // });

        // dump($params);
        // dump($bladeCompile);
        dd($data, $mail);
    }
}
