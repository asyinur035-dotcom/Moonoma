<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report User Notification - Moonoma</title>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #0d0f0d; margin: 0; padding: 0; color: #ffffff; }
        .container { max-width: 600px; margin: 40px auto; background-color: #0f1110; border: 1px solid #3E5641; border-radius: 20px; padding: 40px; text-align: center; }
        .logo { margin-bottom: 30px; }
        h1 { font-size: 24px; margin-bottom: 20px; color: #e87c7c; }
        p { font-size: 14px; line-height: 1.6; color: #888888; margin-bottom: 30px; }
        .footer { margin-top: 40px; font-size: 12px; color: #444444; }
        
        .report-box { background: rgba(232, 124, 124, 0.05); border: 1px solid rgba(232, 124, 124, 0.2); border-radius: 10px; padding: 25px; text-align: left; margin-bottom: 20px;}
        .report-label { color: #e87c7c; font-size: 11px; font-weight: bold; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid rgba(232, 124, 124, 0.2); padding-bottom: 5px;}
        .report-value { color: #fff; font-size: 14px; margin-bottom: 20px; word-break: break-all; line-height: 1.5; }
        .report-value strong { color: #ccc; font-weight: 500; display: inline-block; width: 60px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="Moonoma Logo" style="width: 200px; height: auto; max-width: 100%;">
        </div>
        <h1>User Report Alert</h1>
        <p style="text-align: center;">A user has been reported for a violation. Please review the details below.</p>
        
        <div class="report-box">
            <div class="report-label">Reporter Details</div>
            <div class="report-value">
                <strong>Name:</strong> {{ $reporterName }}<br>
                <strong>Email:</strong> {{ $reporterEmail }}
            </div>

            <div class="report-label">Reported User</div>
            <div class="report-value">
                <strong>Name:</strong> {{ $targetName }}<br>
                <strong>Email:</strong> {{ $targetEmail }}
            </div>

            <div class="report-label">Context & Reason</div>
            <div class="report-value">
                <strong>Room:</strong> {{ $roomName }} (Slug: {{ $roomSlug }})<br>
                <div style="margin-top: 8px; padding: 10px; background: rgba(0,0,0,0.3); border-radius: 5px; font-style: italic; color: #bbb;">
                    "{{ $reason }}"
                </div>
            </div>

            <div class="report-label">Proof / Attachment</div>
            <div class="report-value" style="margin-bottom: 0;">
                @if($path)
                    <a href="{{ $path }}" target="_blank" style="color: #c9a227; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg>
                        View Attached File
                    </a>
                @else
                    <span style="color: #888;">No attachment provided.</span>
                @endif
            </div>
        </div>
        
        <div class="footer">
            &copy; {{ date('Y') }} Moonoma Project. All rights reserved.<br>
            Where Skills Meet Vision.
        </div>
    </div>
</body>
</html>
