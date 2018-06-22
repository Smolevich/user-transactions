<?php

namespace App\Console\Commands;

use App\Jobs\OperationJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;

class PushEnrollmentOperation extends Command {

    protected $signature = 'enrollment:push {user_id} {sum} {--pre-blocked : Enrollment with block}';
    protected $description = 'Push into queue enrollemnt operations';

    public function handle() {
        $userId = (int)$this->argument('user_id');
        $sum = (int)$this->argument('sum');
        $operationData = [
            'user_id' => $userId,
            'operation' => 'enrollment',
            'extra_data' => [
                'sum' => $sum,
                'pre_blocked' => $this->option('pre-blocked')
            ]
        ];
        $job = new OperationJob($operationData);
        $id = Queue::pushOn('default', $job);
        $this->output->writeln(sprintf('Job with id %s pushed into job', $id));
    }
}
