<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\InvoiceCollection;
use App\Http\Resources\V1\InvoiceResource;
use App\Services\V1\InvoiceQuery;
use App\Models\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvoiceApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return InvoiceCollection
     */
    public function index(Request $request): InvoiceCollection
    {
        $filter = new InvoiceQuery();
        $queryItems = $filter->transform($request);

        if (count($queryItems) === 0){
            return new InvoiceCollection(Invoice::paginate(30));
        } else if (count($queryItems) === 1){
            return new InvoiceCollection(Invoice::where($queryItems)->paginate(30)->appends($request->query()));
        } else {
            return new InvoiceCollection(Invoice::whereBetween($queryItems[0], $queryItems[1])->paginate(30)->appends($request->query()));

        }
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
