<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Illuminate\Mail\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppMailTemplate extends Model
{
    use HasFactory, ModelTrait;

    protected $table = 'app_mail_template';

    protected $fillable = [
        'slug',
        'name',
        'subject',
        'body',
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

    static function getTemplateData($slug)
    {
        if (! $exists = realpath(resource_path("/emails/{$slug}.php"))) return false;
        $data = include resource_path("/emails/{$slug}.php");
        return array_merge([
            'slug' => $slug,
            'name' => '',
            'subject' => '',
            'body' => '',
            'vars' => [],
            'test' => false,
        ], $data);
    }

    static function registerTemplates()
    {
        return array_map(function($file) {
            $data = self::getTemplateData(pathinfo($file, PATHINFO_FILENAME));
            $model = self::firstOrNew([ 'slug' => $data['slug'] ], $data);
            $model->name = $data['name'];
            $model->body = $model->body ? $model->body : $data['body'];
            $model->save();
            return $model;
        }, glob(resource_path('/emails/*.php')));
    }

    public function test($data)
    {
        $data = array_merge([
            'slug' => '',
            'subject' => '',
            'body' => '',
        ], $data);

        if ($template = self::getTemplateData($data['slug']) AND is_callable($template['test'])) {
            $header = config('mail.header');
            $footer = config('mail.footer');

            $data['subject'] = str_replace('-&gt;', '->', $data['subject']);
            $data['body'] = str_replace('-&gt;', '->', $data['body']);
            
            $params = call_user_func($template['test']);
            $data['subject'] = \Blade::render($data['subject'], $params);
            $data['body'] = $header . \Blade::render($data['body'], $params) . $footer;
        }
        
        return $data;
    }
}
