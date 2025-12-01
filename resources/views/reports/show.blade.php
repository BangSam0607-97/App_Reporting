@extends('layouts.main')

@section('title', 'Report Detail - Aplikasi Reporting')
@section('page-title', 'Report Detail')
@section('breadcrumb','Reports / Detail')

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <strong>{{ $report['title'] }}</strong>
        </div>
        <div class="card-body">
            <p><strong>Category:</strong> {{ $report['category'] }}</p>
            <p><strong>Status:</strong> {{ ucfirst($report['status']) }}</p>
            <p><strong>Created:</strong> {{ $report['created_at'] }}</p>

            <hr>
            <div class="mb-3">
                <canvas id="reportChart" width="100%" height="40"></canvas>
            </div>

            <h5>Data</h5>
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report['data'] as $k => $v)
                        <tr>
                            <td>{{ $k }}</td>
                            <td>{{ $v }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const reportChart = document.getElementById('reportChart');
    if (reportChart) {
        new Chart(reportChart, {
            type: 'bar',
            data: {
                labels: {{ json_encode($report['chart']['labels']) }},
                datasets: [{
                    label: '{{ $report['chart']['label'] ?? 'Values' }}',
                    data: {{ json_encode($report['chart']['data']) }},
                    backgroundColor: 'rgba(54,162,235,0.7)'
                }]
            }
        });
    }
</script>
@endpush
