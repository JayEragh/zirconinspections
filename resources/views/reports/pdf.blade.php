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
            padding: 10px;
            color: #333;
            font-size: 10px;
            line-height: 1.3;
        }
        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .logo {
            width: 60px;
            height: auto;
            margin-right: 15px;
        }
        .header-text {
            flex: 1;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 3px;
        }
        .report-title {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        .report-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            gap: 10px;
        }
        .info-section {
            flex: 1;
        }
        .info-section h3 {
            color: #007bff;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
            margin-bottom: 5px;
            font-size: 11px;
        }
        .info-item {
            margin-bottom: 3px;
            font-size: 9px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .content-section {
            margin-bottom: 12px;
        }
        .content-section h3 {
            color: #007bff;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
            margin-bottom: 8px;
            font-size: 11px;
        }
        .content-text {
            line-height: 1.4;
            text-align: justify;
            font-size: 9px;
            margin-bottom: 8px;
        }
        .technical-data {
            margin-top: 10px;
        }
        .technical-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 8px;
            margin-top: 8px;
        }
        .technical-item {
            background: #f8f9fa;
            padding: 5px;
            border-radius: 3px;
            font-size: 8px;
        }
        .footer {
            margin-top: 15px;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 8px;
            color: #666;
            font-size: 8px;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 8px;
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
        .compact-row {
            display: flex;
            gap: 15px;
            margin-bottom: 8px;
        }
        .compact-section {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Zircon Inspections Logo" class="logo">
        <div class="header-text">
            <div class="company-name">Zircon Inspections</div>
            <div class="report-title">Inspection Report</div>
            <div>Report #{{ $report->id }}</div>
        </div>
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

    <div class="compact-row">
        <div class="compact-section">
            <div class="content-section">
                <h3>Report Content</h3>
                <div class="content-text">
                    {!! nl2br(e($report->content)) !!}
                </div>
            </div>
        </div>

        <div class="compact-section">
            <div class="content-section">
                <h3>Findings</h3>
                <div class="content-text">
                    {!! nl2br(e($report->findings)) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="content-section">
        <h3>Recommendations</h3>
        <div class="content-text">
            {!! nl2br(e($report->recommendations)) !!}
        </div>
    </div>

    @if($report->inspectionDataSets->count() > 0)
    <div class="content-section">
        <h3>Inspection Data Tabulation</h3>
        <table style="width: 100%; border-collapse: collapse; font-size: 8px; margin-top: 8px;">
            <thead>
                <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="border: 1px solid #dee2e6; padding: 4px; text-align: left;">Date</th>
                    <th style="border: 1px solid #dee2e6; padding: 4px; text-align: left;">Time</th>
                    <th style="border: 1px solid #dee2e6; padding: 4px; text-align: left;">Tank #</th>
                    <th style="border: 1px solid #dee2e6; padding: 4px; text-align: left;">Product Gauge</th>
                    <th style="border: 1px solid #dee2e6; padding: 4px; text-align: left;">Water Gauge</th>
                    <th style="border: 1px solid #dee2e6; padding: 4px; text-align: left;">Temperature (°C)</th>
                    <th style="border: 1px solid #dee2e6; padding: 4px; text-align: left;">Density</th>
                    <th style="border: 1px solid #dee2e6; padding: 4px; text-align: left;">VCF</th>
                    <th style="border: 1px solid #dee2e6; padding: 4px; text-align: left;">TOV</th>
                    <th style="border: 1px solid #dee2e6; padding: 4px; text-align: left;">Water Vol</th>
                    <th style="border: 1px solid #dee2e6; padding: 4px; text-align: left;">GOV</th>
                    <th style="border: 1px solid #dee2e6; padding: 4px; text-align: left;">GSV</th>
                    <th style="border: 1px solid #dee2e6; padding: 4px; text-align: left;">MT Air</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report->inspectionDataSets as $dataSet)
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="border: 1px solid #dee2e6; padding: 4px;">{{ $dataSet->inspection_date && is_object($dataSet->inspection_date) ? $dataSet->inspection_date->format('M d, Y') : ($dataSet->inspection_date ? $dataSet->inspection_date : 'N/A') }}</td>
                    <td style="border: 1px solid #dee2e6; padding: 4px;">{{ $dataSet->inspection_time && is_object($dataSet->inspection_time) ? $dataSet->inspection_time->format('H:i') : ($dataSet->inspection_time ? $dataSet->inspection_time : 'N/A') }}</td>
                    <td style="border: 1px solid #dee2e6; padding: 4px;">{{ $dataSet->tank_number }}</td>
                    <td style="border: 1px solid #dee2e6; padding: 4px;">{{ number_format($dataSet->product_gauge, 3) }}</td>
                    <td style="border: 1px solid #dee2e6; padding: 4px;">{{ number_format($dataSet->water_gauge, 3) }}</td>
                    <td style="border: 1px solid #dee2e6; padding: 4px;">{{ number_format($dataSet->temperature, 1) }}</td>
                    <td style="border: 1px solid #dee2e6; padding: 4px;">{{ number_format($dataSet->density, 4) }}</td>
                    <td style="border: 1px solid #dee2e6; padding: 4px;">{{ number_format($dataSet->vcf, 4) }}</td>
                    <td style="border: 1px solid #dee2e6; padding: 4px;">{{ number_format($dataSet->tov, 3) }}</td>
                    <td style="border: 1px solid #dee2e6; padding: 4px;">{{ number_format($dataSet->water_volume, 3) }}</td>
                    <td style="border: 1px solid #dee2e6; padding: 4px;">{{ number_format($dataSet->gov, 3) }}</td>
                    <td style="border: 1px solid #dee2e6; padding: 4px;">{{ number_format($dataSet->gsv, 3) }}</td>
                    <td style="border: 1px solid #dee2e6; padding: 4px;">{{ number_format($dataSet->mt_air, 3) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="content-section">
        <h3>GSV Time Series Analysis</h3>
        <div style="text-align: center; margin: 15px 0; padding: 20px; border: 1px solid #dee2e6; background-color: #f8f9fa;">
            <p style="font-size: 12px; color: #666; margin-bottom: 10px;"><strong>GSV vs Time Chart</strong></p>
            <div style="font-size: 10px; color: #888;">
                <p>Chart data points:</p>
                @foreach($report->inspectionDataSets as $index => $dataSet)
                    <span style="display: inline-block; margin: 2px 5px; padding: 2px 6px; background-color: #e9ecef; border-radius: 3px;">
                        {{ $dataSet->inspection_date && is_object($dataSet->inspection_date) ? $dataSet->inspection_date->format('M d') : 'N/A' }}: {{ number_format($dataSet->gsv, 3) }} m³
                    </span>
                @endforeach
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