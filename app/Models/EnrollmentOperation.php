<?php

namespace App\Models;


class EnrollmentOperation extends Operation {

    protected $fillable = [
        'user_id',
        'extra_data',
        'operation_id',
        'old_balance',
        'new_balance',
        'is_blocked'
    ];

    public function operate(int $balance): void {

        $extraData = $this->getAttribute('extra_data');
        $isPreBlocked = $extraData['pre_blocked'] ?? false;
        $this->setAttribute('old_balance', $balance);
        $this->setAttribute('is_blocked', $isPreBlocked);

        if ($extraData['sum'] > 0) {
            $this->setAttribute('new_balance', $balance + (int)$extraData['sum']);
        }
    }
}
