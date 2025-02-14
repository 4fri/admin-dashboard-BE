<?php

namespace App\Http\Controllers\Itam;

use App\Http\Controllers\Controller;
use App\Models\Itam\Asset;
use App\Models\Itam\Contract;
use App\Models\Itam\ContractVendor;
use App\Models\Itam\License;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function countSummaryCards()
    {
        $customers = [
            'customer_count' => Contract::whereNull('deleted_at')
                ->count(),
            'customer_created' => optional(Contract::orderBy('created_at', 'desc')->first())->created_at
        ];

        $assets = [
            'asset_count' => Asset::whereNull('deleted_at')
                ->count(),
            'asset_created' => optional(Asset::orderBy('created_at', 'desc')->first())->created_at
        ];

        $licenses = [
            'license_count' => License::whereNull('deleted_at')
                ->count(),
            'license_created' => optional(License::orderBy('created_at', 'desc')->first())->created_at
        ];

        $contractVendor = [
            'contract_vendor_count' => ContractVendor::whereNull('deleted_at')
                ->count(),
            'contract_vendor_created' => optional(ContractVendor::orderBy('created_at', 'desc')->first())->created_at
        ];

        $data = [
            'customer_count' => $customers['customer_count'],
            'customer_created' => $customers['customer_created'] ? $customers['customer_created']->diffForHumans() : null,
            'asset_count' => $assets['asset_count'],
            'asset_created' => $assets['asset_created'] ? $assets['asset_created']->diffForHumans() : null,
            'license_count' => $licenses['license_count'],
            'license_created' => $licenses['license_created'] ? $licenses['license_created']->diffForHumans() : null,
            'contract_vendor_count' => $contractVendor['contract_vendor_count'],
            'contract_vendor_created' => $contractVendor['contract_vendor_created'] ? $contractVendor['contract_vendor_created']->diffForHumans() : null,
        ];

        return response()->json([
            'success' => true,
            'result' => $data
        ]);
    }

    public function getSummaryCards(Request $request)
    {
        switch ($request->type) {
            case 'customer':
                $data = Contract::whereNull('deleted_at')
                    ->get();
                break;
            case 'asset':
                $data = Asset::whereNull('deleted_at')
                    ->get();
                break;
            case 'license':
                $data = License::whereNull('deleted_at')
                    ->get();
                break;
            case 'contract':
                $data = ContractVendor::whereNull('deleted_at')
                    ->get();
                break;
            default:
                $data = [];
        }

        return response()->json([
            'success' => true,
            'type' => $request->type,
            'result' => $data,
        ]);
    }
}
