@extends('layouts.main')

@section('title', 'Technician Dashboard - Aplikasi Reporting')
@section('page-title', 'Technician Dashboard')
@section('breadcrumb', 'Technician')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">Open Jobs</div>
                <div class="card-body">
                    <h3>2</h3>
                    <p>Jobs pending and in progress</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">Completed</div>
                <div class="card-body">
                    <h3>1</h3>
                    <p>Completed jobs this month</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">Assigned to You</div>
                <div class="card-body">
                    <h3>1</h3>
                    <p>Jobs assigned to your account</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Latest Jobs</span>
            <a href="{{ route('technician.jobs.index') }}" class="btn btn-sm btn-primary">View all jobs</a>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Location</th>
                        <th>Assigned</th>
                        <th>Status</th>
                        <th>Created</th>
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
                            <td>{{ ucfirst($job['status']) }}</td>
                            <td>{{ $job['created_at'] }}</td>
                            <td><a href="{{ route('technician.jobs.show', $job['id']) }}" class="btn btn-sm btn-info">Open</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
