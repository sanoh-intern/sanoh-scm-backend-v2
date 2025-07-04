<?php

namespace App\Http\Resources\Subcontractor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubcontReviewDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'sub_transaction_id' => $this->sub_transaction_id,
            'sub_item_id' => $this->sub_item_id,
            'part_name' => $this->item_name,
            'part_number' => $this->item_code,
            'qty_ok' => $this->qty_ok,
            'qty_ng' => $this->qty_ng,
            'qty_total' => ($this->qty_ok ?? 0) + ($this->qty_ng ?? 0),
            'actual_qty_ok' => $this->actual_qty_ok_receive,
            'actual_qty_ng' => $this->actual_qty_ng_receive,
            'actual_qty_total' => ($this->actual_qty_ok_receive == 0 && $this->actual_qty_ng_receive == 0) ? null : ($this->actual_qty_ok_receive + $this->actual_qty_ng_receive),
        ];
    }
}
