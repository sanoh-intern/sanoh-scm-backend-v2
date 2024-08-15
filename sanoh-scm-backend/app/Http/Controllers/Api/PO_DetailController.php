<?php

namespace App\Http\Controllers\Api;

use App\Models\PO_Detail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PO_DetailResource;

class PO_DetailController extends Controller
{
    // View list data PODetail
    public function index()
    {
        //get data api to view
        // Using eager loading request data to database for efficiency data
        //in case calling data relation
        $data_podetail = PO_Detail::with('podetail')->get();

        return response()->json([
            'success' => true,
            'message' => 'Success Display List PO Detail',
            'data' => PO_DetailResource::collection($data_podetail)
        ], 200);
    }
}