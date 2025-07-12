@extends('layouts.app')

@section('title', 'Edit Report')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Edit Report
                </h2>
                <a href="{{ route('inspector.reports') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Reports
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Report Details</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('inspector.reports.update', $report->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Report Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $report->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="draft" {{ old('status', $report->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="submitted" {{ old('status', $report->status) === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                        <option value="approved" {{ old('status', $report->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="content">Report Content <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="6" 
                                      placeholder="Enter the main content of your report..." required>{{ old('content', $report->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="findings">Findings <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('findings') is-invalid @enderror" 
                                      id="findings" name="findings" rows="4" 
                                      placeholder="Describe your findings from the inspection..." required>{{ old('findings', $report->findings) }}</textarea>
                            @error('findings')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="recommendations">Recommendations <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('recommendations') is-invalid @enderror" 
                                      id="recommendations" name="recommendations" rows="4" 
                                      placeholder="Provide your recommendations based on the findings..." required>{{ old('recommendations', $report->recommendations) }}</textarea>
                            @error('recommendations')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Multiple Data Sets Section -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">Inspection Data Sets</h6>
                                <button type="button" class="btn btn-success btn-sm" onclick="addDataSet()">
                                    <i class="fas fa-plus me-2"></i>Add Data Set
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="data-sets-container">
                                    @if($report->inspectionDataSets->count() > 0)
                                        @foreach($report->inspectionDataSets as $index => $dataSet)
                                            <div class="data-set-card card mb-3" data-set-id="{{ $index + 1 }}">
                                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0">Data Set #{{ $index + 1 }}</h6>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeDataSet(this)" {{ $index === 0 && $report->inspectionDataSets->count() === 1 ? 'style=display:none;' : '' }}>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="inspection_date_{{ $index + 1 }}">Inspection Date <span class="text-danger">*</span></label>
                                                                <input type="date" class="form-control" 
                                                                       id="inspection_date_{{ $index + 1 }}" name="data_sets[{{ $index + 1 }}][inspection_date]" 
                                                                       value="{{ old('data_sets.' . ($index + 1) . '.inspection_date', $dataSet->inspection_date ? $dataSet->inspection_date->format('Y-m-d') : date('Y-m-d')) }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="inspection_time_{{ $index + 1 }}">Inspection Time <span class="text-danger">*</span></label>
                                                                <input type="time" class="form-control" 
                                                                       id="inspection_time_{{ $index + 1 }}" name="data_sets[{{ $index + 1 }}][inspection_time]" 
                                                                       value="{{ old('data_sets.' . ($index + 1) . '.inspection_time', $dataSet->inspection_time ? $dataSet->inspection_time->format('H:i') : date('H:i')) }}" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="tank_number_{{ $index + 1 }}">Tank Number <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" 
                                                                       id="tank_number_{{ $index + 1 }}" name="data_sets[{{ $index + 1 }}][tank_number]" 
                                                                       value="{{ old('data_sets.' . ($index + 1) . '.tank_number', $dataSet->tank_number) }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="product_gauge_{{ $index + 1 }}">Product Gauge (m) <span class="text-danger">*</span></label>
                                                                <input type="number" step="0.01" class="form-control" 
                                                                       id="product_gauge_{{ $index + 1 }}" name="data_sets[{{ $index + 1 }}][product_gauge]" 
                                                                       value="{{ old('data_sets.' . ($index + 1) . '.product_gauge', $dataSet->product_gauge) }}" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="water_gauge_{{ $index + 1 }}">Water Gauge (m) <span class="text-danger">*</span></label>
                                                                <input type="number" step="0.01" class="form-control" 
                                                                       id="water_gauge_{{ $index + 1 }}" name="data_sets[{{ $index + 1 }}][water_gauge]" 
                                                                       value="{{ old('data_sets.' . ($index + 1) . '.water_gauge', $dataSet->water_gauge) }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="temperature_{{ $index + 1 }}">Temperature (°C) <span class="text-danger">*</span></label>
                                                                <input type="number" step="0.1" class="form-control" 
                                                                       id="temperature_{{ $index + 1 }}" name="data_sets[{{ $index + 1 }}][temperature]" 
                                                                       value="{{ old('data_sets.' . ($index + 1) . '.temperature', $dataSet->temperature) }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="density_{{ $index + 1 }}">Density (@ 20°C) <span class="text-danger">*</span></label>
                                                                <input type="number" step="0.0001" class="form-control" 
                                                                       id="density_{{ $index + 1 }}" name="data_sets[{{ $index + 1 }}][density]" 
                                                                       value="{{ old('data_sets.' . ($index + 1) . '.density', $dataSet->density) }}" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="vcf_{{ $index + 1 }}">VCF (ASTM 60 B) <span class="text-danger">*</span></label>
                                                                <input type="number" step="0.0001" class="form-control" 
                                                                       id="vcf_{{ $index + 1 }}" name="data_sets[{{ $index + 1 }}][vcf]" 
                                                                       value="{{ old('data_sets.' . ($index + 1) . '.vcf', $dataSet->vcf) }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="tov_{{ $index + 1 }}">TOV (m³) <span class="text-danger">*</span></label>
                                                                <input type="number" step="0.01" class="form-control" 
                                                                       id="tov_{{ $index + 1 }}" name="data_sets[{{ $index + 1 }}][tov]" 
                                                                       value="{{ old('data_sets.' . ($index + 1) . '.tov', $dataSet->tov) }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="water_volume_{{ $index + 1 }}">Water Volume (m³) <span class="text-danger">*</span></label>
                                                                <input type="number" step="0.01" class="form-control" 
                                                                       id="water_volume_{{ $index + 1 }}" name="data_sets[{{ $index + 1 }}][water_volume]" 
                                                                       value="{{ old('data_sets.' . ($index + 1) . '.water_volume', $dataSet->water_volume) }}" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Roof</label>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="data_sets[{{ $index + 1 }}][has_roof]" 
                                                                           id="has_roof_yes_{{ $index + 1 }}" value="1" 
                                                                           {{ old('data_sets.' . ($index + 1) . '.has_roof', $dataSet->has_roof ? '1' : '0') === '1' ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="has_roof_yes_{{ $index + 1 }}">Yes</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="data_sets[{{ $index + 1 }}][has_roof]" 
                                                                           id="has_roof_no_{{ $index + 1 }}" value="0" 
                                                                           {{ old('data_sets.' . ($index + 1) . '.has_roof', $dataSet->has_roof ? '1' : '0') === '0' ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="has_roof_no_{{ $index + 1 }}">No</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="roof_weight_{{ $index + 1 }}">Roof Weight (kg)</label>
                                                                <input type="number" step="0.01" class="form-control" 
                                                                       id="roof_weight_{{ $index + 1 }}" name="data_sets[{{ $index + 1 }}][roof_weight]" 
                                                                       value="{{ old('data_sets.' . ($index + 1) . '.roof_weight', $dataSet->roof_weight) }}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="roof_volume_{{ $index + 1 }}">Roof Volume (m³)</label>
                                                                <input type="number" step="0.01" class="form-control" 
                                                                       id="roof_volume_{{ $index + 1 }}" name="data_sets[{{ $index + 1 }}][roof_volume]" 
                                                                       value="{{ old('data_sets.' . ($index + 1) . '.roof_volume', $dataSet->roof_volume) }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="gov_{{ $index + 1 }}">GOV (m³)</label>
                                                                <input type="number" step="0.01" class="form-control" 
                                                                       id="gov_{{ $index + 1 }}" name="data_sets[{{ $index + 1 }}][gov]" 
                                                                       value="{{ old('data_sets.' . ($index + 1) . '.gov', $dataSet->gov) }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="gsv_{{ $index + 1 }}">GSV (m³)</label>
                                                                <input type="number" step="0.01" class="form-control" 
                                                                       id="gsv_{{ $index + 1 }}" name="data_sets[{{ $index + 1 }}][gsv]" 
                                                                       value="{{ old('data_sets.' . ($index + 1) . '.gsv', $dataSet->gsv) }}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="mt_air_{{ $index + 1 }}">MT Air (tonnes)</label>
                                                                <input type="number" step="0.001" class="form-control" 
                                                                       id="mt_air_{{ $index + 1 }}" name="data_sets[{{ $index + 1 }}][mt_air]" 
                                                                       value="{{ old('data_sets.' . ($index + 1) . '.mt_air', $dataSet->mt_air) }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="notes_{{ $index + 1 }}">Notes</label>
                                                                <textarea class="form-control" id="notes_{{ $index + 1 }}" name="data_sets[{{ $index + 1 }}][notes]" 
                                                                          rows="2" placeholder="Additional notes for this data set...">{{ old('data_sets.' . ($index + 1) . '.notes', $dataSet->notes) }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <!-- Initial Data Set -->
                                        <div class="data-set-card card mb-3" data-set-id="1">
                                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">Data Set #1</h6>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="removeDataSet(this)" style="display: none;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="inspection_date_1">Inspection Date <span class="text-danger">*</span></label>
                                                            <input type="date" class="form-control" 
                                                                   id="inspection_date_1" name="data_sets[1][inspection_date]" 
                                                                   value="{{ old('data_sets.1.inspection_date', date('Y-m-d')) }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="inspection_time_1">Inspection Time <span class="text-danger">*</span></label>
                                                            <input type="time" class="form-control" 
                                                                   id="inspection_time_1" name="data_sets[1][inspection_time]" 
                                                                   value="{{ old('data_sets.1.inspection_time', date('H:i')) }}" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="tank_number_1">Tank Number <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" 
                                                                   id="tank_number_1" name="data_sets[1][tank_number]" 
                                                                   value="{{ old('data_sets.1.tank_number') }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="product_gauge_1">Product Gauge (m) <span class="text-danger">*</span></label>
                                                            <input type="number" step="0.01" class="form-control" 
                                                                   id="product_gauge_1" name="data_sets[1][product_gauge]" 
                                                                   value="{{ old('data_sets.1.product_gauge') }}" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="water_gauge_1">Water Gauge (m) <span class="text-danger">*</span></label>
                                                            <input type="number" step="0.01" class="form-control" 
                                                                   id="water_gauge_1" name="data_sets[1][water_gauge]" 
                                                                   value="{{ old('data_sets.1.water_gauge') }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="temperature_1">Temperature (°C) <span class="text-danger">*</span></label>
                                                            <input type="number" step="0.1" class="form-control" 
                                                                   id="temperature_1" name="data_sets[1][temperature]" 
                                                                   value="{{ old('data_sets.1.temperature') }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="density_1">Density (@ 20°C) <span class="text-danger">*</span></label>
                                                            <input type="number" step="0.0001" class="form-control" 
                                                                   id="density_1" name="data_sets[1][density]" 
                                                                   value="{{ old('data_sets.1.density') }}" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="vcf_1">VCF (ASTM 60 B) <span class="text-danger">*</span></label>
                                                            <input type="number" step="0.0001" class="form-control" 
                                                                   id="vcf_1" name="data_sets[1][vcf]" 
                                                                   value="{{ old('data_sets.1.vcf') }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="tov_1">TOV (m³) <span class="text-danger">*</span></label>
                                                            <input type="number" step="0.01" class="form-control" 
                                                                   id="tov_1" name="data_sets[1][tov]" 
                                                                   value="{{ old('data_sets.1.tov') }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="water_volume_1">Water Volume (m³) <span class="text-danger">*</span></label>
                                                            <input type="number" step="0.01" class="form-control" 
                                                                   id="water_volume_1" name="data_sets[1][water_volume]" 
                                                                   value="{{ old('data_sets.1.water_volume') }}" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Roof</label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="data_sets[1][has_roof]" 
                                                                       id="has_roof_yes_1" value="1" 
                                                                       {{ old('data_sets.1.has_roof') === '1' ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="has_roof_yes_1">Yes</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="data_sets[1][has_roof]" 
                                                                       id="has_roof_no_1" value="0" 
                                                                       {{ old('data_sets.1.has_roof') === '0' ? 'checked' : '' }} checked>
                                                                <label class="form-check-label" for="has_roof_no_1">No</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="roof_weight_1">Roof Weight (kg)</label>
                                                            <input type="number" step="0.01" class="form-control" 
                                                                   id="roof_weight_1" name="data_sets[1][roof_weight]" 
                                                                   value="{{ old('data_sets.1.roof_weight') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="roof_volume_1">Roof Volume (m³)</label>
                                                            <input type="number" step="0.01" class="form-control" 
                                                                   id="roof_volume_1" name="data_sets[1][roof_volume]" 
                                                                   value="{{ old('data_sets.1.roof_volume') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="gov_1">GOV (m³)</label>
                                                            <input type="number" step="0.01" class="form-control" 
                                                                   id="gov_1" name="data_sets[1][gov]" 
                                                                   value="{{ old('data_sets.1.gov') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="gsv_1">GSV (m³)</label>
                                                            <input type="number" step="0.01" class="form-control" 
                                                                   id="gsv_1" name="data_sets.1.gsv]" 
                                                                   value="{{ old('data_sets.1.gsv') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="mt_air_1">MT Air (tonnes)</label>
                                                            <input type="number" step="0.001" class="form-control" 
                                                                   id="mt_air_1" name="data_sets[1][mt_air]" 
                                                                   value="{{ old('data_sets.1.mt_air') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="notes_1">Notes</label>
                                                            <textarea class="form-control" id="notes_1" name="data_sets[1][notes]" 
                                                                      rows="2" placeholder="Additional notes for this data set...">{{ old('data_sets.1.notes') }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="supporting_file">Supporting File</label>
                            <input type="file" class="form-control" id="supporting_file" name="supporting_file">
                            @if($report->supporting_file)
                                <small class="form-text text-muted">Current file: {{ $report->supporting_file }}</small>
                            @endif
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Update Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Instructions</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            You can add multiple data sets for the same service request
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-calendar text-success me-2"></i>
                            Each data set represents a different inspection time
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-calculator text-warning me-2"></i>
                            Calculations (GOV, GSV, MT Air) will be computed automatically
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-chart-line text-info me-2"></i>
                            Multiple data sets enable time series analysis
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let dataSetCounter = {{ $report->inspectionDataSets->count() > 0 ? $report->inspectionDataSets->count() : 1 }};

function addDataSet() {
    dataSetCounter++;
    const container = document.getElementById('data-sets-container');
    const newDataSet = document.createElement('div');
    newDataSet.className = 'data-set-card card mb-3';
    newDataSet.setAttribute('data-set-id', dataSetCounter);
    
    newDataSet.innerHTML = `
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Data Set #${dataSetCounter}</h6>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeDataSet(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inspection_date_${dataSetCounter}">Inspection Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" 
                               id="inspection_date_${dataSetCounter}" name="data_sets[${dataSetCounter}][inspection_date]" 
                               value="${new Date().toISOString().split('T')[0]}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inspection_time_${dataSetCounter}">Inspection Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" 
                               id="inspection_time_${dataSetCounter}" name="data_sets[${dataSetCounter}][inspection_time]" 
                               value="${new Date().toTimeString().slice(0,5)}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tank_number_${dataSetCounter}">Tank Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" 
                               id="tank_number_${dataSetCounter}" name="data_sets[${dataSetCounter}][tank_number]" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="product_gauge_${dataSetCounter}">Product Gauge (m) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" 
                               id="product_gauge_${dataSetCounter}" name="data_sets[${dataSetCounter}][product_gauge]" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="water_gauge_${dataSetCounter}">Water Gauge (m) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" 
                               id="water_gauge_${dataSetCounter}" name="data_sets[${dataSetCounter}][water_gauge]" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="temperature_${dataSetCounter}">Temperature (°C) <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" class="form-control" 
                               id="temperature_${dataSetCounter}" name="data_sets[${dataSetCounter}][temperature]" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="density_${dataSetCounter}">Density (@ 20°C) <span class="text-danger">*</span></label>
                        <input type="number" step="0.0001" class="form-control" 
                               id="density_${dataSetCounter}" name="data_sets[${dataSetCounter}][density]" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="vcf_${dataSetCounter}">VCF (ASTM 60 B) <span class="text-danger">*</span></label>
                        <input type="number" step="0.0001" class="form-control" 
                               id="vcf_${dataSetCounter}" name="data_sets[${dataSetCounter}][vcf]" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tov_${dataSetCounter}">TOV (m³) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" 
                               id="tov_${dataSetCounter}" name="data_sets[${dataSetCounter}][tov]" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="water_volume_${dataSetCounter}">Water Volume (m³) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" 
                               id="water_volume_${dataSetCounter}" name="data_sets[${dataSetCounter}][water_volume]" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Roof</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="data_sets[${dataSetCounter}][has_roof]" 
                                   id="has_roof_yes_${dataSetCounter}" value="1">
                            <label class="form-check-label" for="has_roof_yes_${dataSetCounter}">Yes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="data_sets[${dataSetCounter}][has_roof]" 
                                   id="has_roof_no_${dataSetCounter}" value="0" checked>
                            <label class="form-check-label" for="has_roof_no_${dataSetCounter}">No</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="roof_weight_${dataSetCounter}">Roof Weight (kg)</label>
                        <input type="number" step="0.01" class="form-control" 
                               id="roof_weight_${dataSetCounter}" name="data_sets[${dataSetCounter}][roof_weight]">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="roof_volume_${dataSetCounter}">Roof Volume (m³)</label>
                        <input type="number" step="0.01" class="form-control" 
                               id="roof_volume_${dataSetCounter}" name="data_sets[${dataSetCounter}][roof_volume]">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="gov_${dataSetCounter}">GOV (m³)</label>
                        <input type="number" step="0.01" class="form-control" 
                               id="gov_${dataSetCounter}" name="data_sets[${dataSetCounter}][gov]">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="gsv_${dataSetCounter}">GSV (m³)</label>
                        <input type="number" step="0.01" class="form-control" 
                               id="gsv_${dataSetCounter}" name="data_sets[${dataSetCounter}][gsv]">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="mt_air_${dataSetCounter}">MT Air (tonnes)</label>
                        <input type="number" step="0.001" class="form-control" 
                               id="mt_air_${dataSetCounter}" name="data_sets[${dataSetCounter}][mt_air]">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="notes_${dataSetCounter}">Notes</label>
                        <textarea class="form-control" id="notes_${dataSetCounter}" name="data_sets[${dataSetCounter}][notes]" 
                                  rows="2" placeholder="Additional notes for this data set..."></textarea>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(newDataSet);
    
    // Show remove button for first data set if more than one exists
    const dataSets = document.querySelectorAll('.data-set-card');
    if (dataSets.length > 1) {
        dataSets[0].querySelector('.btn-danger').style.display = 'block';
    }
    
    // Add calculation listeners for the newly added data set
    addCalculationListeners(dataSetCounter);
}

function removeDataSet(button) {
    const dataSetCard = button.closest('.data-set-card');
    dataSetCard.remove();
    
    // Hide remove button for first data set if only one remains
    const dataSets = document.querySelectorAll('.data-set-card');
    if (dataSets.length === 1) {
        dataSets[0].querySelector('.btn-danger').style.display = 'none';
    }
    
    // Renumber the data sets
    dataSets.forEach((card, index) => {
        const header = card.querySelector('.card-header h6');
        header.textContent = `Data Set #${index + 1}`;
        card.setAttribute('data-set-id', index + 1);
    });
}

// Auto-calculation functions
function calculateValues(dataSetId) {
    const roofWeight = parseFloat(document.getElementById(`roof_weight_${dataSetId}`).value) || 0;
    const density = parseFloat(document.getElementById(`density_${dataSetId}`).value) || 0;
    const vcf = parseFloat(document.getElementById(`vcf_${dataSetId}`).value) || 0;
    const tov = parseFloat(document.getElementById(`tov_${dataSetId}`).value) || 0;
    const waterVolume = parseFloat(document.getElementById(`water_volume_${dataSetId}`).value) || 0;

    // Calculate Roof Volume: Roof weight ÷ (Density × VCF)
    let roofVolume = 0;
    if (density > 0 && vcf > 0) {
        roofVolume = roofWeight / (density * vcf);
    }
    document.getElementById(`roof_volume_${dataSetId}`).value = roofVolume.toFixed(2);

    // Calculate GOV: TOV - Water Volume - Roof Volume
    const gov = tov - waterVolume - roofVolume;
    document.getElementById(`gov_${dataSetId}`).value = gov.toFixed(2);

    // Calculate GSV: GOV × VCF
    const gsv = gov * vcf;
    document.getElementById(`gsv_${dataSetId}`).value = gsv.toFixed(2);

    // Calculate MT Air: GSV × (Density - 0.0011)
    const mtAir = gsv * (density - 0.0011);
    document.getElementById(`mt_air_${dataSetId}`).value = mtAir.toFixed(3);
}

// Add event listeners to calculation fields for existing data sets
document.addEventListener('DOMContentLoaded', function() {
    const calculationFields = ['roof_weight', 'density', 'vcf', 'tov', 'water_volume'];
    
    // Add event listeners to existing data sets
    document.querySelectorAll('.data-set-card').forEach(card => {
        const dataSetId = card.getAttribute('data-set-id');
        
        calculationFields.forEach(fieldId => {
            const field = document.getElementById(`${fieldId}_${dataSetId}`);
            if (field) {
                field.addEventListener('input', () => calculateValues(dataSetId));
            }
        });
        
        // Add event listeners for roof radio buttons
        const roofRadios = card.querySelectorAll('input[name^="data_sets"][name$="[has_roof]"]');
        const roofWeightField = document.getElementById(`roof_weight_${dataSetId}`);
        
        roofRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === '1') {
                    roofWeightField.required = true;
                } else {
                    roofWeightField.required = false;
                    roofWeightField.value = '';
                }
                calculateValues(dataSetId);
            });
        });
    });
});

// Function to add event listeners to newly created data sets
function addCalculationListeners(dataSetId) {
    const calculationFields = ['roof_weight', 'density', 'vcf', 'tov', 'water_volume'];
    
    calculationFields.forEach(fieldId => {
        const field = document.getElementById(`${fieldId}_${dataSetId}`);
        if (field) {
            field.addEventListener('input', () => calculateValues(dataSetId));
        }
    });
    
    // Add event listeners for roof radio buttons
    const roofRadios = document.querySelectorAll(`input[name="data_sets[${dataSetId}][has_roof]"]`);
    const roofWeightField = document.getElementById(`roof_weight_${dataSetId}`);
    
    roofRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === '1') {
                roofWeightField.required = true;
            } else {
                roofWeightField.required = false;
                roofWeightField.value = '';
            }
            calculateValues(dataSetId);
        });
    });
}
</script>
@endsection 