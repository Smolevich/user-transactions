<?php

namespace App\Console\Commands;

use App\Jobs\OperationJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;

class TransferOperation extends Command {

    protected $signature = 'transfer:push {user_id} {target_user_id} {sum}';
    protected $description = 'Push into queue accept operations';

    public function handle() {
        $userId = (int)$this->argument('user_id');
        $targetUserId = (int)$this->argument('target_user_id');
        $sum = (int)$this->argument('sum');
        $operationData = [
            'user_id' => $userId,
            'operation' => 'transfer',
            'target_user_id' => $targetUserId,
            'extra_data' => [
                'sum' => $sum
            ]
        ];
        $job = new OperationJob($operationData);
        $id = Queue::pushOn('default', $job);
        $this->output->writeln(sprintf('Job with id %s pushed into job', $id));
    }
}
