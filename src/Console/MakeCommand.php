<?php declare(strict_types=1);

namespace OpenSearch\Migrations\Console;

use Carbon\Carbon;
use OpenSearch\Migrations\Filesystem\MigrationStorage;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'opensearch:make:migration 
        {name : Name of the migration or a full path to the new migration file.}';
    /**
     * @var string
     */
    protected $description = 'Create a new migration file.';
    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;
    /**
     * @var MigrationStorage
     */
    private MigrationStorage $migrationStorage;

    public function __construct(Filesystem $filesystem, MigrationStorage $migrationStorage)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
        $this->migrationStorage = $migrationStorage;
    }

    public function handle(): int
    {
        /** @var string $name */
        $name = $this->argument('name');
        $fileName = sprintf('%s_%s', (new Carbon())->format('Y_m_d_His'), Str::snake(trim($name)));
        $className = Str::studly(trim($name));

        $stub = $this->filesystem->get(__DIR__ . '/stubs/migration.blank.stub');
        $content = str_replace('DummyClass', $className, $stub);

        $this->migrationStorage->create($fileName, $content);

        $this->output->writeln('<info>Created migration:</info> ' . $fileName);

        return 0;
    }
}
