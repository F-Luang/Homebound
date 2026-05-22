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
            margin-bottom: 20px;
            border-bottom: 2.5px solid #1D9E75;
            padding-bottom: 14px;
        }

        .org-tagline {
            font-size: 10px;
            color: #888;
            margin-top: 2px;
        }

        .org-contact {
            font-size: 9.5px;
            color: #aaa;
            margin-top: 3px;
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
            margin-bottom: 4px;
        }

        .contract-subtitle {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-bottom: 20px;
        }

        /* ===== SECTION ===== */
        .section {
            margin-bottom: 18px;
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
            padding: 3.5px 0;
            vertical-align: top;
            font-size: 11px;
        }

        .info-label {
            color: #888;
            width: 140px;
        }

        .info-value {
            font-weight: 600;
            color: #1a1a18;
        }

        /* ===== TWO COLUMN ===== */
        .two-col-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
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
            color: #333;
            line-height: 1.55;
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

        /* ===== ACKNOWLEDGMENT BOX ===== */
        .ack-box {
            border: 0.5px solid #d0ede4;
            border-radius: 6px;
            padding: 12px 14px;
            background: #f9fdf9;
            font-size: 10.5px;
            color: #333;
            line-height: 1.6;
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
            margin-top: 24px;
        }

        .sig-table td {
            width: 50%;
            vertical-align: bottom;
            padding-right: 36px;
        }

        .sig-table td:last-child {
            padding-right: 0;
            padding-left: 20px;
        }

        .sig-line {
            border-bottom: 1px solid #1a1a18;
            height: 44px;
            margin-bottom: 5px;
        }

        .sig-meta {
            display: table;
            width: 100%;
            margin-bottom: 6px;
        }

        .sig-meta-label {
            font-size: 9px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: table-cell;
            width: 50%;
        }

        .sig-date-line {
            border-bottom: 0.5px solid #aaa;
            display: inline-block;
            width: 100px;
            margin-left: 4px;
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
            margin-top: 2px;
        }

        .sig-printed {
            font-size: 9.5px;
            color: #888;
            margin-top: 2px;
        }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 28px;
            padding-top: 10px;
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
                     alt="Homebound" style="height:48px;display:block;">
                <div class="org-tagline">Pet Adoption Management System</div>
                <div class="org-contact">homebound.shelter@email.com &nbsp;·&nbsp; Davao City, Philippines</div>
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
        This agreement is entered into on <strong>{{ $application->updated_at->format('F j, Y') }}</strong>
        by and between <strong>Homebound Pet Adoption Shelter</strong> (hereinafter "the Shelter")
        and the adopter identified below (hereinafter "the Adopter").
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
                    @if(!empty($application->user->phone))
                        <tr>
                            <td class="info-label">Phone number</td>
                            <td class="info-value">{{ $application->user->phone }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="info-label">Home type</td>
                        <td class="info-value">
                            {{ $application->home_type ? ucfirst($application->home_type) . ($application->has_yard ? ', with yard' : '') : '—' }}
                        </td>
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
                <div class="section-title">Animal information</div>
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
                        <td class="info-label">Age at adoption</td>
                        <td class="info-value">
                            {{ floor($application->pet->age_months / 12) }} yr
                            {{ $application->pet->age_months % 12 }} mo
                        </td>
                    </tr>
                    <tr>
                        <td class="info-label">Size / Weight</td>
                        <td class="info-value">
                            {{ ucfirst($application->pet->size) }}
                            @if($application->pet->weight_kg) / {{ $application->pet->weight_kg }} kg @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="info-label">Shelter ID</td>
                        <td class="info-value">HB-PET-{{ str_pad($application->pet->id, 4, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ===== MEET & GREET ===== --}}
    @if($application->meetGreet)
        <div class="section">
            <div class="section-title">Meet &amp; greet record</div>
            <table class="info-table">
                <tr>
                    <td class="info-label">Date conducted</td>
                    <td class="info-value">{{ $application->meetGreet->scheduled_at->format('F j, Y — g:i A') }}</td>
                </tr>
                @if($application->meetGreet->volunteer)
                    <tr>
                        <td class="info-label">Facilitated by</td>
                        <td class="info-value">{{ $application->meetGreet->volunteer->name }}</td>
                    </tr>
                @endif
                @if($application->meetGreet->notes)
                    <tr>
                        <td class="info-label">Observations</td>
                        <td class="info-value">{{ $application->meetGreet->notes }}</td>
                    </tr>
                @endif
            </table>
        </div>
    @endif

    {{-- ===== ADOPTER STATEMENT ===== --}}
    @if($application->reason || $application->notes)
        <div class="section">
            <div class="section-title">Adopter's declaration</div>
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

    {{-- ===== TERMS AND CONDITIONS ===== --}}
    <div class="section">
        <div class="section-title">Terms and conditions of adoption</div>
        <table class="terms-table">
            <tr>
                <td class="term-num">1.</td>
                <td><strong>Care and welfare.</strong> The Adopter agrees to provide
                    <strong>{{ $application->pet->name }}</strong> with adequate food, clean water, safe shelter,
                    regular exercise, and prompt veterinary attention for the duration of the animal's life.</td>
            </tr>
            <tr>
                <td class="term-num">2.</td>
                <td><strong>Prohibited uses.</strong> <strong>{{ $application->pet->name }}</strong> shall be kept
                    solely as a companion animal and shall not be used for breeding, dog/cockfighting,
                    animal experimentation, resale, trade, or any commercial purpose whatsoever.</td>
            </tr>
            <tr>
                <td class="term-num">3.</td>
                <td><strong>No transfer without consent.</strong> The Adopter shall not give away, sell, transfer,
                    barter, or otherwise dispose of <strong>{{ $application->pet->name }}</strong> to any other party
                    without the written consent of the Shelter. If the Adopter can no longer care for the animal, the
                    Shelter must be contacted first and given the opportunity to reclaim the animal.</td>
            </tr>
            <tr>
                <td class="term-num">4.</td>
                <td><strong>Spay / neuter.</strong> If <strong>{{ $application->pet->name }}</strong> has not yet been
                    spayed or neutered at the time of adoption, the Adopter agrees to have the procedure performed
                    by a licensed veterinarian within a reasonable period and to provide proof to the Shelter upon
                    request.</td>
            </tr>
            <tr>
                <td class="term-num">5.</td>
                <td><strong>Confinement and safety.</strong> The Adopter agrees to keep
                    <strong>{{ $application->pet->name }}</strong> safely confined at all times and to prevent the
                    animal from roaming freely without adequate supervision, in compliance with local ordinances.</td>
            </tr>
            <tr>
                <td class="term-num">6.</td>
                <td><strong>Veterinary care.</strong> The Adopter agrees to provide
                    <strong>{{ $application->pet->name }}</strong> with regular veterinary check-ups, complete and
                    up-to-date vaccinations, and any necessary medical treatments as recommended by a licensed
                    veterinarian.</td>
            </tr>
            <tr>
                <td class="term-num">7.</td>
                <td><strong>Shelter follow-up.</strong> The Adopter agrees to cooperate with post-adoption follow-up
                    visits or check-ins conducted by the Shelter or its authorized representatives to verify the
                    animal's welfare.</td>
            </tr>
            <tr>
                <td class="term-num">8.</td>
                <td><strong>Right of reclaim.</strong> Homebound Shelter reserves the right to reclaim
                    <strong>{{ $application->pet->name }}</strong> without prior notice if credible evidence of abuse,
                    neglect, mistreatment, or any violation of the terms of this agreement is presented. The Adopter
                    waives the right to demand compensation in such cases.</td>
            </tr>
            <tr>
                <td class="term-num">9.</td>
                <td><strong>Acknowledgment of condition.</strong> The Adopter confirms having received
                    <strong>{{ $application->pet->name }}</strong> in good health and having been fully informed of
                    any known medical conditions, behavioral traits, and special care requirements prior to
                    adoption.</td>
            </tr>
            <tr>
                <td class="term-num">10.</td>
                <td><strong>Indemnification.</strong> The Shelter shall not be held liable for any injury, property
                    damage, illness, or death caused by <strong>{{ $application->pet->name }}</strong> after the date
                    of handover. The Adopter assumes full legal and financial responsibility for the animal from the
                    date of this agreement.</td>
            </tr>
            <tr>
                <td class="term-num">11.</td>
                <td><strong>Governing law.</strong> This agreement shall be governed by the laws of the Republic of
                    the Philippines, including Republic Act No. 8485 (Animal Welfare Act of 1998) and its amendments.
                    Any dispute arising from this agreement shall be settled amicably; failing which, it shall be
                    subject to the jurisdiction of the appropriate courts.</td>
            </tr>
        </table>
    </div>

    {{-- ===== HANDOVER ACKNOWLEDGMENT ===== --}}
    <div class="section">
        <div class="section-title">Handover acknowledgment</div>
        <div class="ack-box">
            I, <strong>{{ $application->user->name }}</strong>, hereby confirm that I have received
            <strong>{{ $application->pet->name }}</strong> ({{ ucfirst($application->pet->species) }},
            {{ $application->pet->breed ?? 'Mixed' }}, {{ ucfirst($application->pet->gender ?? 'Unknown') }})
            from Homebound Pet Adoption Shelter on <strong>{{ $application->updated_at->format('F j, Y') }}</strong>.
            I have read, understood, and voluntarily agreed to all the terms and conditions set forth in this
            Adoption Agreement. I understand that this document is legally binding upon my signature.
        </div>
    </div>

    {{-- ===== SIGNATURES ===== --}}
    <div class="section">
        <div class="section-title">Signatures</div>
        <table class="sig-table">
            <tr>
                <td>
                    <div class="sig-line"></div>
                    <div class="sig-role">Adopter signature</div>
                    <div class="sig-name">{{ $application->user->name }}</div>
                    <div class="sig-printed">
                        Printed name: ________________________________
                    </div>
                    <div class="sig-printed" style="margin-top:4px;">
                        Date: ________________________________
                    </div>
                </td>
                <td>
                    <div class="sig-line"></div>
                    <div class="sig-role">Shelter representative signature</div>
                    <div class="sig-name">Homebound Pet Adoption Shelter</div>
                    <div class="sig-printed">
                        Printed name &amp; designation: ________________________________
                    </div>
                    <div class="sig-printed" style="margin-top:4px;">
                        Date: ________________________________
                    </div>
                </td>
            </tr>
        </table>

        {{-- Witness --}}
        <table style="width:50%;border-collapse:collapse;margin-top:20px;">
            <tr>
                <td style="vertical-align:bottom;padding-right:36px;">
                    <div class="sig-line"></div>
                    <div class="sig-role">Witness signature</div>
                    <div class="sig-printed">
                        Printed name: ________________________________
                    </div>
                    <div class="sig-printed" style="margin-top:4px;">
                        Date: ________________________________
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ===== FOOTER ===== --}}
    <div class="footer">
        Homebound Pet Adoption Management System &nbsp;·&nbsp;
        Contract #HB-{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }} &nbsp;·&nbsp;
        Generated {{ now()->format('F j, Y \a\t g:i A') }} &nbsp;·&nbsp;
        This document is computer-generated. Signatures render it legally binding.
    </div>

</body>
</html>
