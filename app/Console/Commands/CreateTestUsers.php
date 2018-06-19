<?php

namespace App\Console\Commands;

use App\Jobs\TestJob;
use Illuminate\Console\Command;
use Illuminate\Queue\Jobs\RedisJob;
use Illuminate\Support\Facades\Queue;

class CreateTestUsers extends Command {
    protected $signature = 'create:users';
    protected $description = 'Command description';

    public function handle() {
        $id = Queue::pushOn('test_name', new TestJob([]), ['test' => date('Y-m-d')]);
        $this->output->writeln('id '. $id);
    }
}
