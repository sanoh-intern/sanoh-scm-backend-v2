<?php

namespace App\Jobs\Syncronization;

use App\Models\PurchaseOrder\PoDetail;
use App\Models\PurchaseOrder\PoDetailErp;
use App\Models\PurchaseOrder\PoHeader;
use App\Models\PurchaseOrder\PoHeaderErp;
use App\Service\Syncronization\SyncDeleteData;
use App\Trait\ErrorLog;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Log;

class SyncPurchaseOrderJob implements ShouldQueue
{
    use ErrorLog, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(SyncDeleteData $syncDeleteData): void
    {
        try {
            // Po Header
            // Initialize year and period
            $actualYear = Carbon::now()->year;
            $actualPeriod = Carbon::now()->month;
            $oneYearsBefore = Carbon::now()->subYears(2)->year; // Change subYears value if you want to sync within range year (Only Running at 00:00 - 00:10)
            $twoMonthBefore = Carbon::now()->subMonths(2)->month; // Change subMonths value if you want to sync within range month (Running every ten minute)

            if (Carbon::now()->format('h:i') >= '00:00' && Carbon::now()->format('h:i') <= '00:10') {
                // Get Purchase Order from range two year ago till now
                $sqlsrvDataPoHeader = PoHeaderErp::whereBetween('po_year', [$oneYearsBefore, $actualYear])
                    ->where('po_type_desc', 'PO LOCAL')
                    ->get();

                Log::channel('sync')->info('Sync PO from range two year ago till now');
            } else {
                // Get Purchase Order from range two month ago till now on this year
                $sqlsrvDataPoHeader = PoHeaderErp::whereBetween('po_period', [$twoMonthBefore, $actualPeriod])
                    ->where('po_year', $actualYear)
                    ->where('po_type_desc', 'PO LOCAL')
                    ->get();
            }

            // copy all data from sql server
            $poNumber = [];
            foreach ($sqlsrvDataPoHeader as $data) {
                $poNumber[] = $data->po_no;

                PoHeader::updateOrCreate(
                    // find the po_no
                    [
                        'po_no' => $data->po_no,
                        'supplier_code' => $data->supplier_code,
                    ],
                    // update data
                    [
                        'supplier_name' => $data->supplier_name,
                        'po_date' => $data->po_date,
                        'po_year' => $data->po_year,
                        'po_period' => $data->po_period,
                        'po_status' => $data->po_status,
                        'reference_1' => $data->reference_1,
                        'reference_2' => $data->reference_2,
                        'attn_name' => $data->attn_name,
                        'po_currency' => $data->po_currency,
                        'po_type_desc' => $data->po_type_desc,
                        'pr_no' => $data->pr_no,
                        'planned_receipt_date' => $data->planned_receipt_date,
                        'payment_term' => $data->payment_term,
                        'po_origin' => $data->po_origin,
                        'po_revision_no' => $data->po_revision_no,
                        'po_revision_date' => $data->po_revision_date,
                    ]
                );
            }

            // Po Detail
            foreach ($poNumber as $data) {
                $sqlsrvDataPoDetail = PoDetailErp::where('po_no', $data)->get();

                // copy all data from sql server
                foreach ($sqlsrvDataPoDetail as $data) {
                    PoDetail::updateOrCreate(
                        [
                            'po_no' => $data->po_no,
                            'po_line' => $data->po_line,
                        ],
                        [
                            'po_sequence' => $data->po_sequence,
                            'item_code' => $data->item_code,
                            'code_item_type' => $data->code_item_type,
                            'bp_part_no' => $data->bp_part_no,
                            'bp_part_name' => $data->bp_part_name,
                            'item_desc_a' => $data->item_desc_a,
                            'item_desc_b' => $data->item_desc_b,
                            'planned_receipt_date' => $data->planned_receipt_date,
                            'po_qty' => $data->po_qty,
                            'receipt_qty' => $data->receipt_qty,
                            'invoice_qty' => $data->invoice_qty,
                            'purchase_unit' => $data->purchase_unit,
                            'price' => $data->price,
                            'amount' => $data->amount,
                        ]
                    );
                }
            }

            $syncDeleteData->deletePo();
        } catch (\Throwable $th) {
            $this->syncError(
                'Sync Purchase Order Failed',
                $th->getMessage(),
                $th->getFile(),
                $th->getLine(),
                $this->job->getJobId()
            );
        }
    }
}
