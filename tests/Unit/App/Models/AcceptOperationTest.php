<?php

namespace Tests\Unit\App\Models;

use App\Models\AcceptOperation;
use Tests\TestCase;

class AcceptOperationTest extends TestCase{

    public function testOperate() {
        $operationData = [
            'user_id' => 1,
            'operation_id' => md5('operation_id'),
            'extra_data' => [
                'sum' => 30
            ]
        ];
        $currentBalance = 200;
        $diff = $operationData['extra_data']['sum'];
        $model = new AcceptOperation($operationData);
        $model->operate($currentBalance);
        $this->assertEquals($currentBalance + $diff, $model->getAttribute('new_balance'));
        $this->assertEquals($operationData['user_id'], $model->getAttribute('user_id'));
        $this->assertEquals(false, $model->getAttribute('is_blocked'));
    }
}
