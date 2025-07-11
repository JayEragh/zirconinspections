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
        <div class="section-title">Client Information</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Name:</div>
                <div class="info-value">{{ $report->client->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $report->client->email }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Phone:</div>
                <div class="info-value">{{ $report->client->phone }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Address:</div>
                <div class="info-value">{{ $report->client->address }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Technical Inspection Data</div>
        <div class="technical-data">
            <div class="technical-grid">
                <div class="technical-row">
                    <div class="technical-cell"><strong>Inspection Date:</strong></div>
                    <div class="technical-cell">{{ $report->inspection_date ? $report->inspection_date->format('M d, Y') : 'N/A' }}</div>
                    <div class="technical-cell"><strong>Inspection Time:</strong></div>
                    <div class="technical-cell">{{ $report->inspection_time ? $report->inspection_time->format('H:i') : 'N/A' }}</div>
                </div>
                <div class="technical-row">
                    <div class="technical-cell"><strong>Tank Number:</strong></div>
                    <div class="technical-cell">{{ $report->tank_number ?? 'N/A' }}</div>
                    <div class="technical-cell"><strong>Product Gauge:</strong></div>
                    <div class="technical-cell">{{ $report->product_gauge ?? 'N/A' }}</div>
                </div>
                <div class="technical-row">
                    <div class="technical-cell"><strong>H20 Gauge:</strong></div>
                    <div class="technical-cell">{{ $report->water_gauge ?? 'N/A' }}</div>
                    <div class="technical-cell"><strong>Temperature:</strong></div>
                    <div class="technical-cell">{{ $report->temperature ? $report->temperature . '°C' : 'N/A' }}</div>
                </div>
                <div class="technical-row">
                    <div class="technical-cell"><strong>Roof:</strong></div>
                    <div class="technical-cell">{{ $report->has_roof ? 'Yes' : 'No' }}</div>
                    <div class="technical-cell"><strong>Roof Weight:</strong></div>
                    <div class="technical-cell">{{ $report->has_roof ? ($report->roof_weight ?? 'N/A') : 'N/A' }}</div>
                </div>
                <div class="technical-row">
                    <div class="technical-cell"><strong>Density (@ 20°C):</strong></div>
                    <div class="technical-cell">{{ $report->density ?? 'N/A' }}</div>
                    <div class="technical-cell"><strong>VCF (ASTM 60 B):</strong></div>
                    <div class="technical-cell">{{ $report->vcf ?? 'N/A' }}</div>
                </div>
                <div class="technical-row">
                    <div class="technical-cell"><strong>TOV:</strong></div>
                    <div class="technical-cell">{{ $report->tov ?? 'N/A' }}</div>
                    <div class="technical-cell"><strong>Water Volume:</strong></div>
                    <div class="technical-cell">{{ $report->water_volume ?? 'N/A' }}</div>
                </div>
                <div class="technical-row">
                    <div class="technical-cell"><strong>Roof Volume:</strong></div>
                    <div class="technical-cell">{{ $report->roof_volume ?? 'N/A' }}</div>
                    <div class="technical-cell"><strong>GOV:</strong></div>
                    <div class="technical-cell">{{ $report->gov ?? 'N/A' }}</div>
                </div>
                <div class="technical-row">
                    <div class="technical-cell"><strong>GSV:</strong></div>
                    <div class="technical-cell">{{ $report->gsv ?? 'N/A' }}</div>
                    <div class="technical-cell"><strong>MT Air:</strong></div>
                    <div class="technical-cell">{{ $report->mt_air ?? 'N/A' }}</div>
                </div>
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