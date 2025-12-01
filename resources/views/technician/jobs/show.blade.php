@extends('layouts.main')

@section('title','Job Detail - Technician')
@section('page-title','Job Detail')
@section('breadcrumb','Technician / Jobs / Detail')

@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <strong>{{ $job['title'] }}</strong>
                <div class="small text-muted">{{ $job['location'] }}</div>
            </div>
            <div>
                <span class="badge bg-secondary">{{ ucfirst($job['status']) }}</span>
            </div>
        </div>
        <div class="card-body">
            <h6>Description</h6>
            <p>{{ $job['description'] }}</p>

            <hr />
            <h6>Report</h6>
            @if(!empty($job['report']))
                <dl class="row">
                    <dt class="col-sm-3">Action</dt>
                    <dd class="col-sm-9">{{ $job['report']['action'] ?? '-' }}</dd>
                    <dt class="col-sm-3">Notes</dt>
                    <dd class="col-sm-9">{{ $job['report']['notes'] ?? '-' }}</dd>
                </dl>
            @else
                <p class="text-muted">Belum ada laporan.
                    <a href="{{ route('technician.jobs.create') }}">Buat laporan</a>
                </p>
            @endif
        </div>
    </div>
@endsection
