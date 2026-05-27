{{-- resources/views/orchid/components/dashboard-stats.blade.php --}}
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-primary">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h6 class="text-muted">Total Tasks</h6>
                        <h3>{{ $stats['total'] }}</h3>
                    </div>
                    <div class="col-4 text-right">
                        <i class="icon-list fs-1 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-warning">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h6 class="text-muted">Pending</h6>
                        <h3>{{ $stats['pending'] }}</h3>
                    </div>
                    <div class="col-4 text-right">
                        <i class="icon-clock fs-1 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-info">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h6 class="text-muted">In Progress</h6>
                        <h3>{{ $stats['in_progress'] }}</h3>
                    </div>
                    <div class="col-4 text-right">
                        <i class="icon-refresh fs-1 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-success">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h6 class="text-muted">Completed</h6>
                        <h3>{{ $stats['completed'] }}</h3>
                    </div>
                    <div class="col-4 text-right">
                        <i class="icon-check fs-1 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>