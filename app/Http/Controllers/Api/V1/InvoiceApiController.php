<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\InvoiceCollection;
use App\Http\Resources\V1\InvoiceResource;
use App\Services\V1\InvoiceQuery;
use App\Models\Invoice;
use Illuminate\Http\Request;

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
     */
    public function show(Request $request, Invoice $invoice)
    {
       if ($request->wantsJson() || $request->header('Accept')==='*/*') {
           return new InvoiceResource($invoice);
       }

       if ($request->header('Accept')==='text/csv') {
           $invoiceArr = new InvoiceResource($invoice);
           $invoiceArr = $invoiceArr->toArray($request);
           $invoiceArr = array_values($invoiceArr);
           $csvHeaders = ['id', 'invoice_id', 'amount', 'currency', 'invoice_date', 'is_paid'];

           $callback = function () use ($csvHeaders, $invoiceArr) {
               $FH = fopen('php://output', 'w');
               fputcsv($FH, $csvHeaders);
               fputcsv($FH, (array)$invoiceArr);
               fclose($FH);
           };

           $headers = [
               'Content-type' => 'text/csv',
               'Content-Disposition' => 'attachment; filename=galleries.csv'
           ];

           return response()->stream($callback, 200, $headers);
       }

       if (!$request->header('Accept')){
           return response()->json(['Error' => 'You have to set an Accept Header. Supported types: application/json, text/csv'],406);
       }
       return response()->json(['Error' => 'Content type not supported. Supported types: application/json, text/csv'],406);
    }


}
