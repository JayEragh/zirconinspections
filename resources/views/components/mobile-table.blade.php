@props(['headers', 'rows', 'actions' => null, 'mobileStack' => true])

<div class="mobile-table-wrapper">
    @if($mobileStack)
    <!-- Mobile Stacked View -->
    <div class="d-md-none">
        @foreach($rows as $row)
        <div class="card mb-3 border-0 shadow-sm">
            <div class="card-body">
                @foreach($headers as $key => $header)
                <div class="row mb-2">
                    <div class="col-4">
                        <strong class="text-muted small">{{ $header }}:</strong>
                    </div>
                    <div class="col-8">
                        <span class="text-dark">{{ $row[$key] ?? 'N/A' }}</span>
                    </div>
                </div>
                @endforeach
                
                @if($actions)
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="btn-group w-100" role="group">
                            @foreach($actions as $action)
                            <a href="{{ $action['url'] }}" 
                               class="btn btn-sm btn-{{ $action['type'] ?? 'outline-primary' }}">
                                <i class="{{ $action['icon'] ?? '' }}"></i>
                                <span class="d-none d-sm-inline">{{ $action['text'] }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Desktop Table View -->
    <div class="d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        @foreach($headers as $header)
                        <th>{{ $header }}</th>
                        @endforeach
                        @if($actions)
                        <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)
                    <tr>
                        @foreach($headers as $key => $header)
                        <td>{{ $row[$key] ?? 'N/A' }}</td>
                        @endforeach
                        @if($actions)
                        <td>
                            <div class="btn-group" role="group">
                                @foreach($actions as $action)
                                <a href="{{ $action['url'] }}" 
                                   class="btn btn-sm btn-{{ $action['type'] ?? 'outline-primary' }}"
                                   title="{{ $action['text'] }}">
                                    <i class="{{ $action['icon'] ?? '' }}"></i>
                                    <span class="d-none d-lg-inline ms-1">{{ $action['text'] }}</span>
                                </a>
                                @endforeach
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.mobile-table-wrapper .card {
    transition: transform 0.2s ease;
}

.mobile-table-wrapper .card:hover {
    transform: translateY(-1px);
}

.mobile-table-wrapper .btn-group .btn {
    border-radius: 0.375rem !important;
}

.mobile-table-wrapper .btn-group .btn:first-child {
    border-top-left-radius: 0.375rem !important;
    border-bottom-left-radius: 0.375rem !important;
}

.mobile-table-wrapper .btn-group .btn:last-child {
    border-top-right-radius: 0.375rem !important;
    border-bottom-right-radius: 0.375rem !important;
}

@media (max-width: 576px) {
    .mobile-table-wrapper .btn-group .btn {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    .mobile-table-wrapper .card-body {
        padding: 0.75rem;
    }
    
    .mobile-table-wrapper .row .col-4 {
        font-size: 0.75rem;
    }
    
    .mobile-table-wrapper .row .col-8 {
        font-size: 0.875rem;
    }
}
</style> 