<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Enum\KpiStatus;
use App\Models\Kpi;
use App\Http\Resources\KpiResource;
use App\Http\Requests\StoreKpiRequest;

use App\Http\Requests\RejectKpiRequest;
use App\Services\KpiWorkflow;
use Illuminate\Support\Facades\Gate;

class KpiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'status' => ['nullable', Rule::enum(KpiStatus::class)],
        ]);

        $kpis = Kpi::query()
            ->with(['assignee', 'assigner'])
            ->visibleTo($request->user())
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->latest('assigned_date')
            ->paginate(15);

        return KpiResource::collection($kpis);
    }
    /* public function index(Request $request)
    {
        $request->validate([
            'status' => ['nullable', Rule::enum(KpiStatus::class)],
        ]);
        $user = $request->user();
        $visibleIds = $user->directReports()->pluck('id')->push($user->id);
        $kpis = Kpi::query()
            ->with(['assignee', 'assigner'])
            ->whereIn('assigned_to', $visibleIds)
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->latest('assigned_date')
            ->paginate(15);

        return KpiResource::collection($kpis);
    } */

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKpiRequest $request)
    {
        $data = $request->validated();

        $kpi = Kpi::create([
            'description'   => $data['description'],
            'assigned_to'   => $data['assigned_to'],
            'assigned_by'   => $request->user()->id,                   // from the token, not the client
            'assigned_date' => $data['assigned_date'] ?? now()->toDateString(),
            'status'        => KpiStatus::Assigned,                    // always starts here
        ]);

        return (new KpiResource($kpi->load(['assignee', 'assigner'])))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */

    public function show(Kpi $kpi)
    {
        Gate::authorize('view', $kpi);

        return new KpiResource($kpi->load(['assignee', 'assigner']));
    }
    /* public function show(Request $request, Kpi $kpi)
    {
        $user = $request->user();
        $visibleIds = $user->directReports()->pluck('id')->push($user->id);

        abort_unless($visibleIds->contains($kpi->assigned_to), 403);

        return new KpiResource($kpi->load(['assignee', 'assigner']));
    } */

    public function submit(Kpi $kpi, KpiWorkflow $workflow)
    {
        Gate::authorize('submit', $kpi);

        return new KpiResource(
            $workflow->transition($kpi, KpiStatus::Submitted)->load(['assignee', 'assigner'])
        );
    }

    public function approve(Request $request, Kpi $kpi, KpiWorkflow $workflow)
    {
        Gate::authorize('review', $kpi);

        $data = $request->validate(['notes' => ['nullable', 'string', 'max:1000']]);

        return new KpiResource(
            $workflow->transition($kpi, KpiStatus::Approved, $data['notes'] ?? null)->load(['assignee', 'assigner'])
        );
    }

    public function reject(RejectKpiRequest $request, Kpi $kpi, KpiWorkflow $workflow)
    {
        Gate::authorize('review', $kpi);

        return new KpiResource(
            $workflow->transition($kpi, KpiStatus::Rejected, $request->validated('notes'))->load(['assignee', 'assigner'])
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
