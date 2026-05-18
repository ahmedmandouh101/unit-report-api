<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUnitReportRequest;
use App\Http\Resources\UnitReportResource;
use App\Models\UnitReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnitReportController extends Controller
{
    public function store(StoreUnitReportRequest $request): JsonResponse
    {
        // Verify the booking belongs to the auth user
        $booking = $request->user()
            ->bookings()
            ->where('id', $request->booking_id)
            ->where('unit_id', $request->unit_id)
            ->first();

        if (! $booking) {
            return response()->json([
                'message' => 'You do not have a valid booking for this unit.',
            ], 403);
        }

        $report = UnitReport::create([
            'user_id'     => $request->user()->id,
            'unit_id'     => $request->unit_id,
            'booking_id'  => $request->booking_id,
            'type'        => $request->type,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Report submitted successfully.',
            'data'    => new UnitReportResource($report),
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $reports = UnitReport::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return response()->json([
            'data' => UnitReportResource::collection($reports),
            'meta' => [
                'current_page' => $reports->currentPage(),
                'last_page'    => $reports->lastPage(),
                'per_page'     => $reports->perPage(),
                'total'        => $reports->total(),
            ],
        ]);
    }
}
