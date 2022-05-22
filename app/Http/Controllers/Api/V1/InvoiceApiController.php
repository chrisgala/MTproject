<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\InvoiceCollection;
use App\Http\Resources\V1\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvoiceApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return InvoiceCollection
     */
    public function index(): InvoiceCollection
    {
        return new InvoiceCollection(Invoice::paginate());
    }

    /**
     * Display the specified resource.
     *
     * @param Invoice $invoice
     * @return InvoiceResource
     */
    public function show(Invoice $invoice): InvoiceResource
    {
        return new InvoiceResource($invoice);
    }
}
