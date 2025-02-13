<?php

namespace App\Http\Controllers\Itam;

use App\Http\Controllers\Controller;
use App\Models\Itam\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlertController extends Controller
{
    public function index()
    {
        $licensesTable = License::query()
            ->with(['company', 'category', 'manufacturer', 'seats'])
            ->select([
                'licenses.id',
                'licenses.name',
                'licenses.seats',
                'licenses.expiration_date',
                DB::raw('DATEDIFF(licenses.expiration_date, NOW()) AS sisa_hari'),
                'companies.name as Companies__name',
                'categories.name as Categories__name',
                'manufacturers.name as Manufacturers__name',
                DB::raw("
                    licenses.seats - (
                        SELECT COUNT(*)
                        FROM license_seats
                        WHERE license_seats.license_id = licenses.id
                            AND (
                                (license_seats.assigned_to IS NOT NULL AND license_seats.asset_id IS NULL)
                                OR (license_seats.assigned_to IS NULL AND license_seats.asset_id IS NOT NULL)
                                OR (license_seats.assigned_to IS NOT NULL AND license_seats.asset_id IS NOT NULL)
                            )
                    ) AS sisa_license
                ")
            ])
            ->leftJoin('companies', 'licenses.company_id', '=', 'companies.id')
            ->leftJoin('categories', 'licenses.category_id', '=', 'categories.id')
            ->leftJoin('manufacturers', 'licenses.manufacturer_id', '=', 'manufacturers.id')
            ->whereNull('licenses.deleted_at')
            ->whereNotNull('licenses.expiration_date')
            ->where('licenses.maintained', true)
            ->orderBy('licenses.expiration_date', 'asc')
            ->paginate(10);

        $formattedData = $licensesTable->through(function ($license) {
            return [
                'company_name' => $license->Companies__name,
                'license_name' => $license->name,
                'seats' => $license->seats,
                'category_name' => $license->Categories__name,
                'manufactur_name' => $license->Manufacturers__name,
                'expiration_date' => date('d M Y', strtotime($license->expiration_date)),
                'remaining_seats' => $license->sisa_license,
                'days_remaining' => $license->sisa_hari,
            ];
        });

        return response()->json([
            'success' => true,
            'result' => $formattedData,
        ]);
    }
}
