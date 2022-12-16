<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class App extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Base';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Hello :)');
    }


    public function classData($name, $params=[])
    {
        $params = request()->merge([
            'path' => 'app',
            'prefix' => '',
            'suffix' => '',
        ])->merge($params);

        $return = new \stdClass;
        $return->Name = $name;
        $return->Class = join('', [
            $params->prefix,
            (string) \Str::of($name)->studly(),
            $params->suffix,
        ]);
        $return->ClassPath = join('\\', [
            str_replace('/', '\\', str_replace('app/', 'App\\', $params->path)),
            $return->Class
        ]);
        $return->File = join('/', [ $params->path, "{$return->Class}.php" ]);
        $return->Exists = !!realpath(base_path($return->File));

        return $return;
    }
    

    public $databaseTables = null;
    public function databaseTables()
    {
        return $this->databaseTables = $this->databaseTables? $this->databaseTables:
            collect(\DB::select('SHOW TABLE status'))->map(function($table) {
                $table->Model = $this->classData($table->Name, [
                    'path' => 'app/Models',
                ]);
                $table->Controller = $this->classData($table->Name, [
                    'path' => 'app/Http/Controllers',
                    'suffix' => 'Controller',
                ]);
                return $table;
            });
    }


    public $databaseFks = false;
    public function databaseFks()
    {
        return $this->databaseFks = $this->databaseFks? $this->databaseFks:
            collect(\DB::select("SELECT * from INFORMATION_SCHEMA.TABLE_CONSTRAINTS where CONSTRAINT_TYPE = 'FOREIGN KEY'"));
    }


    public function databaseUML()
    {
        // dd($this->databaseTables());
        // $schema = [];
        // foreach($this->getModels() as $model) {
        //     $tableData = [];
        //     foreach($model->getFillable() as $name) {
        //         $tableData[ $name ] = '';
        //     }
        //     $schema[ $model->getTable() ] = $tableData;
        // }
        // dd($schema);
        // https://docs.kroki.io/kroki/setup/encode-diagram/#php
    }
}
