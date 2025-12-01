@extends('layouts.main')

@section('title','Reports - Aplikasi Reporting')
@section('page-title','Reports')
@section('breadcrumb','Reports')

@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i>
                Reports Listing
            </div>
            <div>
                <a href="#" class="btn btn-sm btn-primary">Create Report</a>
            </div>
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-bordered table-hover">
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
                        <td>
                            <a href="{{ route('reports.show', $report['id']) }}" class="btn btn-sm btn-info">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
