<?php

namespace App\Http\Controllers\Api\V1\PurchaseOrder;

use App\Http\Controllers\Controller;
use App\Http\Resources\PurchaseOrder\PoDetailResource;
use App\Models\PurchaseOrder\PoDetail;

class PoDetailController extends Controller
{
    // To get PO Detail data based supplier_code
    public function index($po_no)
    {
        // Eager load the 'poHeader' relationship
        $data_podetail = PoDetail::where('po_no', $po_no)
            ->with('poHeader')
            ->orderBy('planned_receipt_date', 'asc')
            ->get();

        // Check if PO Header available
        if (! $data_podetail) {
            return response()->json([
                'status' => 'error',
                'message' => 'PO Number Not Found',
            ], 404);
        }

        // Check if data empty
        if ($data_podetail->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'PO details not found / empty',
                'data' => [],
            ], 200);
        }

        // If data isn't empty
        return response()->json([
            'success' => true,
            'message' => 'Display List PO Detail Successfully',
            'data' => [
                'po_no' => $po_no,
                'planned_receipt_date' => $data_podetail->first()->poHeader->planned_receipt_date,
                'note' => ($data_podetail->first()->poHeader->reference_2 == null) ? $data_podetail->first()->poHeader->reference_1 : $data_podetail->first()->poHeader->reference_2,
                'detail' => PoDetailResource::collection($data_podetail)],
        ], 200);
    }

    // Test function to get all data
    public function indexAll()
    {
        // Eager load the 'poHeader' relationship
        $data_podetail = PoDetail::with('poHeader')->get();

        // Check if data empty
        if ($data_podetail->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'PO details not found',
                'data' => [],
            ], 200);
        }

        // If data isn't empty
        return response()->json([
            'success' => true,
            'message' => 'Display List PO Detail Successfully',
            'data' => PoDetailResource::collection($data_podetail),
        ], 200);
    }
}
