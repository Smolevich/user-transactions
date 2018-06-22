<?php

namespace Tests\Unit\App\Models;

use App\Models\TransferOperation;
use PHPUnit\Framework\TestCase;

class TransferOperationTest extends TestCase {

    public function testOperate(){
        $operationData = [
            'user_id' => 1,
            'operation_id' => md5('operation_id'),
            'target_user_id' => 2,
            'is_blocked' => false,
            'extra_data' => [
                'sum' => 30
            ]
        ];
        $currentBalance = 200;
        $diff = $operationData['extra_data']['sum'];
        $model = new TransferOperation($operationData);
        $model->operate($currentBalance);
        $this->assertEquals($currentBalance - $diff, $model->getAttribute('new_balance'));
        $this->assertEquals($operationData['user_id'], $model->getAttribute('user_id'));
        $this->assertEquals($operationData['target_user_id'], $model->getAttribute('target_user_id'));
        $this->assertEquals(false, $model->getAttribute('is_blocked'));
    }
}
