<?php

namespace App\Console\Commands;

use App\Jobs\OperationJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;

class BlockOperation extends Command {

    protected $signature = 'block:push {operation_id}';
    protected $description = 'Push into queue block operations';

    public function handle() {
        $operationId = $this->argument('operation_id');
        $operationData = Cache::get('user:operation:'. $operationId);

        if (!empty($operationData)) {
            if ($operationData['is_blocked'] === true) {
                $this->output->writeln(sprintf("Operation with id %s couldn't be blocked because she is blocked",
                    $operationId));
            } else {
                $operationData['operation'] = 'block';
                $job = new OperationJob($operationData);
                $id = Queue::pushOn('default', $job);
                $this->output->writeln(sprintf('Job with id %s pushed into job', $id));
            }
        } else {
            $this->output->writeln(sprintf('Operation with id %s is not exists', $operationId));
        }
    }
}
