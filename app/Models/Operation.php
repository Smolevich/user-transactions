<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class Operation extends Model {
    abstract public function operate(int $currentBalance): void;
}