<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeTranslationMigration extends Command {
    protected $signature   = 'make:translation {model}';
    protected $description = 'Create a translation migration for a translatable model';

    public function handle() {
        $modelName  = $this->argument('model');
        $modelClass = "App\\Models\\$modelName";

        if (! class_exists($modelClass)) {
            $this->error("Model [$modelClass] not found.");
            return;
        }

        $model = new $modelClass();
        if (! property_exists($model, 'translatedAttributes')) {
            $this->error("Model [$modelClass] does not have \$translatedAttributes property.");
            return;
        }

        $parentTable = Str::snake(Str::pluralStudly($modelName));
        $foreignKey  = Str::snake($modelName) . '_id';
        $table       = Str::snake(Str::singular($modelName)) . '_translations';

        $columns = '';
        foreach ($model->translatedAttributes as $attr) {
            $columns .= "\$table->string('$attr')->nullable();\n            ";
        }

        $stub = File::get(base_path('stubs/translation.stub'));

        $content = str_replace(
            ['{{table}}', '{{foreign}}', '{{parent}}', '{{columns}}'],
            [$table, $foreignKey, $parentTable, $columns],
            $stub
        );

        $filename = date('Y_m_d_His') . "_create_{$table}_table.php";
        $path     = database_path("migrations/$filename");

        File::put($path, $content);

        $this->info("Migration created: $path");
    }
}
