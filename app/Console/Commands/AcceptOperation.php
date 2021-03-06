<?php

namespace App\Console\Commands;

use App\Jobs\OperationJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;

class AcceptOperation extends Command {

    protected $signature = 'accept:push {operation_id}';
    protected $description = 'Push into queue accept operations';

    public function handle() {
        $operationId = $this->argument('operation_id');
        $operationData = Cache::get('user:operation:'. $operationId);

        if (!empty($operationData)) {
            if ($operationData['is_blocked'] === false) {
                $this->output->writeln(sprintf("Operation with id %s couldn't be accepted because she is accepted",
                    $operationId));
            } else {
                $operationData['operation'] = 'accept';
                $job = new OperationJob($operationData);
                $id = Queue::pushOn('default', $job);
                $this->output->writeln(sprintf('Job with id %s pushed into job', $id));
            }
        } else {
            $this->output->writeln(sprintf('Operation with id %s is not exists', $operationId));
        }
    }
}
