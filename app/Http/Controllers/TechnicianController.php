<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    public function sampleJobs()
    {
        return [
            [
                'id' => 1,
                'title' => 'Perbaikan AC - Gedung A',
                'location' => 'Gedung A - Lantai 3',
                'assigned_to' => 'Budi',
                'status' => 'pending',
                'priority' => 'High',
                'created_at' => '2025-11-30 08:20',
                'description' => 'AC tidak dingin, bagian evaporator terlihat berkarat dan bocor.',
                'report' => []
            ],
            [
                'id' => 2,
                'title' => 'Instalasi CCTV - Lokasi Parkir',
                'location' => 'Parkir Utama',
                'assigned_to' => 'Siti',
                'status' => 'in_progress',
                'priority' => 'Medium',
                'created_at' => '2025-11-29 10:10',
                'description' => 'Menambah 4 titik kamera pada parkir utama untuk meningkatkan coverage.',
                'report' => []
            ],
            [
                'id' => 3,
                'title' => 'Perbaikan Internet Router - Kantor',
                'location' => 'Kantor - Lantai 2',
                'assigned_to' => 'Rahmat',
                'status' => 'done',
                'priority' => 'Low',
                'created_at' => '2025-11-25 12:00',
                'description' => 'Router restart terus, butuh update firmware.',
                'report' => [
                    'action' => 'Update firmware, replace PSU',
                    'notes' => 'Selesai, koneksi stabil',
                    'completed_at' => '2025-11-25 15:40',
                    'images' => []
                ]
            ],
        ];
    }

    public function dashboard()
    {
        $jobs = $this->sampleJobs();
        return view('technician.dashboard', compact('jobs'));
    }

    public function index()
    {
        $jobs = $this->sampleJobs();
        return view('technician.jobs.index', compact('jobs'));
    }

    public function show($id)
    {
        $jobs = $this->sampleJobs();
        foreach ($jobs as $job) {
            if ($job['id'] == $id) {
                return view('technician.jobs.show', ['job' => $job]);
            }
        }
        abort(404);
    }

    public function create()
    {
        // show a simple form to create report (demo only)
        return view('technician.jobs.create');
    }

    public function store(Request $request)
    {
        // Demo: Don't persist; simulate saving and redirect
        // In real app: validate and persist to database
        return redirect()->route('technician.jobs.index')->with('success', 'Laporan pekerjaan berhasil dibuat (demo).');
    }
}
