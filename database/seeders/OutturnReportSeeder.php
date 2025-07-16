<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OutturnReport;
use App\Models\OutturnDataSet;
use App\Models\ServiceRequest;
use App\Models\Inspector;
use App\Models\Client;

class OutturnReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing service requests, inspectors, and clients
        $serviceRequests = ServiceRequest::with(['inspector', 'client'])->take(3)->get();
        
        if ($serviceRequests->isEmpty()) {
            $this->command->info('No service requests found. Please run the main seeder first.');
            return;
        }

        foreach ($serviceRequests as $serviceRequest) {
            // Create outturn report
            $outturnReport = OutturnReport::create([
                'report_title' => 'Outturn Report - ' . $serviceRequest->depot . ' - ' . date('Y-m-d'),
                'service_request_id' => $serviceRequest->id,
                'inspector_id' => $serviceRequest->inspector_id,
                'client_id' => $serviceRequest->client_id,
                'bdc_name' => $serviceRequest->depot,
                'report_date' => now()->subDays(rand(1, 30)),
            ]);

            // Get tank numbers from service request
            $tankNumbers = array_map('trim', explode(',', $serviceRequest->tank_numbers));
            
            foreach ($tankNumbers as $tankNumber) {
                // Create initial data set
                $initialGov = rand(1000, 5000) / 1000;
                $initialGsv = $initialGov * 0.9850; // Sample VCF
                $initialMtAir = $initialGsv * 0.7500; // Sample density
                $initialMtVac = $initialGsv * 0.7489; // Sample density - 0.0011
                
                OutturnDataSet::create([
                    'outturn_report_id' => $outturnReport->id,
                    'tank_number' => $tankNumber,
                    'data_type' => 'initial',
                    'inspection_date' => now()->subDays(rand(1, 30)),
                    'inspection_time' => now()->subHours(rand(1, 12)),
                    'product_gauge' => rand(800, 1200) / 1000,
                    'water_gauge' => rand(50, 200) / 1000,
                    'temperature' => rand(250, 350) / 10,
                    'has_roof' => rand(0, 1),
                    'roof_weight' => rand(0, 1) ? rand(100, 500) / 1000 : null,
                    'density' => rand(7400, 7600) / 10000,
                    'vcf' => rand(9800, 9900) / 10000,
                    'tov' => rand(1200, 1500) / 1000,
                    'water_volume' => rand(100, 300) / 1000,
                    'roof_volume' => rand(0, 1) ? rand(50, 150) / 1000 : null,
                    'gov' => $initialGov,
                    'gsv' => $initialGsv,
                    'mt_air' => $initialMtAir,
                    'mt_vac' => $initialMtVac,
                    'notes' => 'Initial measurement before transfer',
                ]);

                // Create final data set
                $finalGov = $initialGov - rand(100, 500) / 1000; // Transfer out
                $finalGsv = $finalGov * 0.9850; // Sample VCF
                $finalMtAir = $finalGsv * 0.7500; // Sample density
                $finalMtVac = $finalGsv * 0.7489; // Sample density - 0.0011
                
                OutturnDataSet::create([
                    'outturn_report_id' => $outturnReport->id,
                    'tank_number' => $tankNumber,
                    'data_type' => 'final',
                    'inspection_date' => now()->subDays(rand(0, 29)),
                    'inspection_time' => now()->subHours(rand(1, 12)),
                    'product_gauge' => rand(600, 1000) / 1000,
                    'water_gauge' => rand(50, 200) / 1000,
                    'temperature' => rand(250, 350) / 10,
                    'has_roof' => rand(0, 1),
                    'roof_weight' => rand(0, 1) ? rand(100, 500) / 1000 : null,
                    'density' => rand(7400, 7600) / 10000,
                    'vcf' => rand(9800, 9900) / 10000,
                    'tov' => rand(900, 1200) / 1000,
                    'water_volume' => rand(100, 300) / 1000,
                    'roof_volume' => rand(0, 1) ? rand(50, 150) / 1000 : null,
                    'gov' => $finalGov,
                    'gsv' => $finalGsv,
                    'mt_air' => $finalMtAir,
                    'mt_vac' => $finalMtVac,
                    'notes' => 'Final measurement after transfer',
                ]);
            }

            // Calculate totals
            $outturnReport->calculateTotals();
        }

        $this->command->info('Sample outturn reports created successfully!');
    }
}
