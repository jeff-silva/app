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

    // ?with=relation1,relation2
    // ?with[]=relation1&with[]=relation2
    if ($params->with) {
      $withs = is_array($params->with) ? $params->with : explode(',', $params->with);
      $query->with($withs);
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
    $query = $this->searchQuery($query, $params);

    $options = array_merge(
      $this->searchOptionsDefault($query, $params),
      $this->searchOptions($query, $params)
    );

    $pagination = (object) $query->paginate($params->per_page)->toArray();

    return [
      'data' => $pagination->data,
      'pagination' => [
        'current_page' => $pagination->current_page,
        'from' => $pagination->from,
        'last_page' => $pagination->last_page,
        'to' => $pagination->to,
        'total' => $pagination->total,
      ],
      'params' => $params->all(),
      'options' => $options,
    ];
  }

  // public function onSchedule($schedule)
  // {
  //   // 
  // }


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