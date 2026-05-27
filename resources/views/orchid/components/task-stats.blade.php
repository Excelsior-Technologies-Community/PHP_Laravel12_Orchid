{{-- resources/views/orchid/components/task-stats.blade.php --}}
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Tasks</h5>
                <h2>{{ \App\Models\Task::count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">Pending</h5>
                <h2>{{ \App\Models\Task::pending()->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">In Progress</h5>
                <h2>{{ \App\Models\Task::inProgress()->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h5 class="card-title">Overdue</h5>
                <h2>{{ \App\Models\Task::overdue()->count() }}</h2>
            </div>
        </div>
    </div>
</div>