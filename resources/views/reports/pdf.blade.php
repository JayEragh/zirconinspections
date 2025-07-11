<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspection Report #{{ $report->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 18px;
            color: #666;
            margin-bottom: 10px;
        }
        .report-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .info-section {
            flex: 1;
        }
        .info-section h3 {
            color: #007bff;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .info-item {
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .content-section {
            margin-bottom: 25px;
        }
        .content-section h3 {
            color: #007bff;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .content-text {
            line-height: 1.6;
            text-align: justify;
        }
        .technical-data {
            margin-top: 20px;
        }
        .technical-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 15px;
        }
        .technical-item {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-approved {
            background: #d4edda;
            color: #155724;
        }
        .status-submitted {
            background: #fff3cd;
            color: #856404;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">Zircon Inspections</div>
        <div class="report-title">Inspection Report</div>
        <div>Report #{{ $report->id }}</div>
    </div>

    <div class="report-info">
        <div class="info-section">
            <h3>Report Details</h3>
            <div class="info-item">
                <span class="info-label">Report ID:</span> {{ $report->id }}
            </div>
            <div class="info-item">
                <span class="info-label">Title:</span> {{ $report->title }}
            </div>
            <div class="info-item">
                <span class="info-label">Status:</span> 
                <span class="status-badge status-{{ $report->status }}">
                    {{ ucfirst($report->status) }}
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Created:</span> {{ $report->created_at->format('M d, Y H:i') }}
            </div>
            @if($report->approved_at)
            <div class="info-item">
                <span class="info-label">Approved:</span> {{ $report->approved_at->format('M d, Y H:i') }}
            </div>
            @endif
        </div>

        <div class="info-section">
            <h3>Service Request</h3>
            <div class="info-item">
                <span class="info-label">Service ID:</span> {{ $report->serviceRequest->service_id }}
            </div>
            <div class="info-item">
                <span class="info-label">Service Type:</span> {{ ucfirst($report->serviceRequest->service_type) }}
            </div>
            <div class="info-item">
                <span class="info-label">Depot:</span> {{ $report->serviceRequest->depot }}
            </div>
            <div class="info-item">
                <span class="info-label">Product:</span> {{ $report->serviceRequest->product }}
            </div>
        </div>

        <div class="info-section">
            <h3>Inspector</h3>
            <div class="info-item">
                <span class="info-label">Name:</span> {{ $report->inspector->user->name }}
            </div>
            <div class="info-item">
                <span class="info-label">Email:</span> {{ $report->inspector->user->email }}
            </div>
            <div class="info-item">
                <span class="info-label">Phone:</span> {{ $report->inspector->phone }}
            </div>
            <div class="info-item">
                <span class="info-label">Specialization:</span> {{ $report->inspector->specialization }}
            </div>
        </div>
    </div>

    <div class="content-section">
        <h3>Report Content</h3>
        <div class="content-text">
            {!! nl2br(e($report->content)) !!}
        </div>
    </div>

    <div class="content-section">
        <h3>Findings</h3>
        <div class="content-text">
            {!! nl2br(e($report->findings)) !!}
        </div>
    </div>

    <div class="content-section">
        <h3>Recommendations</h3>
        <div class="content-text">
            {!! nl2br(e($report->recommendations)) !!}
        </div>
    </div>

    @if($report->inspection_date || $report->tank_number || $report->product_gauge)
    <div class="content-section technical-data">
        <h3>Technical Inspection Data</h3>
        <div class="technical-grid">
            <div class="technical-item">
                <span class="info-label">Inspection Date:</span><br>
                {{ $report->inspection_date ? $report->inspection_date->format('M d, Y') : 'N/A' }}
            </div>
            <div class="technical-item">
                <span class="info-label">Inspection Time:</span><br>
                {{ $report->inspection_time ? $report->inspection_time->format('H:i') : 'N/A' }}
            </div>
            <div class="technical-item">
                <span class="info-label">Tank Number:</span><br>
                {{ $report->tank_number ?? 'N/A' }}
            </div>
            <div class="technical-item">
                <span class="info-label">Product Gauge:</span><br>
                {{ $report->product_gauge ?? 'N/A' }}
            </div>
            <div class="technical-item">
                <span class="info-label">Water Gauge:</span><br>
                {{ $report->water_gauge ?? 'N/A' }}
            </div>
            <div class="technical-item">
                <span class="info-label">Temperature:</span><br>
                {{ $report->temperature ? $report->temperature . '°C' : 'N/A' }}
            </div>
            <div class="technical-item">
                <span class="info-label">Roof:</span><br>
                {{ $report->has_roof ? 'Yes' : 'No' }}
            </div>
            <div class="technical-item">
                <span class="info-label">Density (@ 20°C):</span><br>
                {{ $report->density ?? 'N/A' }}
            </div>
            <div class="technical-item">
                <span class="info-label">VCF (ASTM 60 B):</span><br>
                {{ $report->vcf ?? 'N/A' }}
            </div>
            <div class="technical-item">
                <span class="info-label">TOV:</span><br>
                {{ $report->tov ?? 'N/A' }}
            </div>
            <div class="technical-item">
                <span class="info-label">Water Volume:</span><br>
                {{ $report->water_volume ?? 'N/A' }}
            </div>
            <div class="technical-item">
                <span class="info-label">Roof Volume:</span><br>
                {{ $report->roof_volume ?? 'N/A' }}
            </div>
            <div class="technical-item">
                <span class="info-label">GOV:</span><br>
                {{ $report->gov ?? 'N/A' }}
            </div>
            <div class="technical-item">
                <span class="info-label">GSV:</span><br>
                {{ $report->gsv ?? 'N/A' }}
            </div>
            <div class="technical-item">
                <span class="info-label">MT Air:</span><br>
                {{ $report->mt_air ?? 'N/A' }}
            </div>
        </div>
    </div>
    @endif

    <div class="footer">
        <p><strong>Zircon Inspections</strong></p>
        <p>Professional Inspection Services</p>
        <p>Generated on {{ now()->format('M d, Y H:i') }}</p>
    </div>
</body>
</html> 