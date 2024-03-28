<?php declare(strict_types=1);

namespace OpenSearch\Migrations\Console;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use OpenSearch\Migrations\Migrator;

class RollbackCommand extends Command
{
    use ConfirmableTrait;

    /**
     * @var string
     */
    protected $signature = 'opensearch:migrate:rollback 
        {name? : Name of the migration or a full path to the existing migration file.}
        {--force : Force the operation to run when in production.}';
    /**
     * @var string
     */
    protected $description = 'Rollback migrations.';

    protected Migrator $migrator;

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
            $this->migrator->rollbackOne(trim($name));
        } else {
            $this->migrator->rollbackLastBatch();
        }

        return 0;
    }
}
