<?php

namespace App\Console\Commands;

use App\Jobs\TestJob;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;

class CreateTestUsers extends Command {
    protected $signature = 'create:users';
    protected $description = 'Create users and save into storage';

    public function handle() {
        $data = (new Filesystem())->get(storage_path('app/public/users.json'));
        $users = json_decode($data, true) ?? [];
        $count = 0;

        foreach ($users as $id => $user) {
            $userData = [
                'id' => $id,
                'name' => $user['name'],
                'balance' => $user['balance']
            ];
            Cache::forever('user:'.$id, $userData);
            $count++;
        }

        $this->output->writeln(sprintf('Successfully created %s users', $count));
    }
}
