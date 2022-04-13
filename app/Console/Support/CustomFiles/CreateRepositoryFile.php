<?php

namespace App\Console\Support\CustomFiles;

class CreateRepositoryFile
{
    public function __invoke(string $modelName): string
    {

        return "<?php

namespace App\Repositories;

use App\Models\\{$modelName};

class {$modelName}Repository extends BaseRepository
{
    /**
     * @var Model
     */
    protected \$model = {$modelName}::class;
}";
    }
}
