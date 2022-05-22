<?php

namespace Database\Seeders;

use App\Models\Invoice;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Invoice::truncate();
        $csvFile = fopen(base_path("database/data/invoices.csv"), "r");
        $headerRow = true;

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$headerRow) {
                Invoice::create([
                    "invoice_id" => $data['0'],
                    "amount" => $data['1'],
                    "currency" => $data['2'],
                    "invoice_date" => $data['3'],
                    "is_paid" => $data["4"]
                ]);
            }
            $headerRow = false;
        }
        fclose($csvFile);
    }
}
