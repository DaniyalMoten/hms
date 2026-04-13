<?php

use App\Models\Prescription;
use App\Repositories\PrescriptionRepository;

$p = Prescription::with('patient.patientUser', 'doctor.doctorUser', 'getMedicine.medicines')->first();
$data = app(PrescriptionRepository::class)->getSettingList();
$medicines = app(PrescriptionRepository::class)->getMedicineData($p->id);

try {
    echo view('prescriptions.view_fields', ['prescription' => $p, 'medicines' => $medicines, 'data' => $data])->render();
    echo "\nRENDERED OK!\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "FILE: " . $e->getFile() . "\n";
    echo "LINE: " . $e->getLine() . "\n";
}
