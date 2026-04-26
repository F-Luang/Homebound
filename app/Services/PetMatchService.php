<?php

namespace App\Services;

use App\Models\Pet;
use Illuminate\Support\Collection;

class PetMatchService
{
    /**
     * Run the two-stage matching pipeline:
     * Stage 1 — must-have filters (hard exclusions)
     * Stage 2 — weighted scoring and ranking
     */
    public function match(array $prefs): Collection
    {
        return $this->filter($prefs)
            ->with(['primaryImage'])
            ->get()
            ->map(fn(Pet $pet) => [
                'pet' => $pet,
                'score' => $this->score($pet, $prefs),
            ])
            ->sortByDesc('score')
            ->values()
            ->take(10);
    }

    /**
     * Stage 1: Hard filters — failing any of these excludes the pet entirely.
     * No partial credit here; either you pass or you're out.
     */
    private function filter(array $prefs)
    {
        return Pet::query()
            ->where('status', 'available')
            ->when(
                ($prefs['species'] ?? 'any') !== 'any',
                fn($q) => $q->where('species', $prefs['species'])
            )
            ->when(
                !empty($prefs['hypoallergenic']),
                fn($q) => $q->where('hypoallergenic', true)
            );
    }

    /**
     * Stage 2: Weighted scoring — each factor contributes proportionally.
     * Adjacent matches (e.g. moderate vs high activity) earn partial credit.
     * Returns a 0–100 score.
     */
    private function score(Pet $pet, array $prefs): int
    {
        $weights = $prefs['weights'] ?? [];
        $wActivity = (int) ($weights['activity'] ?? 4);
        $wKids = (int) ($weights['kids'] ?? 3);
        $wSize = (int) ($weights['size'] ?? 2);
        $wSenior = (int) ($weights['senior'] ?? 1);
        $total = $wActivity + $wKids + $wSize + $wSenior ?: 1;

        $earned = 0;

        // Activity level — full credit for exact match, half for adjacent
        $activityIndex = ['low' => 0, 'moderate' => 1, 'high' => 2];
        $petIndex = $activityIndex[$pet->activity_level] ?? 1;
        $prefIndex = $activityIndex[$prefs['activity'] ?? 'moderate'] ?? 1;
        $diff = abs($petIndex - $prefIndex);

        if ($diff === 0)
            $earned += $wActivity;
        elseif ($diff === 1)
            $earned += $wActivity * 0.5;
        // diff of 2 (low vs high) earns nothing

        // Good with kids — binary
        if ($pet->good_with_kids)
            $earned += $wKids;

        // Size — full credit for exact, 30% for mismatch
        if ($pet->size === ($prefs['size'] ?? 'medium')) {
            $earned += $wSize;
        } else {
            $earned += $wSize * 0.3;
        }

        // Senior preference — bonus if pet is senior
        if ($pet->is_senior)
            $earned += $wSenior;

        return (int) round(($earned / $total) * 100);
    }
}