@props(['title', 'stats', 'recentItems', 'viewAllRoute' => null])

<div class="mobile-dashboard">
    <div class="row g-3 mb-4">
        @foreach($stats as $stat)
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <div class="stat-icon mb-2">
                        <i class="{{ $stat['icon'] }} fa-2x text-primary"></i>
                    </div>
                    <h3 class="stat-number mb-1">{{ $stat['value'] }}</h3>
                    <p class="stat-label mb-0 text-muted small">{{ $stat['label'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($recentItems && count($recentItems) > 0)
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $title }}</h5>
            @if($viewAllRoute)
            <a href="{{ $viewAllRoute }}" class="btn btn-sm btn-outline-primary">View All</a>
            @endif
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @foreach($recentItems as $item)
                <div class="list-group-item border-0 py-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $item['title'] }}</h6>
                            <p class="text-muted small mb-1">{{ $item['description'] }}</p>
                            <small class="text-muted">{{ $item['date'] }}</small>
                        </div>
                        @if(isset($item['status']))
                        <span class="badge bg-{{ $item['status_color'] }}">{{ $item['status'] }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.mobile-dashboard .card {
    transition: transform 0.2s ease;
}

.mobile-dashboard .card:hover {
    transform: translateY(-2px);
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--primary-color);
}

.stat-label {
    font-size: 0.75rem;
    font-weight: 500;
}

.stat-icon {
    color: var(--primary-color);
    opacity: 0.8;
}

@media (max-width: 768px) {
    .mobile-dashboard .col-6 {
        margin-bottom: 1rem;
    }
    
    .stat-number {
        font-size: 1.25rem;
    }
    
    .stat-icon i {
        font-size: 1.5rem !important;
    }
    
    .list-group-item {
        padding: 0.75rem 1rem;
    }
}

@media (max-width: 576px) {
    .mobile-dashboard .col-6 {
        margin-bottom: 0.75rem;
    }
    
    .stat-number {
        font-size: 1.125rem;
    }
    
    .stat-icon i {
        font-size: 1.25rem !important;
    }
    
    .list-group-item {
        padding: 0.5rem 0.75rem;
    }
}
</style> 