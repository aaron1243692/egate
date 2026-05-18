<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EgateDashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('welcome');
    }

    public function getStudents(Request $request): JsonResponse
    {

        $response = Http::withoutVerifying()
            ->acceptJson()
            ->timeout(15)
            ->get('https://app.olpcc.online/api/admin/id-migrations/students', [
                'school_year_id' => 77
            ]);

        $data = collect($response->json('data', []))
            ->take(2)
            ->values();

        return response()->json($data);

    }

}
