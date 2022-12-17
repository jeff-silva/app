<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppDb extends App
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Database commands';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->createModels();
        $this->createControllers();
    }

    public function createModels()
    {
        foreach($this->databaseTables() as $table) {

            if (!$table->Model->Exists) {

                // Create model
                $this->call('make:model', [
                    'name' => $table->Model->Class,
                ]);

                // Add traits and fillable
                $file = \Nette\PhpGenerator\PhpFile::fromCode(file_get_contents(base_path($table->Model->File)), true);
                foreach($file->getClasses() as $fileClass) {
                    $fileClass->addTrait('\App\Traits\Model');
                    $fileClass->addProperty('singular', $table->Name)->setProtected();
                    $fileClass->addProperty('plural', \Str::plural($table->Name))->setProtected();
                    $fileClass->addProperty('table', $table->Name)->setProtected();

                    $fillable = $table->Fields
                        ->filter(function($field) {
                            return !in_array($field->Field, ['created_at', 'updated_at', 'deleted_at']);
                        })
                        ->map(function($field) {
                            return $field->Field;
                        })
                        ->toArray();
                    
                    $fileClass->addProperty('fillable', $fillable);
                    file_put_contents(base_path($table->Model->File), $file->__toString());
                }

                // Create Controller
                file_put_contents(base_path($table->Controller->File), join("\n", [
                    '<?php',
                    '',
                    'namespace App\Http\Controllers;',
                    '',
                    'use Illuminate\Http\Request;',
                    'use Illuminate\Support\Facades\Route;',
                    '',
                    '#[\apiResource()]',
                    "class {$table->Controller->Class} extends Controller",
                    '{',
                    "\tpublic \$model = \\{$table->Model->ClassPath}::class;",
                    '',
                    "\tpublic function onInit()",
                    "\t{",
                    "\t\t\$this->middleware('auth:api', [",
                    "\t\t\t'except' => [],",
                    "\t\t]);",
                    "\t}",
                    '}',
                    '',
                ]));

                // Create Migration
                $sql_create = str_replace("\n", "\n\t\t", $table->SqlCreate);
                $file_name = base_path("database/migrations/". date('Y_m_d_') ."000000_create_{$table->Name}_table.php");
                file_put_contents($file_name, join("\n", [
                    '<?php',
                    '',
                    'use Illuminate\Database\Migrations\Migration;',
                    'use Illuminate\Support\Facades\Schema;',
                    '',
                    'return new class extends Migration',
                    '{',
                    "\tpublic function up()",
                    "\t{",
                    "\t\t\DB::statement('{$sql_create}');",
                    "\t}",
                    '',
                    "\tpublic function down()",
                    "\t{",
                    "\t\tSchema::dropIfExists('{$table->Name}');",
                    "\t}",
                    '};',
                    '',
                ]));
            }
        }
    }
    
    public function createControllers()
    {
        // foreach($this->databaseTables() as $table) {
        //     dump($table->Controller);
        // }
    }
}
