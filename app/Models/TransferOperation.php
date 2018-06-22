<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferOperation extends Model {

    protected $fillable = [
        'user_id',
        'target_user_id',
        'extra_data',
        'operation_id',
        'old_balance',
        'new_balance',
        'is_blocked'
    ];

    public function operate(int $balance): void {
        $extraData = $this->getAttribute('extra_data');
        $sum = $extraData['sum'] ?? 0;
        $this->setAttribute('old_balance', $balance);
        $this->setAttribute('new_balance', $balance);
        $this->setAttribute('is_blocked', false);

        if ($balance > 0 && $sum > 0) {
            $this->setAttribute('new_balance', $balance - $sum);
        }
    }
}
