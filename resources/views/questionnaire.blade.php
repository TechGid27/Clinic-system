<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <title>Questionnaire — ACLC Clinic</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            margin: 0; padding: 2rem 1rem;
            color: #1e293b;
        }

        .q-wrapper {
            max-width: 860px;
            margin: 0 auto;
        }

        /* Header */
        .q-header {
            background: #0f1b2d;
            border-radius: 14px 14px 0 0;
            padding: 2rem 2.5rem 1.75rem;
            text-align: center;
            color: #fff;
        }
        .q-header .logo-row {
            display: flex; align-items: center; justify-content: center; gap: .75rem;
            margin-bottom: 1.25rem;
        }
        .q-header .logo-icon {
            width: 44px; height: 44px;
            background: rgba(255,255,255,.1);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .q-header .logo-icon img { width: 30px; }
        .q-header .logo-name { font-weight: 700; font-size: 1rem; line-height: 1.2; }
        .q-header .logo-sub  { font-size: .72rem; color: rgba(255,255,255,.5); }
        .q-header h1 {
            font-size: 1.15rem; font-weight: 700;
            margin: 0 0 .35rem;
            line-height: 1.4;
        }
        .q-header p {
            font-size: .8rem; color: rgba(255,255,255,.6);
            margin: 0;
        }

        /* Scale legend */
        .scale-legend {
            background: #1d4ed8;
            padding: .65rem 2.5rem;
            display: flex; flex-wrap: wrap; gap: .5rem 1.5rem;
            justify-content: center;
        }
        .scale-legend span {
            font-size: .75rem; color: #fff; font-weight: 500;
        }
        .scale-legend .sep { color: rgba(255,255,255,.3); }

        /* Respondent info */
        .respondent-box {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-top: none;
            padding: 1.25rem 2.5rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .75rem 2rem;
        }
        .respondent-box .field-label {
            font-size: .72rem; font-weight: 600; color: #64748b;
            text-transform: uppercase; letter-spacing: .05em;
            margin-bottom: .2rem;
        }
        .respondent-box .field-line {
            border-bottom: 1.5px solid #cbd5e1;
            height: 24px;
        }

        /* Part sections */
        .q-part {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-top: none;
        }
        .q-part:last-child {
            border-radius: 0 0 14px 14px;
        }

        .part-header {
            padding: .9rem 2.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; gap: .75rem;
        }
        .part-badge {
            width: 28px; height: 28px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: .72rem; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }
        .part-badge.before  { background: #64748b; }
        .part-badge.during  { background: #1d4ed8; }
        .part-badge.after   { background: #059669; }

        .part-title {
            font-size: .9rem; font-weight: 700; color: #1e293b;
        }
        .part-subtitle {
            font-size: .75rem; color: #64748b; margin-top: .1rem;
        }

        /* Table */
        .q-table {
            width: 100%;
            border-collapse: collapse;
        }
        .q-table thead tr {
            background: #f8fafc;
        }
        .q-table thead th {
            font-size: .7rem; font-weight: 700;
            color: #64748b; text-transform: uppercase;
            letter-spacing: .05em;
            padding: .6rem 1rem;
            border-bottom: 1px solid #e2e8f0;
            text-align: center;
        }
        .q-table thead th.th-no    { width: 42px; text-align: center; }
        .q-table thead th.th-stmt  { text-align: left; }
        .q-table thead th.th-score { width: 52px; }

        .q-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background .15s;
        }
        .q-table tbody tr:last-child { border-bottom: none; }
        .q-table tbody tr:hover { background: #f8fafc; }

        .q-table tbody td {
            padding: .7rem 1rem;
            font-size: .82rem; color: #334155;
            vertical-align: middle;
        }
        .q-table tbody td.td-no {
            text-align: center; font-weight: 600;
            color: #94a3b8; font-size: .78rem;
        }
        .q-table tbody td.td-score {
            text-align: center;
        }

        /* Radio circles */
        .radio-circle {
            width: 22px; height: 22px;
            border: 2px solid #cbd5e1;
            border-radius: 50%;
            display: inline-block;
            cursor: pointer;
            transition: border-color .15s, background .15s;
            vertical-align: middle;
        }
        .radio-circle:hover {
            border-color: #1d4ed8;
            background: rgba(29,78,216,.06);
        }

        /* Score header colors per part */
        .before  .q-table thead th.th-score { color: #64748b; }
        .during  .q-table thead th.th-score { color: #1d4ed8; }
        .after   .q-table thead th.th-score { color: #059669; }

        /* Print button */
        .print-bar {
            display: flex; justify-content: flex-end; gap: .75rem;
            margin-bottom: 1.25rem;
        }
        .btn-print {
            background: #1d4ed8; color: #fff;
            border: none; border-radius: 8px;
            padding: .5rem 1.25rem;
            font-size: .82rem; font-weight: 600;
            cursor: pointer; text-decoration: none;
            display: flex; align-items: center; gap: .4rem;
            transition: background .15s, transform .15s;
        }
        .btn-print:hover { background: #1e40af; transform: translateY(-1px); color: #fff; }

        .btn-back {
            background: #fff; color: #64748b;
            border: 1px solid #e2e8f0; border-radius: 8px;
            padding: .5rem 1.25rem;
            font-size: .82rem; font-weight: 600;
            cursor: pointer; text-decoration: none;
            display: flex; align-items: center; gap: .4rem;
            transition: background .15s;
        }
        .btn-back:hover { background: #f8fafc; color: #1e293b; }

        /* Footer note */
        .q-footer {
            text-align: center;
            padding: 1.25rem 2.5rem;
            font-size: .72rem; color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            background: #fff;
            border-radius: 0 0 14px 14px;
        }

    </style>
</head>
<body>

<div class="q-wrapper">

    {{-- Print / Back bar --}}
    <div class="print-bar">
        @auth
        <a href="{{ url()->previous() }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        @endauth
        <a href="{{ route('questionnaire.download') }}" class="btn-print">
            <i class="bi bi-file-earmark-pdf"></i> Download PDF
        </a>
    </div>

    {{-- ── HEADER ── --}}
    <div class="q-header">
        <div class="logo-row">
            <div class="logo-icon">
                <img src="/newAclcLogo-BQdiVkLw-removebg-preview.png" alt="ACLC Logo">
            </div>
            <div>
                <div class="logo-name">ACLC College of Mandaue</div>
                <div class="logo-sub">Clinic Information & Medical Inventory Management System</div>
            </div>
        </div>
        <h1>Questionnaire on the Effectiveness of the<br>Clinic Information and Medical Inventory Management System</h1>
        <p>Please rate each statement honestly based on your experience.</p>
    </div>

    {{-- ── SCALE LEGEND ── --}}
    <div class="scale-legend">
        <span><strong>5</strong> – Strongly Agree</span>
        <span class="sep">|</span>
        <span><strong>4</strong> – Agree</span>
        <span class="sep">|</span>
        <span><strong>3</strong> – Neutral</span>
        <span class="sep">|</span>
        <span><strong>2</strong> – Disagree</span>
        <span class="sep">|</span>
        <span><strong>1</strong> – Strongly Disagree</span>
    </div>

    {{-- ── RESPONDENT INFO ── --}}
    <div class="respondent-box">
        <div>
            <div class="field-label">Name (Optional)</div>
            <div class="field-line"></div>
        </div>
        <div>
            <div class="field-label">Role</div>
            <div class="field-line"></div>
        </div>
        <div>
            <div class="field-label">Date</div>
            <div class="field-line"></div>
        </div>
        <div>
            <div class="field-label">Course / Department</div>
            <div class="field-line"></div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         PART I — BEFORE (Manual System)
    ══════════════════════════════════════════ --}}
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

    <div class="q-part before">
        <div class="part-header">
            <div class="part-badge before">I</div>
            <div>
                <div class="part-title">Part I: Before — Manual System</div>
                <div class="part-subtitle">Rate the existing manual clinic management process.</div>
            </div>
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
         PART II — DURING (System Use)
    ══════════════════════════════════════════ --}}
    <div class="q-part during">
        <div class="part-header">
            <div class="part-badge during">II</div>
            <div>
                <div class="part-title">Part II: During — System Use</div>
                <div class="part-subtitle">Rate your experience while using the Clinic Information and Medical Inventory Management System.</div>
            </div>
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
         PART III — AFTER (Automated System)
    ══════════════════════════════════════════ --}}
    <div class="q-part after">
        <div class="part-header">
            <div class="part-badge after">III</div>
            <div>
                <div class="part-title">Part III: After — Automated System</div>
                <div class="part-subtitle">Rate the overall effectiveness of the automated system compared to the manual process.</div>
            </div>
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

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
