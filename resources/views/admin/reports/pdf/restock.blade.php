<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; margin: 0; padding: 20px; }
        h1 { font-size: 16px; font-weight: 700; margin: 0 0 4px; }
        .sub { font-size: 10px; color: #64748b; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #f8fafc; font-size: 9px; font-weight: 700; text-transform: uppercase;
             letter-spacing: .04em; color: #64748b; padding: 7px 10px; border-bottom: 2px solid #e2e8f0; text-align: left; }
        td { padding: 7px 10px; border-bottom: 1px solid #f1f5f9; font-size: 10px; }
        .section-title { font-size: 12px; font-weight: 700; color: #1e293b; margin: 16px 0 6px; padding-bottom: 4px; border-bottom: 1px solid #e2e8f0; }
        .badge-red  { background: #fef2f2; color: #dc2626; padding: 2px 7px; border-radius: 4px; font-size: 9px; font-weight: 600; }
        .badge-blue { background: #eff6ff; color: #1d4ed8; padding: 2px 7px; border-radius: 4px; font-size: 9px; font-weight: 600; }
        .badge-warn { background: #fffbeb; color: #d97706; padding: 2px 7px; border-radius: 4px; font-size: 9px; font-weight: 600; }
        .stats { display: table; width: 100%; margin-bottom: 20px; }
        .stat-box { display: table-cell; width: 25%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 6px; }
        .stat-val { font-size: 20px; font-weight: 700; }
        .stat-lbl { font-size: 9px; color: #64748b; }
        .footer { font-size: 9px; color: #94a3b8; margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 8px; }
    </style>
</head>
<body>

<h1>ACLC Clinic — Restock Report</h1>
<div class="sub">{{ now()->format('F j, Y \a\t g:i A') }} — ACLC College of Mandaue</div>

{{-- Stats --}}
@php $totalUsed = $mostUsed->sum('total_used'); @endphp
<table style="margin-bottom:20px;">
    <tr>
        <td style="width:25%;padding:10px 12px;border:1px solid #e2e8f0;border-radius:6px;">
            <div style="font-size:20px;font-weight:700;color:#dc2626;">{{ $lowStock->count() }}</div>
            <div style="font-size:9px;color:#64748b;">Items Need Restock</div>
        </td>
        <td style="width:5%;"></td>
        <td style="width:25%;padding:10px 12px;border:1px solid #e2e8f0;border-radius:6px;">
            <div style="font-size:20px;font-weight:700;color:#dc2626;">{{ $expired->count() }}</div>
            <div style="font-size:9px;color:#64748b;">Expired Items</div>
        </td>
        <td style="width:5%;"></td>
        <td style="width:25%;padding:10px 12px;border:1px solid #e2e8f0;border-radius:6px;">
            <div style="font-size:20px;font-weight:700;color:#d97706;">{{ $expiringSoon->count() }}</div>
            <div style="font-size:9px;color:#64748b;">Expiring in 30 Days</div>
        </td>
        <td style="width:5%;"></td>
        <td style="width:25%;padding:10px 12px;border:1px solid #1d4ed8;background:#1d4ed8;border-radius:6px;">
            <div style="font-size:20px;font-weight:700;color:#fff;">{{ number_format($totalUsed) }}</div>
            <div style="font-size:9px;color:rgba(255,255,255,.7);">Total Units Dispensed</div>
        </td>
    </tr>
</table>

{{-- Low Stock --}}
<div class="section-title">Items Needing Restock</div>
<table>
    <thead><tr><th>Medication</th><th>Category</th><th>Production Date</th><th>Qty</th><th>Threshold</th></tr></thead>
    <tbody>
        @forelse($lowStock as $med)
        <tr>
            <td>{{ $med->name }}</td>
            <td>{{ $med->category->name ?? '—' }}</td>
            <td>{{ $med->production_date?->format('M d, Y') ?? '—' }}</td>
            <td><span class="badge-red">{{ $med->quantity }} {{ $med->unit }}</span></td>
            <td style="color:#94a3b8;">{{ $med->low_stock_threshold }}</td>
        </tr>
        @empty
        <tr><td colspan="5" style="color:#94a3b8;text-align:center;padding:12px;">No low stock items.</td></tr>
        @endforelse
    </tbody>
</table>

{{-- Most Used --}}
<div class="section-title">Most Frequently Used</div>
<table>
    <thead><tr><th>Medication</th><th>Category</th><th>Total Used</th></tr></thead>
    <tbody>
        @forelse($mostUsed as $row)
        @if($row->medication)
        <tr>
            <td>{{ $row->medication->name }}</td>
            <td>{{ $row->medication->category->name ?? '—' }}</td>
            <td><span class="badge-blue">{{ number_format($row->total_used) }} {{ $row->medication->unit }}</span></td>
        </tr>
        @endif
        @empty
        <tr><td colspan="3" style="color:#94a3b8;text-align:center;padding:12px;">No usage data yet.</td></tr>
        @endforelse
    </tbody>
</table>

@if($expired->isNotEmpty())
<div class="section-title">Expired Medications</div>
<table>
    <thead><tr><th>Medication</th><th>Category</th><th>Qty</th><th>Production Date</th><th>Expired On</th></tr></thead>
    <tbody>
        @foreach($expired as $med)
        <tr>
            <td>{{ $med->name }}</td>
            <td>{{ $med->category->name ?? '—' }}</td>
            <td>{{ $med->quantity }} {{ $med->unit }}</td>
            <td>{{ $med->production_date?->format('M d, Y') ?? '—' }}</td>
            <td><span class="badge-red">{{ $med->expiry_date->format('M d, Y') }}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@if($expiringSoon->isNotEmpty())
<div class="section-title">Expiring Within 30 Days</div>
<table>
    <thead><tr><th>Medication</th><th>Category</th><th>Qty</th><th>Production Date</th><th>Expiry Date</th><th>Days Left</th></tr></thead>
    <tbody>
        @foreach($expiringSoon as $med)
        <tr>
            <td>{{ $med->name }}</td>
            <td>{{ $med->category->name ?? '—' }}</td>
            <td>{{ $med->quantity }} {{ $med->unit }}</td>
            <td>{{ $med->production_date?->format('M d, Y') ?? '—' }}</td>
            <td><span class="badge-warn">{{ $med->expiry_date->format('M d, Y') }}</span></td>
            <td style="color:#d97706;font-weight:600;">{{ now()->diffInDays($med->expiry_date) }} days</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<div class="footer">Generated by ACLC Clinic Information &amp; Inventory System &middot; {{ now()->format('F j, Y \a\t g:i A') }}</div>
</body>
</html>
