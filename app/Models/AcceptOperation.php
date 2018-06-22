<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcceptOperation extends Model {

    protected $fillable = [
        'user_id',
        'extra_data',
        'operation_id',
        'new_balance',
        'is_blocked'
    ];

    public function operate(int $currentBalance): void {
        if (!$this->getAttribute('is_blocked')) {
            $this->setAttribute('new_balance', $currentBalance);
        } else {
            $diff = $this->getAttribute('extra_data' )['sum'];
            $currentBalance += $diff;
            $this->setAttribute('new_balance', $currentBalance);
            $this->setAttribute('is_blocked', false);
        }
    }

}
