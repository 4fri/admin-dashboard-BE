<?php

namespace App\Http\Controllers\Itam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getSummaryCards()
    {
        return response()->json([
            'success' => true,
            'message' => 'tahap pengembangan',
        ]);
    }
}
