<?php

namespace App;

use App\Models\AcceptOperation;
use App\Models\EnrollmentOperation;
use App\Models\BlockOperation;
use App\Models\NoTypeOperation;
use App\Models\TransferOperation;
use App\Models\WriteOffOperation;
use Illuminate\Database\Eloquent\Model;

class OperationFactory {

    public function createOperation(string $type): Model {

        switch ($type) {
            case 'enrollment':
                $model = new EnrollmentOperation();
                break;
            case 'write-off':
                $model = new WriteOffOperation();
                break;
            case 'transfer':
                $model = new TransferOperation();
                break;
            case 'accept':
                $model = new AcceptOperation();
                break;
            case 'block':
                $model = new BlockOperation();
                break;
            default:
                $model = new NoTypeOperation();
                break;
        }

        return $model;
    }
}