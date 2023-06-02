<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait ModelTrait
{

  public static function bootModelTrait()
  {
    static::retrieved(function($model) {
      $model->mutatorRetrieve();
    });

    static::saved(function($model) {
        $model->mutatorRetrieve();
    });

    static::saving(function($model) {
      $model->mutatorSave();

      $validate = $model->validate();
      if ($validate->fails()) {
        \App\Utils::throwError(400, 'Validation errors', $validate->errors());
      }

      if (in_array('slug', $model->getFillable()) AND $model->name AND !$model->slug) {
        $model->slug = \Str::slug($model->name);
      }

      $fill = $model->toArray();

      // try upload
      foreach($model->attributes as $name => $value) {
        if ($file = request()->file($name)) {
          if ('app_file' == $model->getTable()) {
            $uploadData = $model->uploadData($file);
            $fill['slug'] = $uploadData['slug'];
            $fill['name'] = $uploadData['name'];
            $fill['size'] = $uploadData['size'];
            $fill['mime'] = $uploadData['mime'];
            $fill['ext'] = $uploadData['ext'];
            $fill['content'] = $uploadData['content'];
            continue;
          }

          $fill[ $name ] = $model->upload($file);
        }
      }

      // error_log(var_export($fill, true));

      // proccess data
      foreach($fill as $name => $value) {
        if (is_array($value)) {
          $value = json_encode($value);
        }

        else if (in_array($value, ['null', 'false', 'undefined', ''])) {
          $value = null;
        }
        
        else if (in_array($value, ['true'])) {
          $value = true;
        }

        $fill[ $name ] = $value;
      }
      
      $model->fill($fill);
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

  public function searchOptionsDefault($query, $params=[])
  {
    $params = $this->searchParamsDefault($params);
    $options = [];

    // Withs
    $options['with'] = [];
    $withTypes = [
      'Illuminate\Database\Eloquent\Relations\HasOne',
      'Illuminate\Database\Eloquent\Relations\HasMany',
      'Illuminate\Database\Eloquent\Relations\BelongsTo',
    ];
    foreach((new \ReflectionClass($this))->getMethods() as $refMethod) {
      if ($returnType = $refMethod->getReturnType()) {
        if (in_array($returnType->getName(), $withTypes)) {
          $options['with'][] = $refMethod->getName();
        }
      }
    }

    return $options;
  }

  public function searchOptions($query, $params=[])
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
        'find' => null,
        'with' => null,
        'limit' => 1,
        'page' => 1,
        'per_page' => 10,
        'order' => 'updated_at:desc',
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
  
  public function searchQueryDefault($params=[])
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

    // ?find=123
    if ($params->find) {
      $query->where(function($q) use($params) {
        $q->where('id', $params->find);
        if (in_array('slug', $this->getFillable())) {
          $q->orWhere('slug', $params->find);
        }
      });
    }

    // ?with=relation1,relation2
    // ?with[]=relation1&with[]=relation2
    if ($params->with) {
      $withs = is_array($params->with) ? $params->with : explode(',', $params->with);
      $query->with($withs);
    }

    // ?limit=10
    if ($params->limit) {
      $query->take($params->limit);
    }

    // ?order=id:desc,name:asc
    // ?order[]=id:desc&order[]=name:asc
    if ($params->order) {
      $orders = is_array($params->order) ? $params->order : explode(',', $params->order);
      foreach($orders as $order) {
        list($field, $type) = explode(':', $order);
        $query->orderBy($field, $type);
      }
    }

    return $query;
  }

  public function searchQuery($query, $params)
  {
    return $query;
  }

  public function scopeSearch($query, $params=[])
  {
    $params = $this->searchParamsDefault($params);
    $query = $this->searchQueryDefault($params);
    return $this->searchQuery($query, $params);
  }

  public function scopeSearchPaginate($query, $params=[])
  {
    $params = $this->searchParamsDefault($params);
    $query = $this->scopeSearch($query, $params);

    $options = array_merge(
      $this->searchOptionsDefault($query, $params),
      $this->searchOptions($query, $params)
    );

    $pagination = (object) $query->paginate($params->per_page)->toArray();

    $params_data = $params->all();
    $params_data['limit'] = intval($params_data['limit']);
    $params_data['page'] = intval($params_data['page']);
    $params_data['per_page'] = intval($params_data['per_page']);

    return [
      'data' => $pagination->data,
      'pagination' => [
        'current_page' => intval($pagination->current_page),
        'from' => intval($pagination->from),
        'last_page' => intval($pagination->last_page),
        'to' => intval($pagination->to),
        'total' => intval($pagination->total),
      ],
      'params' => $params_data,
      'options' => $options,
    ];
  }

  // public function onSchedule($schedule)
  // {
  //   // 
  // }


  public function uploadData($file)
  {
    $ext = strtolower($file->getClientOriginalExtension());
    $mime = strtolower($file->getClientMimeType());

    return [
      'slug' => \Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . ".{$ext}",
      'name' => $file->getClientOriginalName(),
      'size' => $file->getSize(),
      'mime' => $mime,
      'ext' => $ext,
      // 'content' => mb_convert_encoding(file_get_contents($file), 'UTF-8', 'UTF-8'),
      'content' => mb_convert_encoding((string) $file->getContent(), 'UTF-8', 'UTF-8'),
    ];
  }


  // Upload
  public function upload($file, $folder='')
  {
    $data = $this->uploadData($file);
    $data['folder'] = $folder;
    $save = \App\Models\AppFile::store($data);
    return $save->id;
  }


  // // Find
  // public function find($id, $columns = ['*'])
  // {
  //   if (in_array('slug', $this->getFillable())) {
  //     return $this->where(function($query) use($id) {
  //       $query->where('id', $id);
  //       $query->orWhere('slug', $id);
  //     })->first();
  //   }

  //   return parent::find($id, $columns);
  // }


  public function store($data)
  {
    if ($data instanceof Request) {
      $data = $data->all();
    }

    $data = array_merge(['id' => null], $data);
    $model = $this->firstOrNew(['id' => $data['id']], $data);
    $model->fill($data);
    $model->save();
    
    return $model;
  }


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