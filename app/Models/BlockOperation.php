<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockOperation extends Model {

    protected $fillable = [
        'user_id',
        'extra_data',
        'diff',
        'operation_id',
        'new_balance',
        'is_blocked'
    ];

    public function operate(int $currentBalance): void {
        if (!$this->getAttribute('is_blocked')) {
            $diff = $this->getAttribute('extra_data' )['sum'];
            $currentBalance += - $diff;
            $this->setAttribute('new_balance', $currentBalance);
            $this->setAttribute('is_blocked', true);
        } else {
            $this->setAttribute('new_balance', $currentBalance);
        }
    }
}
