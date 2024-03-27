<?php declare(strict_types=1);

namespace OpenSearch\Migrations\Console;

use OpenSearch\Migrations\Migrator;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class MigrateCommand extends Command
{
    use ConfirmableTrait;

    /**
     * @var string
     */
    protected $signature = 'opensearch:migrate 
        {name? : Name of the migration or a full path to the existing migration file.}
        {--force : Force the operation to run when in production.}';
    /**
     * @var string
     */
    protected $description = 'Run the migrations.';

    /**
     * @var Migrator
     */
    protected Migrator $migrator;

    /**
     * @param Migrator $migrator
     */
    public function __construct(Migrator $migrator)
    {
        parent::__construct();

        $this->migrator = $migrator;
    }

    public function handle(): int
    {
        $this->migrator->setOutput($this->output);

        if (!$this->confirmToProceed() || !$this->migrator->isReady()) {
            return 1;
        }

        /** @var ?string $name */
        $name = $this->argument('name');

        if (isset($name)) {
            $this->migrator->migrateOne(trim($name));
        } else {
            $this->migrator->migrateAll();
        }

        return 0;
    }
}
