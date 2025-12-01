@extends('layouts.main')

@section('title', 'Dashboard - Aplikasi Reporting')
@section('page-title', 'Dashboard')
@section('breadcrumb','Dashboard')

@section('content')
    

    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">Jobs Today</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <div class="small text-white">{{ $metrics['today_count'] ?? 0 }}</div>
                    <div class="small text-white"><i class="fas fa-calendar-day"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-dark mb-4">
                <div class="card-body">Pending</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <div class="small text-dark">{{ $metrics['status_counts']['pending'] ?? 0 }}</div>
                    <div class="small text-dark"><i class="fas fa-hourglass-start"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-secondary text-white mb-4">
                <div class="card-body">In Progress</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <div class="small text-white">{{ $metrics['status_counts']['in_progress'] ?? 0 }}</div>
                    <div class="small text-white"><i class="fas fa-spinner"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">Completed</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <div class="small text-white">{{ ($metrics['status_counts']['processed'] ?? 0) + ($metrics['status_counts']['done'] ?? 0) }}</div>
                    <div class="small text-white"><i class="fas fa-check"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-area me-1"></i>
            Reports Chart
            <select id="chartRange" class="form-select form-select-sm float-end" style="width:auto;">
                <option value="daily">Daily (30 days)</option>
                <option value="weekly">Weekly (12 weeks)</option>
                <option value="monthly">Monthly (12 months)</option>
            </select>
        </div>
        <div class="card-body">
            <canvas id="myAreaChart" width="100%" height="40"></canvas>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">KPI Teknisi</div>
                <div class="card-body">
                    <p><strong>Overall SLA ({{ $metrics['sla_hours'] }}h):</strong> {{ $metrics['overall_sla_pct'] ?? 'N/A' }}%</p>
                    <p><strong>Avg Completion Time:</strong> {{ $metrics['overall_avg_hours'] ?? 'N/A' }} hours</p>
                    <hr/>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Technician</th>
                                    <th>Total</th>
                                    <th>Completed</th>
                                    <th>SLA %</th>
                                    <th>Avg Hours</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($metrics['tech_summary'] ?? [] as $tech => $s)
                                <tr>
                                    <td>{{ $tech }}</td>
                                    <td>{{ $s['total'] }}</td>
                                    <td>{{ $s['completed'] }}</td>
                                    <td>{{ $s['sla_pct'] ?? 'N/A' }}</td>
                                    <td>{{ $s['avg_hours'] ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Recent Reports
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $report['title'] }}</td>
                        <td>{{ $report['category'] }}</td>
                        <td>{{ ucfirst($report['status']) }}</td>
                        <td>{{ $report['created_at'] }}</td>
                        <td><a href="{{ route('reports.show', $report['id']) }}" class="btn btn-sm btn-primary">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Sample Chart Data
    const areaCtx = document.getElementById('myAreaChart');
    if (areaCtx) {
        var areaChart = new Chart(areaCtx, {
            type: 'line',
            data: {
                labels: {{ json_encode($chartData['daily']['labels'] ?? []) }},
                datasets: [{
                    label: 'Total Reports',
                    data: {{ json_encode($chartData['daily']['data'] ?? []) }},
                    backgroundColor: 'rgba(2,117,216,0.2)',
                    borderColor: 'rgba(2,117,216,1)'
                }]
            }
        });
    }

    // no bar chart for now
</script>
<script>
    // Chart switcher
    document.addEventListener('DOMContentLoaded', function() {
        const selector = document.getElementById('chartRange');
        selector.addEventListener('change', function(e) {
            const range = e.target.value;
            const labels = {
                daily: {{ json_encode($chartData['daily']['labels'] ?? []) }},
                weekly: {{ json_encode($chartData['weekly']['labels'] ?? []) }},
                monthly: {{ json_encode($chartData['monthly']['labels'] ?? []) }},
            }[range];
            const data = {
                daily: {{ json_encode($chartData['daily']['data'] ?? []) }},
                weekly: {{ json_encode($chartData['weekly']['data'] ?? []) }},
                monthly: {{ json_encode($chartData['monthly']['data'] ?? []) }},
            }[range];
            if (areaChart) {
                areaChart.data.labels = labels;
                areaChart.data.datasets[0].data = data;
                areaChart.update();
            }
        });
    });
    </script>
@endpush
