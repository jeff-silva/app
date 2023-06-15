<?php
 
namespace App\Console\Commands;
 
use App\Models\AppSettings;
use App\Models\AppMailTemplate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
 
class AppInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install';
 
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install app data';
 
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->call('config:clear');
        $this->call('route:clear');
        $this->call('cache:clear');
        
        // $this->call('migrate');
        $this->migrate();
        $this->call('db:seed');

        $this->registerSettings();
        $this->registerEmailTemplates();
        $this->clearLogFiles();
        $this->makeApiRoutes();

        $this->comment('app:install finish');
    }

    public function query($sql, $assocKey=null)
    {
        // dump($sql);
        $data = \DB::select($sql);

        if ($assocKey) {
            $data = collect($data)->mapWithKeys(fn($item, $index) => [ $item->{$assocKey} => $item ])->toArray();
        }

        return $data;
    }

    public function getDatabaseSchema()
    {
        return include base_path('/database/schema.php');
    }

    public function migrate()
    {
        $database_schema = $this->getDatabaseSchema();
        $tables = $this->query('SHOW TABLE STATUS', 'Name');
        $foreign_keys_sqls = [];
        
        // // Delete tables that is not in settings
        // $tables_delete = array_values(array_diff(array_keys($tables), array_keys($database_schema['tables'])));
        // foreach($tables_delete as $table_delete) {
        //     $this->query("DROP TABLE `{$table_delete}`;");
        // }

        foreach($database_schema['tables'] as $table_name => $table_data) {

            // Table exists
            if (isset($tables[ $table_name ])) {
                $table_fields = $this->query("SHOW COLUMNS FROM {$table_name}", 'Field');
                
                $table_fields_delete = array_values(array_diff(array_keys($table_fields), array_keys($table_data['fields'])));
                foreach($table_fields_delete as $table_field_delete) {
                    $this->query("ALTER TABLE `{$table_name}` DROP COLUMN `{$table_field_delete}`;");
                }
                
                if (isset($table_data['fields']) AND is_array($table_data['fields'])) {
                    $field_name_last = false;
                    foreach($table_data['fields'] as $field_name => $field_type) {
                        $field_name_last = $field_name_last ? "AFTER `{$field_name_last}`" : null;
                        if (isset($table_fields[ $field_name ])) {
                            $this->query("ALTER TABLE `{$table_name}` MODIFY COLUMN `{$field_name}` {$field_type} {$field_name_last};");
                        } else {
                            $this->query("ALTER TABLE `{$table_name}` ADD COLUMN `{$field_name}` {$field_type} {$field_name_last};");
                        }
                        $field_name_last = $field_name;
                    }
                }

                continue;
            }

            // Table does not exists
            if (isset($table_data['fields']) AND is_array($table_data['fields'])) {

                // Create table
                $sql_create = [];
                $sql_create[] = "CREATE TABLE `{$table_name}` (";

                $sql_create_inside = [];
                foreach($table_data['fields'] as $field_name => $field_type) {
                    $sql_create_inside[] = "`{$field_name}` {$field_type}";
                }
    
                $sql_create_inside[] = "\tPRIMARY KEY (`{$table_data['pk']}`) USING BTREE";
                $sql_create[] = implode(",\n\t", $sql_create_inside);
                $sql_create[] = ") COLLATE='{$database_schema['collate']}' ENGINE={$database_schema['engine']};";
                $sql_create = join("\n", $sql_create);
                
                $this->query($sql_create);

                // Add FKs
                if (isset($table_data['fks']) AND is_array($table_data['fks'])) {
                    foreach($table_data['fks'] as $fk_field => $fk_data) {
                        if (!isset($fk_data['to_table']) OR !isset($fk_data['to_field'])) continue;
                        $fk_name = "{$fk_data['to_table']}_{$fk_data['to_field']}_foreign";
                        $fk_data['on_update'] = isset($fk_data['on_update']) ? $fk_data['on_update'] : 'NO ACTION';
                        $fk_data['on_delete'] = isset($fk_data['on_delete']) ? $fk_data['on_delete'] : 'NO ACTION';
                        $foreign_keys_sqls[] = join(' ', [
                            "ALTER TABLE `{$table_name}` ADD CONSTRAINT `{$fk_name}`",
                            "FOREIGN KEY (`{$fk_field}`) REFERENCES `{$fk_data['to_table']}` (`{$fk_data['to_field']}`)",
                            "ON UPDATE {$fk_data['on_update']} ON DELETE {$fk_data['on_delete']}",
                        ]);
                    }
                }
            }
        }

        foreach($foreign_keys_sqls as $sql) {
            $this->query($sql);
        }
    }

    public function registerSettings()
    {
        AppSettings::registerSettings();
    }

    public function registerEmailTemplates()
    {
        return AppMailTemplate::registerTemplates();
    }

    public function clearLogFiles()
    {
        foreach(glob(storage_path('/logs/*.log')) as $path) {
            File::put($path, '');
        }
    }

    public function makeApiRoutes()
    {
        $lines = [
            '<?php',
            '',
            'use Illuminate\Http\Request;',
            'use Illuminate\Support\Facades\Route;',
            '',
            '/*',
            '|--------------------------------------------------------------------------',
            '| API Routes',
            '|--------------------------------------------------------------------------',
            '|',
            '| Here is where you can register API routes for your application. These',
            '| routes are loaded by the RouteServiceProvider and all of them will',
            '| be assigned to the "api" middleware group. Make something great!',
            '|',
            '| This file is auto-generated.',
            '| DON\'T WRITE ROUTES HERE.',
            '|',
            '*/',
            '',
        ];
        
        $except = [
            base_path('/app/Http/Controllers/Controller.php'),
        ];

        foreach(\File::allFiles(base_path('/app/Http/Controllers')) as $file) {
            if (in_array($file->getPathname(), $except)) continue;
            $class = str_replace(base_path('/app'), '\App', $file->getPathname());
            $class = str_replace($file->getFilename(), pathinfo($file->getPathname(), PATHINFO_FILENAME), $class);
            $class= str_replace('/', '\\', $class);
            $lines[] = "new {$class}();";
        }

        $lines[] = '';

        \File::put(base_path('/routes/api.php'), join("\n", $lines));
    }
}