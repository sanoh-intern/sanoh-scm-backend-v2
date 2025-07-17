<?php

namespace App\Models\DeliveryNote;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class DnDetail extends Model
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'dn_detail_no';

    public $timestamps = false;

    protected $table = 'dn_detail';

    protected $fillable = [
        'dn_detail_no',
        'no_dn',
        'dn_line',
        'order_origin',
        'plan_delivery_date',
        'plan_delivery_time',
        'actual_receipt_date',
        'actual_receipt_time',
        'no_order',
        'order_set',
        'order_line',
        'order_seq',
        'part_no',
        'supplier_item_no',
        'item_desc_a',
        'item_desc_b',
        'item_customer',
        'lot_number',
        'dn_qty',
        'receipt_qty',
        'dn_unit',
        'dn_snp',
        'reference',
        'status_desc',
        'qty_confirm',
    ];

    // Relationship to dnheader
    public function dnHeader(): BelongsTo
    {
        return $this->belongsTo(DnHeader::class, 'no_dn', 'no_dn');
    }

    public function dnOutstanding(): HasMany
    {
        return $this->hasMany(DnDetailOutstanding::class, 'dn_detail_no', 'dn_detail_no');
    }
}
