<?php

namespace App\Jobs;

use App\Models\BlockOperation;
use App\Models\NoTypeOperation;
use App\OperationFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;

class OperationJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;
    public $tries = 3;
    protected $operationFactory;

    public function __construct($data) {
        $this->data = $data;
    }

    public function handle() {
        $operationType = $this->data['operation'] ?? 'no operation';
        $this->operationFactory = new OperationFactory();
        $args = [
            $operationType,
            $this->data['user_id'],
            $this->job->getJobId()
        ];
        $this->output('Message received, operation %s, user_id %s, job_id %s', $args);
        $this->process($operationType);
        sleep(2);
        $this->job->delete();
    }

    public function process(string $type): void {
        $model = $this->operationFactory->createOperation($type);
        $userData = Cache::get('user:'.$this->data['user_id']);

        if (!isset($this->data['operation_id'])) {
            $this->data['operation_id'] = $this->job->getJobId();
        }

        if ($model instanceof NoTypeOperation || empty($userData)) {
            $this->output("Wrong type operation or user isn't exists");
        } else {
            $model->fill($this->data);
            $model->operate((int)$userData['balance']);
            $oldBalance = $userData['balance'];
            $operationData = $model->toArray();

            $isBlocked = $operationData['is_blocked'] ?? true;

            if (!$isBlocked) {
                $userData['balance'] = $operationData['new_balance'];
                Cache::forever('user:'.$this->data['user_id'], $userData);
            }

            if ($model instanceof BlockOperation) {
                $userData['balance'] = $operationData['new_balance'];
                unset($operationData['blocked']);
                Cache::forever('user:'.$this->data['user_id'], $userData);
            }

            Cache::forever('user:operation:'. $this->data['operation_id'], $operationData);

            if ($model->getAttribute('target_user_id')) {
                $targetUserId = $model->getAttribute('target_user_id');
                $targetUserData = Cache::get('user:'. $targetUserId);
                $targetUserData['balance'] += $model->getAttribute('extra_data')['sum'];
                Cache::forever('user:'. $targetUserId, $targetUserData);
            }

            $this->output('Operation with id %s successfully finished. Old balance %s, new balance %s',
                [
                    $this->data['operation_id'],
                    $oldBalance,
                    $model->getAttribute('new_balance')
                ]
            );
        }
    }

    protected function updateUserData(array $userData) {
        Cache::forever('user:'.$this->data['user_id'], $userData);
    }

    protected function output(string $pattern, array $args = []) {
        echo vprintf($pattern.PHP_EOL, $args);
    }

    public function failed(\ErrorException $exception = null) {
        if ($exception instanceof \ErrorException) {
            $this->output('Error: %s', $exception->getMessage());
        }
    }
}
