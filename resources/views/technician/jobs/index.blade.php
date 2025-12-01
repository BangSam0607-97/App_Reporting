@extends('layouts.main')

@section('title','Jobs - Technician')
@section('page-title','Jobs')
@section('breadcrumb','Technician / Jobs')

@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Jobs List</span>
            <div>
                <a href="{{ route('technician.jobs.create') }}" class="btn btn-sm btn-success">Create Job</a>
            </div>
        </div>
        <div class="card-body">
            <table id="datatableJobs" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Location</th>
                        <th>Assigned</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jobs as $job)
                    <tr>
                        <td>{{ $job['id'] }}</td>
                        <td>{{ $job['title'] }}</td>
                        <td>{{ $job['location'] }}</td>
                        <td>{{ $job['assigned_to'] }}</td>
                        <td>{{ $job['priority'] }}</td>
                        <td>{{ ucfirst($job['status']) }}</td>
                        <td>
                            <a href="{{ route('technician.jobs.show', $job['id']) }}" class="btn btn-sm btn-primary">Details</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const el = document.getElementById('datatableJobs');
        if (el) { new simpleDatatables.DataTable(el); }
    });
</script>
@endpush
