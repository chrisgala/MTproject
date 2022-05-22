<?php

namespace  App\Services\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceQuery{

    protected array $allowedParams = [
        'invoice_id',
        'date_from',
        'date_to'
    ];

    public function transform(Request $request): array
    {
        $eloquentQuery = [];

        if (array_key_exists('invoice_id',$request->input())){
            $query = $request->query('invoice_id');
            $eloquentQuery[] = ['invoice_id', '=', $query];
            return $eloquentQuery;
        }

        if (array_key_exists('date_from',$request->input())){
            $query = $request->query('date_from');
            $query = preg_replace('/\//','-', $query);
            $query = Carbon::parse($query)->toDateString();
            $eloquentQuery[] = ['invoice_date', '>=', $query];
        }

        if (array_key_exists('date_to',$request->input())){
            $query = $request->query('date_to');
            $query = preg_replace('/\//','-', $query);
            $query = Carbon::parse($query)->toDateString();
            $eloquentQuery[] = ['invoice_date', '<=', $query];
        }

        if (array_key_exists('date_from',$request->input()) && array_key_exists('date_to',$request->input())){
            return ['invoice_date', [$eloquentQuery[0][2], $eloquentQuery[1][2]]];
        }

        return $eloquentQuery;
    }
}
