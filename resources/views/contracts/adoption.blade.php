<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Adoption Contract — {{ $application->pet->name }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1a1a18;
            line-height: 1.6;
            padding: 36px 40px;
            background: white;
        }

        /* ===== HEADER ===== */
        .header-table {
            width: 100%;
            margin-bottom: 24px;
            border-bottom: 2.5px solid #1D9E75;
            padding-bottom: 16px;
        }

        .org-tagline {
            font-size: 10px;
            color: #888;
            margin-top: 2px;
        }

        .meta-right {
            text-align: right;
            font-size: 10px;
            color: #666;
        }

        .meta-right .contract-label {
            font-size: 13px;
            font-weight: 700;
            color: #1a1a18;
            display: block;
            margin-bottom: 4px;
        }

        /* ===== TITLE ===== */
        .contract-title {
            text-align: center;
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 5px;
        }

        .contract-subtitle {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-bottom: 24px;
        }

        /* ===== SECTION ===== */
        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #1D9E75;
            border-bottom: 0.5px solid #d0ede4;
            padding-bottom: 4px;
            margin-bottom: 10px;
        }

        /* ===== INFO TABLE ===== */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px 0;
            vertical-align: top;
            font-size: 11px;
        }

        .info-label {
            color: #888;
            width: 130px;
        }

        .info-value {
            font-weight: 600;
            color: #1a1a18;
        }

        /* ===== TWO COLUMN ===== */
        .two-col-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .two-col-table td {
            vertical-align: top;
            width: 50%;
            padding-right: 16px;
        }

        .two-col-table td:last-child {
            padding-right: 0;
            padding-left: 16px;
            border-left: 0.5px solid #eee;
        }

        /* ===== TERMS ===== */
        .terms-table {
            width: 100%;
            border-collapse: collapse;
        }

        .terms-table td {
            padding: 5px 0;
            vertical-align: top;
            border-bottom: 0.5px solid #f5f5f5;
            font-size: 10.5px;
            color: #444;
            line-height: 1.5;
        }

        .term-num {
            width: 24px;
            font-weight: 700;
            color: #1D9E75;
            vertical-align: top;
            padding-top: 5px;
        }

        /* ===== STATEMENT ===== */
        .statement-box {
            background: #f9fdf9;
            border-left: 3px solid #1D9E75;
            padding: 10px 14px;
            font-size: 11px;
            color: #444;
            font-style: italic;
            border-radius: 0 4px 4px 0;
        }

        /* ===== BADGE ===== */
        .badge {
            background: #E1F5EE;
            color: #0F6E56;
            font-size: 9px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 10px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ===== SIGNATURE ===== */
        .sig-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 28px;
        }

        .sig-table td {
            width: 50%;
            vertical-align: bottom;
            padding-right: 32px;
        }

        .sig-table td:last-child {
            padding-right: 0;
            padding-left: 16px;
        }

        .sig-line {
            border-bottom: 1px solid #1a1a18;
            height: 44px;
            margin-bottom: 6px;
        }

        .sig-role {
            font-size: 9px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .sig-name {
            font-size: 11px;
            font-weight: 600;
            margin-top: 3px;
        }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 32px;
            padding-top: 12px;
            border-top: 0.5px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #aaa;
        }

        /* ===== WATERMARK ===== */
        .watermark {
            position: fixed;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-35deg);
            font-size: 90px;
            font-weight: 900;
            color: rgba(29, 158, 117, 0.04);
            white-space: nowrap;
            z-index: -1;
        }

        /* ===== PAGE BREAK ===== */
        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    <div class="watermark">HOMEBOUND</div>

    {{-- ===== HEADER ===== --}}
    <table class="header-table">
        <tr>
            <td>
                <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('assets/Homebound.png'))) }}"
                     alt="Homebound" style="height: 48px; display: block;">
                <div class="org-tagline">Pet Adoption Management System</div>
            </td>
            <td class="meta-right">
                <span class="contract-label">Adoption Contract</span>
                Contract No: HB-{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}<br>
                Date Issued: {{ now()->format('F j, Y') }}<br>
                Status: <span class="badge">Completed</span>
            </td>
        </tr>
    </table>

    {{-- ===== TITLE ===== --}}
    <div class="contract-title">Pet Adoption Agreement</div>
    <div class="contract-subtitle">
        This agreement was entered into on {{ $application->submitted_at->format('F j, Y') }}
        between Homebound Pet Adoption Shelter and the adopter named below.
    </div>

    {{-- ===== PARTIES ===== --}}
    <table class="two-col-table">
        <tr>
            <td>
                <div class="section-title">Adopter information</div>
                <table class="info-table">
                    <tr>
                        <td class="info-label">Full name</td>
                        <td class="info-value">{{ $application->user->name }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Email address</td>
                        <td class="info-value">{{ $application->user->email }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Application date</td>
                        <td class="info-value">{{ $application->submitted_at->format('M j, Y') }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Completion date</td>
                        <td class="info-value">{{ $application->updated_at->format('M j, Y') }}</td>
                    </tr>
                </table>
            </td>
            <td>
                <div class="section-title">Pet information</div>
                <table class="info-table">
                    <tr>
                        <td class="info-label">Name</td>
                        <td class="info-value">{{ $application->pet->name }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Species</td>
                        <td class="info-value">{{ ucfirst($application->pet->species) }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Breed</td>
                        <td class="info-value">{{ $application->pet->breed ?? 'Mixed / Unknown' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Gender</td>
                        <td class="info-value">{{ ucfirst($application->pet->gender ?? 'Unknown') }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Age</td>
                        <td class="info-value">{{ floor($application->pet->age_months / 12) }} yr
                            {{ $application->pet->age_months % 12 }} mo
                        </td>
                    </tr>
                    <tr>
                        <td class="info-label">Size</td>
                        <td class="info-value">{{ ucfirst($application->pet->size) }}</td>
                    </tr>
                    @if($application->pet->weight_kg)
                        <tr>
                            <td class="info-label">Weight</td>
                            <td class="info-value">{{ $application->pet->weight_kg }} kg</td>
                        </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    {{-- ===== ADOPTER PROFILE ===== --}}
    @if($application->home_type || $application->experience)
        <div class="section">
            <div class="section-title">Adopter's profile</div>
            <table class="info-table">
                @if($application->home_type)
                    <tr>
                        <td class="info-label">Home type</td>
                        <td class="info-value">{{ ucfirst($application->home_type) }}{{ $application->has_yard ? ', with yard' : '' }}</td>
                    </tr>
                @endif
                @if($application->has_children)
                    <tr>
                        <td class="info-label">Children at home</td>
                        <td class="info-value">Yes{{ $application->children_ages ? ' — ages ' . $application->children_ages : '' }}</td>
                    </tr>
                @endif
                @if($application->has_other_pets)
                    <tr>
                        <td class="info-label">Other pets</td>
                        <td class="info-value">{{ $application->other_pets_description ?? 'Yes' }}</td>
                    </tr>
                @endif
                @if($application->experience)
                    <tr>
                        <td class="info-label">Ownership experience</td>
                        <td class="info-value">{{ ['first_time' => 'First-time owner', 'some' => 'Some experience', 'experienced' => 'Experienced owner'][$application->experience] ?? ucfirst($application->experience) }}</td>
                    </tr>
                @endif
                @if($application->hours_alone !== null)
                    <tr>
                        <td class="info-label">Hours alone per day</td>
                        <td class="info-value">{{ $application->hours_alone }}h</td>
                    </tr>
                @endif
            </table>
        </div>
    @endif

    {{-- ===== ADOPTER STATEMENT ===== --}}
    @if($application->reason || $application->notes)
        <div class="section">
            <div class="section-title">Adopter's statement</div>
            @if($application->reason)
                <div class="statement-box">"{{ $application->reason }}"</div>
            @endif
            @if($application->notes)
                <div style="font-size:10px;color:#666;margin-top:8px;line-height:1.6;">
                    <strong>Additional notes:</strong> {{ $application->notes }}
                </div>
            @endif
        </div>
    @endif

    {{-- ===== MEET & GREET ===== --}}
    @if($application->meetGreet)
        <div class="section">
            <div class="section-title">Meet & greet record</div>
            <table class="info-table">
                <tr>
                    <td class="info-label">Date conducted</td>
                    <td class="info-value">{{ $application->meetGreet->scheduled_at->format('F j, Y · g:i A') }}</td>
                </tr>
                @if($application->meetGreet->volunteer)
                    <tr>
                        <td class="info-label">Facilitated by</td>
                        <td class="info-value">{{ $application->meetGreet->volunteer->name }}</td>
                    </tr>
                @endif
                @if($application->meetGreet->notes)
                    <tr>
                        <td class="info-label">Notes</td>
                        <td class="info-value">{{ $application->meetGreet->notes }}</td>
                    </tr>
                @endif
            </table>
        </div>
    @endif

    {{-- ===== TERMS ===== --}}
    <div class="section">
        <div class="section-title">Terms and conditions</div>
        <table class="terms-table">
            <tr>
                <td class="term-num">1.</td>
                <td>The adopter agrees to provide <strong>{{ $application->pet->name }}</strong> with adequate food,
                    clean water, shelter, and veterinary care for the duration of the animal's life.</td>
            </tr>
            <tr>
                <td class="term-num">2.</td>
                <td><strong>{{ $application->pet->name }}</strong> shall be kept as a companion animal and shall not be
                    used for breeding, fighting, experimentation, or any commercial purpose.</td>
            </tr>
            <tr>
                <td class="term-num">3.</td>
                <td>The adopter agrees to keep <strong>{{ $application->pet->name }}</strong> safely confined and not
                    allow the animal to roam freely without adequate supervision.</td>
            </tr>
            <tr>
                <td class="term-num">4.</td>
                <td>If the adopter is unable to continue caring for <strong>{{ $application->pet->name }}</strong> for
                    any reason, the adopter agrees to contact Homebound Shelter before rehoming the animal to any other
                    party.</td>
            </tr>
            <tr>
                <td class="term-num">5.</td>
                <td>Homebound Shelter reserves the right to reclaim <strong>{{ $application->pet->name }}</strong> if
                    credible evidence of abuse, neglect, or violation of this agreement is presented.</td>
            </tr>
            <tr>
                <td class="term-num">6.</td>
                <td>The adopter acknowledges receiving <strong>{{ $application->pet->name }}</strong> in good health and
                    confirms having been informed of any known medical conditions or special care requirements.</td>
            </tr>
            <tr>
                <td class="term-num">7.</td>
                <td>The adopter agrees to provide <strong>{{ $application->pet->name }}</strong> with regular veterinary
                    check-ups and keep all vaccinations and treatments up to date.</td>
            </tr>
            <tr>
                <td class="term-num">8.</td>
                <td>This agreement is legally binding upon signing and constitutes the complete understanding between
                    both parties regarding the adoption of <strong>{{ $application->pet->name }}</strong>.</td>
            </tr>
        </table>
    </div>

    {{-- ===== SIGNATURES ===== --}}
    <table class="sig-table">
        <tr>
            <td>
                <div class="sig-line"></div>
                <div class="sig-role">Adopter signature & date</div>
                <div class="sig-name">{{ $application->user->name }}</div>
            </td>
            <td>
                <div class="sig-line"></div>
                <div class="sig-role">Shelter representative signature & date</div>
                <div class="sig-name">Homebound Pet Adoption Shelter</div>
            </td>
        </tr>
    </table>

    {{-- ===== FOOTER ===== --}}
    <div class="footer">
        Homebound Pet Adoption Management System &nbsp;·&nbsp;
        Contract #HB-{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }} &nbsp;·&nbsp;
        Generated on {{ now()->format('F j, Y') }} &nbsp;·&nbsp;
        This document is computer-generated and valid without a physical stamp.
    </div>

</body>

</html>