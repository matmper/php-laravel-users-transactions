<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Support\CustomFiles\CreateRepositoryFile;

class CreateRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repository:create {modelName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository file';

    private $modelName;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->modelName = $this->argument('modelName');

        if ($this->modelName === 'all') {
            foreach (scandir('./app/Models/') as $modelName) {
                $this->createFiles($modelName);
                $this->info("- - - - - - - - - ");
            }
        } else {
            $this->createFiles($this->modelName);
        }

        $this->newLine(2);
        $this->info("Tudo pronto, seus arquivos foram criado com sucesso!");
    }

    private function createFiles($modelName)
    {
        $modelName = str_replace(['/', '\\', '.php', 'php'], '', $modelName);

        $file = (array) $this->createRepository($modelName);

        if (!file_exists('./app/Models/'.$modelName.'.php')) {
            $this->info("A model {$modelName} não existe");
            return null;
        }

        $this->info("Criando o seu arquivo: {$file['fileName']}");
        sleep(1);

        $pathToFile = $file['filePath'] . $file['fileName'];

        if (file_exists($pathToFile)) {
            $this->info("O arquivo {$file["fileName"]} já existe");
            return null;
        } else {
            if ($fileHandle = fopen($pathToFile, "a+")) {
                fwrite($fileHandle, $file["content"]);
                fclose($fileHandle);
            } else {
                $this->info("O arquivo {$file["fileName"]} não foi possível ser criado");
                return null;
            }
        }

        unset($pathToFile);
        unset($fileHandle);

        $this->info("O arquivo {$file["fileName"]} foi criado com sucesso");
        sleep(0.5);

        $this->newLine(1);
    }

    private function createRepository($modelName): array
    {
        return [
            'fileName' => "{$modelName}Repository.php",
            'filePath' => "app/Repositories/",
            'content' => (new CreateRepositoryFile())($modelName),
        ];
    }
}
