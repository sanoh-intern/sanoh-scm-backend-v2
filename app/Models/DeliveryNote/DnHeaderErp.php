<?php

namespace App\Models\DeliveryNote;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DnHeaderErp extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';

    protected $table = 'dn_header';
}
