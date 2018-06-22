<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WriteOffOperation extends Model {

    protected $fillable = [
        'user_id',
        'sum',
        'extra_data',
        'operation_id',
        'old_balance',
        'new_balance',
        'is_blocked'
    ];

    public function operate(int $balance): void {
        $extraData = $this->getAttribute('extra_data');
        $sum = (int)$extraData['sum'] ?? 0;
        $this->setAttribute('old_balance', $balance);
        $this->setAttribute('new_balance', $balance);
        $isPreBlocked = (boolean)$extraData['pre_blocked'] ?? false;
        $this->setAttribute('is_blocked', $isPreBlocked);

        if ($sum > 0 && $sum < $balance) {
            $this->setAttribute('new_balance', $balance - (int)$sum);
            $extraData['sum'] = - $sum;
            $this->setAttribute('extra_data', $extraData);
        }
    }
}
