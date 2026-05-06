<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ContractController extends Controller
{
    public function download(Application $application): Response
    {
        // Only completed applications get a contract
        abort_unless($application->status === 'completed', 403, 'Contract only available for completed adoptions.');

        $application->load(['user', 'pet', 'meetGreet.volunteer']);

        $pdf = Pdf::loadView('contracts.adoption', compact('application'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("Homebound-Adoption-Contract-{$application->pet->name}.pdf");
    }
}