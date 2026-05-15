<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EgateDashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('welcome');
    }

    public function getStudents(): JsonResponse
    {
        // $response = Http::timeout(10)->get('https://randomuser.me/api/', [
        //     'results' => 10,
        // ]);

        // if ($response->failed()) {
        //     return response()->json([
        //         'status' => 'offline',
        //         'message' => 'Failed to fetch students',
        //         'students' => [],
        //     ], 500);
        // }

        // $students = collect($response->json('results', []))
        //     ->map(function (array $student, int $index) {
        //         $name = $student['name'] ?? [];
        //         $picture = $student['picture'] ?? [];

        //         return [
        //             'name' => trim(($name['last'] ?? 'Student').', '.($name['first'] ?? 'Unknown')),
        //             'student_number' => (string) random_int(20260000, 20269999),
        //             'grade_level' => '11',
        //             'department' => 'ABM',
        //             'course' => 'Business',
        //             'rfid_uid' => 'RFID-'
        //                 .str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT)
        //                 .'-'.Str::upper(Str::random(6)),
        //             'image' => $picture['large'] ?? 'https://via.placeholder.com/300',
        //         ];
        //     })
        //     ->values();


        $students = collect(range(1, 2))->map(function ($i) {
            return [
                'name' => fake()->lastName() . ', ' . fake()->firstName(),
                'student_number' => (string) random_int(20260000, 20269999),
                'grade_level' => collect(['11', '12'])->random(),
                'department' => collect(['ABM', 'STEM', 'HUMSS', 'TVL'])->random(),
                'course' => 'Business',
                'rfid_uid' => 'RFID-' . str_pad($i, 4, '0', STR_PAD_LEFT),

                // random face image API
                'image' => 'https://randomuser.me/api/portraits/' .
                    (rand(0, 1) ? 'men' : 'women') . '/' . rand(1, 99) . '.jpg',
            ];
        });


        return response()->json([
            'status' => 'online',
            'message' => 'Student data loaded from API',
            'students' => $students,
        ]);
    }
}
