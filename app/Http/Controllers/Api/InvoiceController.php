<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $fromDate = $request['date_from'];
        $toDate = $request['date_to'];

        if (!empty($request)) {
            if ($fromDate && $toDate) {
                /** @noinspection PhpUndefinedMethodInspection */
                return response()->json(['invoices' => Invoice::whereBetween('invoice_date', [$fromDate, $toDate])->get()]);
            } elseif ($fromDate) {
                /** @noinspection PhpUndefinedMethodInspection */
                return response()->json(['invoices' => Invoice::whereDate('invoice_date', '>=', $fromDate)->get()]);
            } elseif ($toDate) {
                /** @noinspection PhpUndefinedMethodInspection */
                return response()->json(['invoices' => Invoice::whereDate('invoice_date', '<=', $toDate)->get()]);
            }
        }

        return response()->json(['invoices' => Invoice::all()]);
    }

    /**
     * Display the specified resource.
     *
     * @param $invoice_id
     * @return JsonResponse
     */
    public function show($invoice_id): JsonResponse
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $invoice = Invoice::where('invoice_id', $invoice_id)->firstOrFail();

        return response()->json([
            'invoice' => $invoice
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Invoice $invoice
     * @return Response
     */
}
