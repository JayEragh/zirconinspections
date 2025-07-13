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
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            background-color: #f8f9fa;
            padding: 5px;
            border-left: 4px solid #007bff;
            margin-bottom: 10px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 30%;
            padding: 3px 0;
        }
        .info-value {
            display: table-cell;
            padding: 3px 0;
        }
        .technical-data {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px 0;
        }
        .technical-grid {
            display: table;
            width: 100%;
        }
        .technical-row {
            display: table-row;
        }
        .technical-cell {
            display: table-cell;
            padding: 3px 5px;
            border-bottom: 1px solid #eee;
        }
        .content-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px 0;
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .status-badge {
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-draft { background-color: #6c757d; color: white; }
        .status-submitted { background-color: #ffc107; color: black; }
        .status-approved { background-color: #28a745; color: white; }
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

    <div class="section">
        <div class="section-title">Report Information</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Report Title:</div>
                <div class="info-value">{{ $report->title }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">
                    <span class="status-badge status-{{ $report->status }}">
                        {{ ucfirst($report->status) }}
                    </span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Created:</div>
                <div class="info-value">{{ $report->created_at->format('M d, Y H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Inspector:</div>
                <div class="info-value">{{ $report->inspector->name }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Service Request Details</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Request #:</div>
                <div class="info-value">{{ $report->serviceRequest->id }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Service ID:</div>
                <div class="info-value">{{ $report->serviceRequest->service_id }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Service Type:</div>
                <div class="info-value">{{ ucfirst($report->serviceRequest->service_type) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Depot:</div>
                <div class="info-value">{{ $report->serviceRequest->depot }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Product:</div>
                <div class="info-value">{{ $report->serviceRequest->product }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Report Content</div>
        <div class="content-box">
            <strong>Content:</strong><br>
            {{ $report->content }}
        </div>
    </div>

    <div class="section">
        <div class="section-title">Findings</div>
        <div class="content-box">
            {{ $report->findings }}
        </div>
    </div>

    <div class="section">
        <div class="section-title">Recommendations</div>
        <div class="content-box">
            {{ $report->recommendations }}
        </div>
    </div>

    @if($report->inspectionDataSets->count() > 0)
    <div class="section">
        <div class="section-title">Inspection Data Tabulation</div>
        <div class="technical-data">
            <table style="width: 100%; border-collapse: collapse; font-size: 8px;">
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
    </div>

    <div class="section">
        <div class="section-title">GSV Time Series Analysis</div>
        <div class="content-box" style="text-align: center;">
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

    @if($report->supporting_file)
    <div class="section">
        <div class="section-title">Supporting Documentation</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">File:</div>
                <div class="info-value">{{ $report->supporting_file }}</div>
            </div>
        </div>
    </div>
    @endif

    <div class="footer">
        <p>Generated on {{ now()->format('M d, Y H:i:s') }}</p>
        <p>Zircon Inspections - Professional Tank Inspection Services</p>
        <p>This report is confidential and intended for authorized personnel only.</p>
    </div>
</body>
</html> 