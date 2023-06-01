<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait ModelTrait
{

  public static function bootModel()
  {
    static::retrieved(function($model) {
      $model->mutatorRetrieve();
    });

    static::saved(function($model) {
        $model->mutatorRetrieve();
    });

    static::saving(function($model) {
      // print_r($model);
      $model->mutatorSave();

      $validate = $model->validate();
      if ($validate->fails()) {
        \App\Utils::throwError(400, 'Validation errors', $validate->errors());
      }

      if (in_array('slug', $model->getFillable()) AND $model->name AND !$model->slug) {
        $model->slug = \Str::slug($model->name);
      }

      foreach($model->attributes as $name => $value) {
        if (is_array($value)) {
          $value = json_encode($value);
        }

        else if (in_array($value, ['null', 'false', 'undefined', ''])) {
          $value = null;
        }
        
        else if (in_array($value, ['true'])) {
          $value = true;
        }

        // else if ($file = request()->file($name)) {
        //   $value = $model->upload($file);
        // }

        $model->attributes[ $name ] = $value;
      }
      
      return $model;
    });
  }


  public function mutatorSave()
  {
      // 
  }


  public function mutatorRetrieve()
  {
      // 
  }


  // Validation
  public function validationRules()
  {
    return [
      'name' => ['required'],
    ];
  }

  public function validationMessages()
  {
    return [];
  }

  public function validate($data=null)
  {
      $data = $data===null? $this->attributes: $data;
      $rules = $this->validationRules();
      $messages = $this->validationMessages();
      return \Validator::make($data, $rules, $messages);
  }


  // Search

  public function getSingular()
  {
    return $this->singular;
  }

  public function getPlural()
  {
    return $this->plural;
  }

  public function searchOptions($pagination, $params=[])
  {
    return [];
  }

  public function searchParamsDefault($merge=[])
  {
    if ($merge instanceof Request) {
      $merge = $merge->all();
    }

    $merge = array_merge(
      [
        'q' => null,
        'with' => null,
        'page' => 1,
        'per_page' => 10,
        'order' => 'id:desc',
      ],
      $this->searchParams(),
      $merge
    );

    return new Request($merge);
  }
  
  public function searchParams()
  {
    return [];
  }
  
  public function searchQuery($query, $params)
  {
    return $query;
  }

  public function scopeSearch($query, $params=[])
  {
    $params = $this->searchParamsDefault($params);

    $query = $this->query();

    // ?q=the+terms
    if ($params->q) {
      $query->where(function($q) use($params) {
        $terms = array_values(array_filter(preg_split('/[^a-zA-Z0-9]/', $params->q)));
        foreach($this->getFillable() as $field) {
          foreach($terms as $term) {
            if (empty($q->getQuery()->wheres)) {
              $q->where($field, 'like', "%{$term}%");
              continue;
            }
  
            $q->orWhere($field, 'like', "%{$term}%");
          }
        }
      });
    }

    // ?with=relation1,relation2
    if ($params->with) {
      $withs = is_array($params->with) ? $params->with : explode(',', $params->with);
      dd($withs);
    }

    $query = $this->searchQuery($query, $params);

    $return = (object) $query->paginate($params->per_page)->toArray();
    return [
      'current_page' => $return->current_page,
      'from' => $return->from,
      'last_page' => $return->last_page,
      'data' => $return->data,
      'params' => $params->all(),
      'options' => $this->searchOptions($return, $params),
    ];
  }

  // Database migration and seed
  public function getTableFields()
  {
    return isset($this->tableFields)? $this->tableFields: [];
  }

  public function getTableFks()
  {
    return isset($this->tableFks)? $this->tableFks: [];
  }

  public function seed()
  {
    // 
  }

  public function onSchedule($schedule)
  {
    // 
  }


  // Upload
  public function upload($file, $folder='')
  {
    try {
      $file_content = mb_convert_encoding(file_get_contents($file), 'UTF-8', 'UTF-8');

      if ('files'==$this->getTable()) {
        return $file_content;
      }

      $save = \App\Models\Files::create([
        'slug' => \Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) .'.'. $file->getClientOriginalExtension(),
        'name' => $file->getClientOriginalName(),
        'mime' => $file->getClientMimeType(),
        'ext' => $file->getClientOriginalExtension(),
        'size' => $file->getSize(),
        'folder' => $folder,
        'file' => $file_content,
      ]);
      return $save->id;
    }
    catch(\Exception $e) {
      \App\Utils::error(500, $e->getMessage());
    }
  }


  // Find
  public function find($id, $columns = ['*'])
  {
    if (in_array('slug', $this->getFillable())) {
      return $this->where(function($query) use($id) {
        $query->where('id', $id);
        $query->orWhere('slug', $id);
      })->first();
    }

    return parent::find($id, $columns);
  }


  public function demo()
  {
    // 
  }
    
    
  //   public static function seed()
  //   {
  //       return [];
  //   }


  //   public function validationRules()
	// {
  //       return [
	// 		'name' => ['required'],
	// 	];
  //   }


  //   public function upload($file)
  //   {
  //       $max_upload_size = intval(config('app_models_files.max_upload_size'));
  //       if ($file->getSize() AND $file->getSize() > $max_upload_size) {
  //           throw new \Exception('O arquivo enviado ultrapassou o tamanho permitido');
  //           return false;
  //       }

  //       $storage_type = config('app_models_files.storage_type'); // database | file
  //       $value = null;

  //       if ($storage_type=='database') {
  //           $type = preg_replace('/\/.+/', '', $file->getClientMimeType());
  //           $ext = $file->getClientOriginalExtension();
  //           $texts = ['svg', 'csv'];

  //           if ($type=='text' OR in_array($ext, $texts)) {
  //               $value = file_get_contents($file);
  //           }
  //           else {
  //               $value = 'data:'. $file->getClientMimeType() .';base64,'. base64_encode(file_get_contents($file));
  //           }
  //       }
  //       else if ($storage_type=='file') {
  //           $filename = \Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) .'.'. $file->getClientOriginalExtension();
  //           \Storage::disk('public')->put($filename, file_get_contents($file));
  //           $value = "/uploads/{$filename}";
  //       }

  //       return $value;
  //   }


  //   public function default()
  //   {
  //       $data = array_map(function() { return ''; }, array_flip($this->getFillable()));
  //       if (isset($data['created_at'])) unset($data['created_at']);
  //       if (isset($data['updated_at'])) unset($data['updated_at']);
  //       if (isset($data['deleted_at'])) unset($data['deleted_at']);
  //       $new = (new static)->fill($data);
  //       $new->mutatorRetrieve();
  //       return $new;
  //   }


  //   public function clone($id, $data=[]) {
  //       $model = self::find($id);
  //       if (!$model) {
  //           throw new \Exception('Model does not exists');
  //       }

  //       $new = $model->replicate()->fill(request()->all());
  //       $new->slug = null;
  //       $new->created_at = $new->updated_at = date('Y-m-d H:i:s');
  //       $new->deleted_at = null;
  //       $new->save();

  //       return $new;
  //   }



  //   public function exportUrls($params=[])
  //   {
  //       $params = $this->searchParamsDefault($params);

  //       foreach($params as $key => $value) {
  //           if (!$value OR in_array($key, ['page', 'per_page'])) {
  //               unset($params[ $key ]);
  //           }
  //       }

  //       $params = http_build_query($params);
  //       $namespace = \Str::of($this->getTable())->studly()->kebab();

  //       $return = [];
  //       foreach(\App\Converters\Converter::formats() as $format) {
  //           $return[] = [
  //               'ext' => $format->ext,
  //               'name' => $format->name,
  //               'url' => "/api/{$namespace}/export?format={$format->ext}&{$params}",
  //           ];
  //       }
        
  //       return collect($return);
  //   }
    


  //   // public function setSlugAttribute($value)
  //   // {
  //   //     $slug = isset($this->attributes['slug'])? $this->attributes['slug']: null;
  //   //     if (in_array('slug', $this->getFillable()) AND !$slug) {
  //   //         $this->attributes['slug'] = \Str::slug($this->name);
  //   //     }
  //   // }


  //   public function setDeletedAtAttribute($value)
	// {
	// 	$value = strtotime($value)? $value: null;
  //       $this->attributes['deleted_at'] = $value;
	// }


  //   public function scopeNotEmpty($query, $fields)
  //   {
  //       $fields = is_array($fields)? $fields: [$fields];
  //       if (empty($fields)) return;

  //       $query->where(function($q) use($fields) {
  //           foreach($fields as $i => $field) {
  //               $q->orWhere(function($q) use($field) {
  //                   $q->whereNotNull($field);
  //                   $q->orWhere($field, '!=', '');
  //               });
  //           }
  //       });
  //   }


  //   public function scopeWithJoin($query, $method, $fields=[], $params=[])
  //   {   
  //       $relation = call_user_func([$this, $method]);

  //       $params = (object) array_merge([
  //           'method' => 'join',
  //           'from' => $query->getModel()->getTable(),
  //           'table' => $relation->getRelated()->getTable(),
  //           'key' => $relation->getForeignKeyName(),
  //           'as' => $relation->getRelated()->getTable(),
  //           'field' => $relation->getLocalKeyName(),
  //       ], $params);

  //       call_user_func([$query, $params->method], "{$params->table} as {$params->as}", "{$params->as}.{$params->key}", '=', "{$params->from}.{$params->field}");

  //       $fields = array_map(function($field) use($params) {
  //           return "{$params->as}.{$field} as {$params->as}_{$field}";
  //       }, $fields);

  //       $query->selectRaw(implode(', ', $fields));
  //   }


  //   public function scopeFindIdOrSlug($query, $slugid)
  //   {
  //       $fillable = $this->fillable;
  //       $query->where(function($q) use($slugid, $fillable) {
  //           $q->where('id', $slugid);

  //           if (in_array('slug', $fillable)) {
  //               $q->orWhere('slug', $slugid);
  //           }
  //       });

  //       return $query->first();
  //   }


  //   public function exportFormats()
  //   {
  //       return \App\Converters\Converter::formats();
  //   }


  //   public function import($format, $content)
  //   {
  //       // 
  //   }


  //   public function delete()
  //   {
  //       if (! $this->id) return false;
        
  //       if (in_array('deleted_at', $this->fillable) AND !$this->deleted_at) {
  //           $this->deleted_at = date('Y-m-d H:i:s');
  //           $this->save();
  //           return true;
  //       }

  //       return parent::delete();
  //   }


  //   public function scopeExport($query, $format)
  //   {
  //       return \App\Converters\Converter::format($format)->export($query);
  //   }


  //   public function scopeDeleteAll($query, $params=null) {
  //       $return = [];
  //       foreach($query->get() as $item) {
  //           $item->delete();
  //           $return[] = $item->id;
  //       }
  //       return $return;
  //   }


  //   public function scopeRestoreAll($query)
  //   {
  //       $return = [];
  //       foreach($query->get() as $item) {
  //           $item->deleted_at = null;
  //           $item->save();
  //           $return[] = $item->id;
  //       }
  //       return $return;
  //   }

  //   public function scopeWhereDeleted($query, $deleted=true) {
  //       if (!in_array('deleted_at', $this->fillable)) return;

  //       if ($deleted) {
  //           $query->where(function($q) {
  //               $q->whereNotNull('deleted_at');
  //               $q->orWhere('deleted_at', '!=', '');
  //           });
  //       }
  //       else {
  //           $query->where(function($q) {
  //               $q->whereNull('deleted_at');
  //               $q->orWhere('deleted_at', '');
  //           });
  //       }

  //       return $query;
  //   }


  //   public function scopeSelectExcept($query, $fields=[]) {
  //       $select = [];
  //       foreach($this->fillable as $col) {
  //           if (in_array($col, $fields)) continue;
  //           $select[] = $col;
  //       }
  //       return $query->select($select);
  //   }


  //   public function searchParamsDefault($params=null) {
  //       $params = is_array($params)? $params: [];
  //       $params = array_merge([
  //           'q' => '',
  //           'page' => 1,
  //           'per_page' => 20,
  //           'order' => 'updated_at:desc',
  //           'deleted' => '',
  //           'limit' => '',
  //           'op' => 'and',
  //       ], $this->searchParams(), $params);

  //       foreach($params as $field => $value) {
  //           if ('me'==$value AND $user=auth()->user()) {
  //               $params[ $field ] = $user->id;
  //           }
  //       }

  //       return $params;
  //   }
    

  //   public function searchParams() {
  //       return [];
  //   }


  //   public function searchAttributes($params=[]) {
  //       return [];
  //   }


  //   public function searchQuery($query, $params) {
  //       return $query;
  //   }


  //   public function scopeSearch($query, $params=null) {
  //       $params = $this->searchParamsDefault($params? $params: request()->all());

  //       if ($query2 = $this->searchQuery($query, (object) $params)) {
  //           $query = $query2;
  //       }

  //       $query_table = $query->getModel()->getTable();
        
  //       foreach($params as $field=>$value) {
  //           $whereCallback = $params['op']=='and'? [$query, 'where']: [$query, 'orWhere'];

  //           // ?orderby=id&order=desc
  //           if ($field=='order') {
  //               $orders = is_array($value)? $value: [$value];
  //               foreach($orders as $order) {
  //                   list($orderby, $order) = explode(':', $order);
  //                   $orderby = "{$query_table}.{$orderby}";
  //                   $query->orderBy($orderby, $order);
  //               }
  //               continue;
  //           }

  //           // ?per_page=10
  //           else if ($field=='per_page') {
  //               $query->limit($value);
  //           }
            
  //           call_user_func($whereCallback, function($query) use($query_table, $field, $value, $params) {
  //               $is_fillable = in_array($field, $this->fillable);
  //               $field = $is_fillable? "{$query_table}.{$field}": $field;
  //               $operator = isset($params["{$field}_op"])? $params["{$field}_op"]: 'eq';

  //               // ?q=term+search
  //               if ($field=='q') {
  //                   if ($value) {
  //                       $terms = preg_split('/[^a-zA-Z0-9]/', $value);
  //                       $whereLikes = [];
  //                       foreach($terms as $q) {
  //                           foreach($this->fillable as $field) {
  //                               $field = "{$query_table}.{$field}";
  //                               $whereLikes[] = [$field, 'like', "%{$q}%"];
  //                           }
  //                       }
  //                       $query = $query->where(function($q) use($whereLikes) {
  //                           foreach($whereLikes as $w) {
  //                               $q->orWhere($w[0], $w[1], $w[2]);
  //                           }
  //                       });
  //                   }
  //               }

  //               // ?deleted=1
  //               else if ($field=='deleted') {
  //                   $query->whereDeleted($value);
  //               }

  //               else if ($is_fillable) {

  //                   // ?status[]=progress&term[]=payment
  //                   // where status in ('progress', 'payment')
  //                   if (is_array($value)) {
  //                       $query->whereIn($field, $value);
  //                   }
        
  //                   // ?price=1000&price_op=lt
  //                   // where price < 1000
  //                   else if ($operator=='lt') {
  //                       $query->where($field, '<', $value);
  //                   }
                    
  //                   // ?price=1000&price_op=lte
  //                   // where price <= 1000
  //                   else if ($operator=='lte') {
  //                       $query->where($field, '<=', $value);
  //                   }
        
  //                   // ?price=1000&price_op=gt
  //                   // where price > 1000
  //                   else if ($operator=='gt') {
  //                       $query->where($field, '>', $value);
  //                   }
        
  //                   // ?price=1000&price_op=gte
  //                   // where price >= 1000
  //                   else if ($operator=='gte') {
  //                       $query->where($field, '>=', $value);
  //                   }
        
  //                   // ?status=finished&status_op=neq
  //                   // where status != 'finished'
  //                   else if ($operator=='neq') {
  //                       $query->where($field, '!=', $value);
  //                   }
        
  //                   // ?title=search&title_op=like
  //                   // where title like '%search%'
  //                   else if ($operator=='like') {
  //                       $query->where($field, 'like', "%{$value}%");
  //                   }
        
  //                   // ?title=search&title_op=nlike
  //                   // where title not like '%search%'
  //                   else if ($operator=='nlike') {
  //                       $query->where($field, 'not like', "%{$value}%");
  //                   }
        
  //                   // ?stars[]=3&stars[]=4&stars_op=between
  //                   // where stars between(3, 4)
  //                   else if ($operator=='between') {
  //                       $query->whereBetween($field, $value);
  //                   }
        
  //                   // ?stars[]=3&stars[]=4&stars_op=nbetween
  //                   // where stars not between(3, 4)
  //                   else if ($operator=='nbetween') {
  //                       $query->whereNotBetween($field, $value);
  //                   }
        
  //                   // ?status=finished
  //                   // where status='finished'
  //                   else {
  //                       if ($value) {
  //                           $query->where($field, $value);
  //                       }
  //                   }
  //               }
  //           });
  //       }

  //       return $query;
  //   }



  public static function scopeToRawSql($query)
  {
    $sqlQuery = \Str::replaceArray(
    '?',
    collect($query->getBindings())
      ->map(function ($i) {
      if (is_object($i)) {
        $i = (string) $i;
      }
      return (is_string($i)) ? "'$i'" : $i;
      })->all(),
    $query->toSql()
    );

    return $sqlQuery;
  }
}