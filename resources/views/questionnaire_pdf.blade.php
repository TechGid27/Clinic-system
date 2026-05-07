<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Questionnaire — ACLC Clinic</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9pt;
            color: #1e293b;
            background: #fff;
        }

        /* ── HEADER ── */
        .q-header {
            background-color: #0f1b2d;
            color: #ffffff;
            padding: 14pt 20pt 12pt;
            text-align: center;
        }
        .q-header .school-name {
            font-size: 10pt;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 2pt;
        }
        .q-header .school-sub {
            font-size: 7.5pt;
            color: rgba(255,255,255,0.6);
            margin-bottom: 8pt;
        }
        .q-header h1 {
            font-size: 10.5pt;
            font-weight: bold;
            color: #ffffff;
            line-height: 1.4;
            margin-bottom: 3pt;
        }
        .q-header .subtitle {
            font-size: 7.5pt;
            color: rgba(255,255,255,0.65);
        }

        /* ── SCALE LEGEND ── */
        .scale-bar {
            background-color: #1d4ed8;
            padding: 5pt 20pt;
            text-align: center;
            font-size: 7.5pt;
            color: #ffffff;
        }

        /* ── RESPONDENT BOX ── */
        .respondent-box {
            border: 1pt solid #e2e8f0;
            border-top: none;
            padding: 10pt 20pt;
        }
        .respondent-table {
            width: 100%;
            border-collapse: collapse;
        }
        .respondent-table td {
            width: 50%;
            padding: 4pt 6pt 4pt 0;
            vertical-align: bottom;
        }
        .field-label {
            font-size: 6.5pt;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            display: block;
            margin-bottom: 3pt;
        }
        .field-line {
            border-bottom: 1pt solid #94a3b8;
            height: 12pt;
            display: block;
        }

        /* ── PART SECTIONS ── */
        .part-section {
            border: 1pt solid #e2e8f0;
            border-top: none;
            margin-bottom: 0;
        }

        .part-header {
            padding: 7pt 20pt;
            border-bottom: 1pt solid #e2e8f0;
        }
        .part-header-before { background-color: #f1f5f9; }
        .part-header-during { background-color: #eff6ff; }
        .part-header-after  { background-color: #f0fdf4; }

        .part-badge {
            display: inline-block;
            width: 16pt; height: 16pt;
            border-radius: 50%;
            text-align: center;
            line-height: 16pt;
            font-size: 7pt;
            font-weight: bold;
            color: #ffffff;
            margin-right: 6pt;
            vertical-align: middle;
        }
        .badge-before { background-color: #64748b; }
        .badge-during { background-color: #1d4ed8; }
        .badge-after  { background-color: #059669; }

        .part-title {
            font-size: 9pt;
            font-weight: bold;
            vertical-align: middle;
        }
        .part-title-before { color: #334155; }
        .part-title-during { color: #1e40af; }
        .part-title-after  { color: #065f46; }

        .part-subtitle {
            font-size: 7pt;
            color: #64748b;
            margin-top: 2pt;
            padding-left: 22pt;
        }

        /* ── QUESTION TABLE ── */
        .q-table {
            width: 100%;
            border-collapse: collapse;
        }
        .q-table thead tr {
            background-color: #f8fafc;
        }
        .q-table thead th {
            font-size: 7pt;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 5pt 8pt;
            border-bottom: 1pt solid #e2e8f0;
            text-align: center;
        }
        .q-table thead th.th-stmt { text-align: left; }
        .q-table thead th.th-no   { width: 24pt; }
        .q-table thead th.th-score { width: 30pt; }

        .q-table tbody tr {
            border-bottom: 0.5pt solid #f1f5f9;
        }
        .q-table tbody tr:last-child { border-bottom: none; }

        .q-table tbody td {
            padding: 5pt 8pt;
            font-size: 8pt;
            color: #334155;
            vertical-align: middle;
        }
        .q-table tbody td.td-no {
            text-align: center;
            font-weight: bold;
            color: #94a3b8;
            font-size: 7.5pt;
        }
        .q-table tbody td.td-score {
            text-align: center;
        }

        /* Radio circle drawn with border */
        .radio-circle {
            display: inline-block;
            width: 11pt; height: 11pt;
            border: 1.5pt solid #cbd5e1;
            border-radius: 50%;
        }

        /* ── FOOTER ── */
        .q-footer {
            border: 1pt solid #e2e8f0;
            border-top: 1pt solid #e2e8f0;
            padding: 8pt 20pt;
            text-align: center;
            font-size: 7pt;
            color: #94a3b8;
            background-color: #fff;
        }

        /* page break helpers */
        .page-break { page-break-after: always; }
        .no-break    { page-break-inside: avoid; }
    </style>
</head>
<body>

@php
    $partI = [
        'The manual system is easy to use.',
        'Recording student information manually is accurate.',
        'The manual system rarely produces errors.',
        'The process of filling out records is fast.',
        'Searching for past records is easy.',
        'The manual system prevents duplication of entries.',
        'The information recorded is always complete.',
        'The system is reliable for tracking student clinic visits.',
        'The manual process does not cause delays.',
        'I am satisfied with the current manual clinic system.',
    ];

    $partII = [
        'The system is easy to understand and use.',
        'Entering student data in the system is simple.',
        'The system processes requests quickly.',
        'The system reduces manual work.',
        'The system minimizes errors during data entry.',
        'The system responds quickly.',
        'The system is accessible when needed.',
        'The interface is user-friendly.',
        'The system performs consistently.',
        'I feel comfortable using the system.',
    ];

    $partIII = [
        'The system reduces recording errors.',
        'The system improves accuracy.',
        'The system speeds up the process.',
        'The system makes record retrieval easier.',
        'The system ensures complete information.',
        'The system is reliable.',
        'The system securely stores data.',
        'The system improves efficiency.',
        'I am satisfied with the system.',
        'I prefer this system over the manual system.',
    ];
@endphp

{{-- ── HEADER ── --}}
<div class="q-header">
    <div class="school-name">ACLC College of Mandaue</div>
    <div class="school-sub">Clinic Information &amp; Medical Inventory Management System</div>
    <h1>Questionnaire on the Effectiveness of the<br>Clinic Information and Medical Inventory Management System</h1>
    <div class="subtitle">Please rate each statement honestly based on your experience.</div>
</div>

{{-- ── SCALE LEGEND ── --}}
<div class="scale-bar">
    <strong>5</strong> – Strongly Agree &nbsp;|&nbsp;
    <strong>4</strong> – Agree &nbsp;|&nbsp;
    <strong>3</strong> – Neutral &nbsp;|&nbsp;
    <strong>2</strong> – Disagree &nbsp;|&nbsp;
    <strong>1</strong> – Strongly Disagree
</div>

{{-- ── RESPONDENT INFO ── --}}
<div class="respondent-box">
    <table class="respondent-table">
        <tr>
            <td>
                <span class="field-label">Name (Optional)</span>
                <span class="field-line"></span>
            </td>
            <td style="padding-left:12pt;">
                <span class="field-label">Role</span>
                <span class="field-line"></span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="field-label">Date</span>
                <span class="field-line"></span>
            </td>
            <td style="padding-left:12pt;">
                <span class="field-label">Course / Department (if student)</span>
                <span class="field-line"></span>
            </td>
        </tr>
    </table>
</div>

{{-- ══════════════════════════════════════════
     PART I — BEFORE
══════════════════════════════════════════ --}}
<div class="part-section no-break">
    <div class="part-header part-header-before">
        <span class="part-badge badge-before">I</span>
        <span class="part-title part-title-before">Part I: Before — Manual System</span>
        <div class="part-subtitle">Rate the existing manual clinic management process.</div>
    </div>
    <table class="q-table">
        <thead>
            <tr>
                <th class="th-no">No.</th>
                <th class="th-stmt">Statement</th>
                <th class="th-score">5</th>
                <th class="th-score">4</th>
                <th class="th-score">3</th>
                <th class="th-score">2</th>
                <th class="th-score">1</th>
            </tr>
        </thead>
        <tbody>
            @foreach($partI as $i => $stmt)
            <tr>
                <td class="td-no">{{ $i + 1 }}</td>
                <td>{{ $stmt }}</td>
                @for($s = 5; $s >= 1; $s--)
                <td class="td-score"><span class="radio-circle"></span></td>
                @endfor
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- ══════════════════════════════════════════
     PART II — DURING
══════════════════════════════════════════ --}}
<div class="part-section no-break">
    <div class="part-header part-header-during">
        <span class="part-badge badge-during">II</span>
        <span class="part-title part-title-during">Part II: During — System Use</span>
        <div class="part-subtitle">Rate your experience while using the Clinic Information and Medical Inventory Management System.</div>
    </div>
    <table class="q-table">
        <thead>
            <tr>
                <th class="th-no">No.</th>
                <th class="th-stmt">Statement</th>
                <th class="th-score">5</th>
                <th class="th-score">4</th>
                <th class="th-score">3</th>
                <th class="th-score">2</th>
                <th class="th-score">1</th>
            </tr>
        </thead>
        <tbody>
            @foreach($partII as $i => $stmt)
            <tr>
                <td class="td-no">{{ $i + 1 }}</td>
                <td>{{ $stmt }}</td>
                @for($s = 5; $s >= 1; $s--)
                <td class="td-score"><span class="radio-circle"></span></td>
                @endfor
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- ══════════════════════════════════════════
     PART III — AFTER
══════════════════════════════════════════ --}}
<div class="part-section no-break">
    <div class="part-header part-header-after">
        <span class="part-badge badge-after">III</span>
        <span class="part-title part-title-after">Part III: After — Automated System</span>
        <div class="part-subtitle">Rate the overall effectiveness of the automated system compared to the manual process.</div>
    </div>
    <table class="q-table">
        <thead>
            <tr>
                <th class="th-no">No.</th>
                <th class="th-stmt">Statement</th>
                <th class="th-score">5</th>
                <th class="th-score">4</th>
                <th class="th-score">3</th>
                <th class="th-score">2</th>
                <th class="th-score">1</th>
            </tr>
        </thead>
        <tbody>
            @foreach($partIII as $i => $stmt)
            <tr>
                <td class="td-no">{{ $i + 1 }}</td>
                <td>{{ $stmt }}</td>
                @for($s = 5; $s >= 1; $s--)
                <td class="td-score"><span class="radio-circle"></span></td>
                @endfor
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- ── FOOTER ── --}}
<div class="q-footer">
    Thank you for your participation. Your responses will help improve the clinic's services at ACLC College of Mandaue.
</div>

</body>
</html>
