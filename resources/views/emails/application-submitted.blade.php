<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: #F5F4F0;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            max-width: 560px;
            margin: 40px auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
        }

        .header {
            background: #1D9E75;
            padding: 28px 32px;
            text-align: center;
        }

        .header-title {
            color: white;
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .header-sub {
            color: rgba(255, 255, 255, 0.8);
            font-size: 13px;
            margin-top: 4px;
        }

        .body {
            padding: 32px;
        }

        .greeting {
            font-size: 16px;
            font-weight: 600;
            color: #1a1a18;
            margin-bottom: 12px;
        }

        .text {
            font-size: 14px;
            color: #555;
            line-height: 1.7;
            margin-bottom: 16px;
        }

        .pet-card {
            background: #F5F4F0;
            border-radius: 12px;
            padding: 16px 20px;
            margin: 20px 0;
        }

        .pet-name {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a18;
            margin-bottom: 4px;
        }

        .pet-detail {
            font-size: 13px;
            color: #888;
        }

        .status-badge {
            display: inline-block;
            background: #E1F5EE;
            color: #0F6E56;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 20px;
        }

        .steps {
            background: #f9fdf9;
            border-radius: 10px;
            padding: 16px 20px;
            margin: 20px 0;
        }

        .steps-title {
            font-size: 12px;
            font-weight: 600;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 12px;
        }

        .step {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 8px;
        }

        .step-dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #1D9E75;
            color: white;
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .step-text {
            font-size: 13px;
            color: #444;
            line-height: 1.5;
        }

        .btn {
            display: inline-block;
            background: #1D9E75;
            color: white;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            margin: 8px 0;
        }

        .footer {
            padding: 20px 32px;
            border-top: 1px solid #f0f0ec;
            text-align: center;
        }

        .footer-text {
            font-size: 11px;
            color: #aaa;
            line-height: 1.6;
        }

        .divider {
            border: none;
            border-top: 1px solid #f0f0ec;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="header">
            <div class="header-title">🐾 Homebound</div>
            <div class="header-sub">Pet Adoption Management System</div>
        </div>
        <div class="body">
            <div class="greeting">Hi {{ $application->user->name }},</div>
            <p class="text">
                Thank you for submitting your adoption application! We've received it and our team will review it
                shortly.
            </p>

            <div class="pet-card">
                <div class="pet-name">{{ $application->pet->name }}</div>
                <div class="pet-detail">
                    {{ ucfirst($application->pet->species) }}
                    @if($application->pet->breed) · {{ $application->pet->breed }} @endif
                    · {{ floor($application->pet->age_months / 12) }}y {{ $application->pet->age_months % 12 }}m
                    · {{ ucfirst($application->pet->size) }}
                </div>
            </div>

            <span class="status-badge">● Pending review</span>

            <div class="steps">
                <div class="steps-title">What happens next</div>
                <div class="step">
                    <div class="step-dot">1</div>
                    <div class="step-text"><strong>Review</strong> — Our team reviews your application and background.
                    </div>
                </div>
                <div class="step">
                    <div class="step-dot">2</div>
                    <div class="step-text"><strong>Meet & greet</strong> — We schedule a visit for you to meet
                        {{ $application->pet->name }}.
                    </div>
                </div>
                <div class="step">
                    <div class="step-dot">3</div>
                    <div class="step-text"><strong>Home check</strong> — A volunteer visits your home to ensure it's a
                        great fit.</div>
                </div>
                <div class="step">
                    <div class="step-dot">4</div>
                    <div class="step-text"><strong>Approval</strong> — Once approved, {{ $application->pet->name }}
                        comes home with you!</div>
                </div>
            </div>

            <p class="text">
                You can track your application status anytime by logging into Homebound.
            </p>

            <hr class="divider">
            <p class="text" style="font-size:13px;color:#888;">
                Application submitted: {{ $application->submitted_at->format('F j, Y · g:i A') }}<br>
                Application ID: #{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}
            </p>
        </div>
        <div class="footer">
            <div class="footer-text">
                This email was sent by Homebound Pet Adoption System.<br>
                Please do not reply to this email.
            </div>
        </div>
    </div>
</body>

</html>