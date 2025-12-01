@extends('layouts.main')

@section('title','Create Job Report - Technician')
@section('page-title','Create Job Report')
@section('breadcrumb','Technician / Jobs / Create')

@section('content')
    <div class="card mb-4">
        <div class="card-header">Buat Laporan Pekerjaan</div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('technician.jobs.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="job_title" class="form-label">Judul</label>
                    <input type="text" name="title" id="job_title" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label for="job_location" class="form-label">Lokasi</label>
                    <input type="text" name="location" id="job_location" class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="job_desc" class="form-label">Deskripsi</label>
                    <textarea name="description" id="job_desc" class="form-control" rows="4"></textarea>
                </div>
                <div class="mb-3">
                    <label for="job_action" class="form-label">Tindakan</label>
                    <textarea name="action" id="job_action" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('technician.jobs.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
