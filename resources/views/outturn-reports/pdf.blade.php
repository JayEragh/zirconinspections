<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Outturn Report - {{ $outturnReport->report_title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.2;
            margin: 0;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .logo {
            max-width: 150px;
            max-height: 60px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin: 5px 0;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
            margin: 5px 0;
        }
        .info-section {
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            width: 120px;
        }
        .info-value {
            flex: 1;
        }
        .summary-section {
            margin-bottom: 15px;
        }
        .summary-title {
            font-size: 12px;
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 5px;
            margin-bottom: 10px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 10px;
            margin-bottom: 15px;
        }
        .summary-item {
            text-align: center;
            padding: 5px;
            border: 1px solid #ddd;
        }
        .summary-label {
            font-size: 8px;
            font-weight: bold;
            color: #666;
        }
        .summary-value {
            font-size: 10px;
            font-weight: bold;
        }
        .positive { color: #28a745; }
        .negative { color: #dc3545; }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 8px;
        }
        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 3px;
            text-align: center;
        }
        .data-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .tank-header {
            background-color: #e9ecef;
            font-weight: bold;
            text-align: left;
            padding: 5px;
            margin-bottom: 10px;
        }
        .initial-section {
            background-color: #e3f2fd;
            padding: 5px;
            margin-bottom: 5px;
        }
        .final-section {
            background-color: #e8f5e8;
            padding: 5px;
            margin-bottom: 5px;
        }
        .difference-section {
            background-color: #fff3cd;
            padding: 5px;
            margin-bottom: 10px;
        }
        .page-break {
            page-break-before: always;
        }
        .footer {
            position: fixed;
            bottom: 10px;
            left: 10px;
            right: 10px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Zircon Inspections" class="logo">
        <div class="title">OUTTURN REPORT</div>
        <div class="subtitle">{{ $outturnReport->report_title }}</div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">BDC Name:</span>
            <span class="info-value">{{ $outturnReport->bdc_name }}</span>
            <span class="info-label">Report Date:</span>
            <span class="info-value">{{ $outturnReport->report_date->format('F d, Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Service Request:</span>
            <span class="info-value">#{{ $outturnReport->serviceRequest->id }}</span>
            <span class="info-label">Client:</span>
            <span class="info-value">{{ $outturnReport->serviceRequest->client->user->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Inspector:</span>
            <span class="info-value">{{ $outturnReport->inspector->user->name }}</span>
            <span class="info-label">Service Type:</span>
            <span class="info-value">{{ ucfirst($outturnReport->serviceRequest->service_type) }}</span>
        </div>
    </div>

    @php
        $tanks = $outturnReport->outturnDataSets->groupBy('tank_number');
    @endphp

    @foreach($tanks as $tankNumber => $dataSets)
        @php
            $initialData = $dataSets->where('data_type', 'initial')->first();
            $finalData = $dataSets->where('data_type', 'final')->first();
        @endphp

        <div class="tank-header">TANK: {{ $tankNumber }}</div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Parameter</th>
                    <th>Initial</th>
                    <th>Final</th>
                    <th>Difference</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Date</strong></td>
                    <td>{{ $initialData->inspection_date->format('M d, Y') }}</td>
                    <td>{{ $finalData->inspection_date->format('M d, Y') }}</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td><strong>Time</strong></td>
                    <td>{{ $initialData->inspection_time->format('H:i') }}</td>
                    <td>{{ $finalData->inspection_time->format('H:i') }}</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td><strong>Product Gauge</strong></td>
                    <td>{{ $initialData->formatted_product_gauge }}</td>
                    <td>{{ $finalData->formatted_product_gauge }}</td>
                    <td>{{ number_format($finalData->product_gauge - $initialData->product_gauge, 3) }}</td>
                </tr>
                <tr>
                    <td><strong>Water Gauge</strong></td>
                    <td>{{ $initialData->formatted_water_gauge }}</td>
                    <td>{{ $finalData->formatted_water_gauge }}</td>
                    <td>{{ number_format($finalData->water_gauge - $initialData->water_gauge, 3) }}</td>
                </tr>
                <tr>
                    <td><strong>Temperature (°C)</strong></td>
                    <td>{{ $initialData->formatted_temperature }}</td>
                    <td>{{ $finalData->formatted_temperature }}</td>
                    <td>{{ number_format($finalData->temperature - $initialData->temperature, 1) }}</td>
                </tr>
                <tr>
                    <td><strong>Density</strong></td>
                    <td>{{ $initialData->formatted_density }}</td>
                    <td>{{ $finalData->formatted_density }}</td>
                    <td>{{ number_format($finalData->density - $initialData->density, 4) }}</td>
                </tr>
                <tr>
                    <td><strong>VCF</strong></td>
                    <td>{{ $initialData->formatted_vcf }}</td>
                    <td>{{ $finalData->formatted_vcf }}</td>
                    <td>{{ number_format($finalData->vcf - $initialData->vcf, 4) }}</td>
                </tr>
                <tr>
                    <td><strong>TOV</strong></td>
                    <td>{{ $initialData->formatted_tov }}</td>
                    <td>{{ $finalData->formatted_tov }}</td>
                    <td>{{ number_format($finalData->tov - $initialData->tov, 3) }}</td>
                </tr>
                <tr>
                    <td><strong>Water Volume</strong></td>
                    <td>{{ $initialData->formatted_water_volume }}</td>
                    <td>{{ $finalData->formatted_water_volume }}</td>
                    <td>{{ number_format($finalData->water_volume - $initialData->water_volume, 3) }}</td>
                </tr>
                @if($initialData->has_roof || $finalData->has_roof)
                <tr>
                    <td><strong>Roof Weight</strong></td>
                    <td>{{ $initialData->formatted_roof_weight }}</td>
                    <td>{{ $finalData->formatted_roof_weight }}</td>
                    <td>{{ $initialData->roof_weight && $finalData->roof_weight ? number_format($finalData->roof_weight - $initialData->roof_weight, 3) : '-' }}</td>
                </tr>
                <tr>
                    <td><strong>Roof Volume</strong></td>
                    <td>{{ $initialData->formatted_roof_volume }}</td>
                    <td>{{ $finalData->formatted_roof_volume }}</td>
                    <td>{{ $initialData->roof_volume && $finalData->roof_volume ? number_format($finalData->roof_volume - $initialData->roof_volume, 3) : '-' }}</td>
                </tr>
                @endif
                <tr>
                    <td><strong>GOV</strong></td>
                    <td>{{ $initialData->formatted_gov }}</td>
                    <td>{{ $finalData->formatted_gov }}</td>
                    <td class="{{ ($finalData->gov - $initialData->gov) >= 0 ? 'positive' : 'negative' }}">
                        {{ number_format($finalData->gov - $initialData->gov, 3) }}
                    </td>
                </tr>
                <tr>
                    <td><strong>GSV</strong></td>
                    <td>{{ $initialData->formatted_gsv }}</td>
                    <td>{{ $finalData->formatted_gsv }}</td>
                    <td class="{{ ($finalData->gsv - $initialData->gsv) >= 0 ? 'positive' : 'negative' }}">
                        {{ number_format($finalData->gsv - $initialData->gsv, 3) }}
                    </td>
                </tr>
                <tr>
                    <td><strong>MT Air</strong></td>
                    <td>{{ $initialData->formatted_mt_air }}</td>
                    <td>{{ $finalData->formatted_mt_air }}</td>
                    <td class="{{ ($finalData->mt_air - $initialData->mt_air) >= 0 ? 'positive' : 'negative' }}">
                        {{ number_format($finalData->mt_air - $initialData->mt_air, 3) }}
                    </td>
                </tr>
                <tr>
                    <td><strong>MT Vac</strong></td>
                    <td>{{ $initialData->formatted_mt_vac }}</td>
                    <td>{{ $finalData->formatted_mt_vac }}</td>
                    <td class="{{ ($finalData->mt_vac - $initialData->mt_vac) >= 0 ? 'positive' : 'negative' }}">
                        {{ number_format($finalData->mt_vac - $initialData->mt_vac, 3) }}
                    </td>
                </tr>
            </tbody>
        </table>

        @if($initialData->notes || $finalData->notes)
        <div style="margin-bottom: 15px;">
            @if($initialData->notes)
            <div style="margin-bottom: 5px;">
                <strong>Initial Notes:</strong> {{ $initialData->notes }}
            </div>
            @endif
            @if($finalData->notes)
            <div>
                <strong>Final Notes:</strong> {{ $finalData->notes }}
            </div>
            @endif
        </div>
        @endif

        @if(!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach

    <div class="summary-section">
        <div class="summary-title">SUMMARY TOTALS</div>
        <table style="width: 100%; border-collapse: collapse; font-size: 10px; margin-bottom: 10px;">
            <thead>
                <tr style="background: #f0f0f0;">
                    <th style="border: 1px solid #ddd; padding: 4px;">Metric</th>
                    <th style="border: 1px solid #ddd; padding: 4px;">Initial</th>
                    <th style="border: 1px solid #ddd; padding: 4px;">Final</th>
                    <th style="border: 1px solid #ddd; padding: 4px;">Difference</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 4px;"><strong>GOV</strong></td>
                    <td style="border: 1px solid #ddd; padding: 4px;">{{ $outturnReport->formatted_total_gov_initial }}</td>
                    <td style="border: 1px solid #ddd; padding: 4px;">{{ $outturnReport->formatted_total_gov_final }}</td>
                    <td style="border: 1px solid #ddd; padding: 4px; color: {{ $outturnReport->total_gov_difference >= 0 ? '#28a745' : '#dc3545' }};">
                        {{ $outturnReport->formatted_total_gov_difference }}
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 4px;"><strong>GSV</strong></td>
                    <td style="border: 1px solid #ddd; padding: 4px;">{{ $outturnReport->formatted_total_gsv_initial }}</td>
                    <td style="border: 1px solid #ddd; padding: 4px;">{{ $outturnReport->formatted_total_gsv_final }}</td>
                    <td style="border: 1px solid #ddd; padding: 4px; color: {{ $outturnReport->total_gsv_difference >= 0 ? '#28a745' : '#dc3545' }};">
                        {{ $outturnReport->formatted_total_gsv_difference }}
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 4px;"><strong>MT Air</strong></td>
                    <td style="border: 1px solid #ddd; padding: 4px;">{{ $outturnReport->formatted_total_mt_air_initial }}</td>
                    <td style="border: 1px solid #ddd; padding: 4px;">{{ $outturnReport->formatted_total_mt_air_final }}</td>
                    <td style="border: 1px solid #ddd; padding: 4px; color: {{ $outturnReport->total_mt_air_difference >= 0 ? '#28a745' : '#dc3545' }};">
                        {{ $outturnReport->formatted_total_mt_air_difference }}
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 4px;"><strong>MT Vac</strong></td>
                    <td style="border: 1px solid #ddd; padding: 4px;">{{ $outturnReport->formatted_total_mt_vac_initial }}</td>
                    <td style="border: 1px solid #ddd; padding: 4px;">{{ $outturnReport->formatted_total_mt_vac_final }}</td>
                    <td style="border: 1px solid #ddd; padding: 4px; color: {{ $outturnReport->total_mt_vac_difference >= 0 ? '#28a745' : '#dc3545' }};">
                        {{ $outturnReport->formatted_total_mt_vac_difference }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Generated by Zircon Inspections on {{ now()->format('F d, Y \a\t H:i') }}</p>
        <p>This report contains confidential information and should be handled accordingly.</p>
    </div>
</body>
</html> 