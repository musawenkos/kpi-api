<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreEvidenceRequest;
use App\Http\Resources\EvidenceResource;
use App\Models\Kpi;
use App\Models\KpiEvidence;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class EvidenceController extends Controller
{
    public function index(Kpi $kpi)
    {
        Gate::authorize('view', $kpi);

        return EvidenceResource::collection(
            $kpi->evidences()->latest('captured_date')->get()
        );
    }

    public function store(StoreEvidenceRequest $request, Kpi $kpi)
    {
        Gate::authorize('uploadEvidence', $kpi);

        $file = $request->file('file');

        // Read these before storing the file.
        $mime = $file->getMimeType();
        $size = $file->getSize();
        $hash = hash_file('sha256', $file->getRealPath());

        $evidence = $kpi->evidences()->create([
            'evidence_name' => $request->validated('evidence_name')
                ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'captured_by'   => $request->user()->id,
            'captured_date' => now(),
            'path'          => $file->store("evidence/kpi-{$kpi->id}"),  // random name, private disk
            'mime_type'     => $mime,
            'size_bytes'    => $size,
            'sha256'        => $hash,
        ]);

        return (new EvidenceResource($evidence))->response()->setStatusCode(201);
    }

    public function download(KpiEvidence $evidence)
    {
        $evidence->loadMissing('kpi');
        Gate::authorize('view', $evidence->kpi);

        $extension = pathinfo($evidence->path, PATHINFO_EXTENSION);

        return Storage::download($evidence->path, "{$evidence->evidence_name}.{$extension}");
    }
}