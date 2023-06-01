<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Illuminate\Mail\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppMail extends Model
{
    use HasFactory, ModelTrait;

    protected $table = 'app_mail';

    protected $fillable = [
        'slug',
        'name',
        'subject',
        'content',
    ];

    protected $appends = [
        'vars',
    ];

    protected function getVarsAttribute()
    {
        $data = (object) self::getTemplateData($this->slug);
        $vars = $data->vars;
        foreach($data->params as $paramName => $paramValue) {
            try {
                if ($model = app($paramValue)) {
                    foreach($model->getFillable() as $modelField) {
                        $vars[] = "{{ \${$paramName}->{$modelField} }}";
                    }
                }
            } catch(\Exception $e) {
                // $vars[] = "{{ \${$paramName} }}";
            }
        }

        return $vars;
    }

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

    static function getTemplateData($slug)
    {
        if (! $exists = realpath(resource_path("/emails/{$slug}.php"))) return false;
        $data = include resource_path("/emails/{$slug}.php");
        return array_merge([
            'slug' => $slug,
            'name' => '',
            'subject' => '',
            'content' => '',
            'vars' => [],
            'test' => false,
        ], $data);
    }

    static function registerTemplates()
    {
        $templates = array_map(function($file) {
            $data = self::getTemplateData(pathinfo($file, PATHINFO_FILENAME));
            $model = self::firstOrNew([ 'slug' => $data['slug'] ], $data);
            $model->name = $data['name'];
            $model->save();
            return $model;
        }, glob(resource_path('/emails/*.php')));
        
        foreach($templates as $template) {
            dump($template->toArray());
        }
    }
}
