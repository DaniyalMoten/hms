<?php

use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\OpdPatientDepartment;
use App\Models\IpdPatientDepartment;
use App\Models\Prescription;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\PatientDiagnosisTest;
use App\Models\DiagnosisCategory;
use App\Models\BedAssign;
use App\Models\Bed;
use App\Models\NoticeBoard;
use App\Models\OperationReport;
use App\Models\PatientCase;
use App\Models\Department;
use Carbon\Carbon;

$patients = Patient::all();
$doctors = Doctor::all();
$beds = Bed::where('is_available', 1)->get();
$docType = DocumentType::first();
$diagCategory = DiagnosisCategory::first();
$department = Department::where('name', 'Patient')->orWhere('name', 'Doctor')->first();

if ($patients->isEmpty() || $doctors->isEmpty()) {
    die("Not enough patients or doctors.");
}

// 1. Notice Board
for ($i = 1; $i <= 5; $i++) {
    NoticeBoard::create([
        'title' => 'General Announcement ' . $i,
        'description' => 'This is a test notice board message generated dynamically for hospital staff and visitors. Please ignore.',
    ]);
}

foreach ($patients as $index => $patient) {
    // Pick a random doctor
    $doctor = $doctors->random();
    
    // Create Case
    $case = PatientCase::create([
        'case_id' => mb_strtoupper(PatientCase::generateUniqueCaseId()),
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'date' => Carbon::now()->subDays(rand(1, 60)),
        'status' => 1,
        'fee' => rand(200, 1000)
    ]);
    
    // Appointment
    Appointment::create([
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'department_id' => $doctor->doctor_department_id ?? ($department->id ?? 1),
        'opd_date' => Carbon::now()->addDays(rand(1, 10))->format('Y-m-d H:i:s'),
        'problem' => 'Routine Checkup and Consultation for patient ' . $patient->patientUser->full_name,
        'is_completed' => array_rand([0, 1])
    ]);
    
    // Prescription
    Prescription::create([
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'food_allergies' => 'None',
        'tendency_bleed' => 'None',
        'heart_disease' => 'None',
        'high_blood_pressure' => 'None',
        'diabetic' => 'No',
        'surgery' => 'None',
        'accident' => 'None',
        'others' => 'None',
        'medical_history' => 'Normal',
        'current_medication' => 'None',
        'female_pregnancy' => 'No',
        'breast_feeding' => 'No',
        'health_insurance' => 'Yes',
        'low_income' => 'No',
        'reference' => 'N/A',
        'status' => 1,
        'plus_rate' => rand(60, 100),
        'temperature' => rand(97, 100) . '.0',
        'problem_description' => 'General weakness and headache',
        'test' => 'Blood Test',
        'advice' => 'Take prescribed medicines and rest properly.',
        'next_visit_qty' => '1',
        'next_visit_time' => 'Weeks',
    ]);
    
    // Document
    if ($docType) {
        Document::create([
            'title' => 'Medical History Report for ' . $patient->patientUser->first_name,
            'document_type_id' => $docType->id,
            'patient_id' => $patient->id,
            'uploaded_by' => $doctor->user_id,
            'notes' => 'Old medical records.'
        ]);
    }
    
    // Diagnosis Test
    if ($diagCategory) {
        PatientDiagnosisTest::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'category_id' => $diagCategory->id,
            'report_number' => mb_strtoupper(PatientCase::generateUniqueCaseId()), // Random string
            'age' => rand(20, 60),
            'height' => rand(150, 180),
            'weight' => rand(50, 90),
            'average_filled' => 'Normal',
            'utilization' => 'Standard'
        ]);
    }
    
    // OPD
    OpdPatientDepartment::create([
        'patient_id' => $patient->id,
        'opd_number' => OpdPatientDepartment::generateUniqueOpdNumber(),
        'height' => rand(150, 180),
        'weight' => rand(50, 90),
        'bp' => rand(110, 130) . '/' . rand(70, 90),
        'symptoms' => 'Fever and Cough',
        'notes' => 'Requires 3 days monitoring.',
        'appointment_date' => Carbon::now()->subDays(rand(1, 30)),
        'case_id' => $case->id,
        'is_old_patient' => ($index % 2 == 0) ? 1 : 0,
        'doctor_id' => $doctor->id,
        'standard_charge' => 500,
        'payment_mode' => 1,
        'currency_symbol' => '$',
    ]);
    
    // IPD & Bed Management
    $bed = $beds->shift(); // Get a unique available bed
    if ($bed) {
        // Create IPD
        IpdPatientDepartment::create([
            'patient_id' => $patient->id,
            'ipd_number' => IpdPatientDepartment::generateUniqueIpdNumber(),
            'height' => rand(150, 180),
            'weight' => rand(50, 90),
            'bp' => rand(110, 130) . '/' . rand(70, 90),
            'symptoms' => 'Severe Pain',
            'notes' => 'Admitted strictly.',
            'admission_date' => Carbon::now()->subDays(rand(1, 30)),
            'case_id' => $case->id,
            'is_old_patient' => ($index % 2 == 0) ? 1 : 0,
            'doctor_id' => $doctor->id,
            'bed_type_id' => $bed->bed_type_id,
            'bed_id' => $bed->id,
        ]);
        
        // Formally assign the bed
        BedAssign::create([
            'bed_id' => $bed->id,
            'patient_id' => $patient->id,
            'case_id' => $case->case_id,
            'assign_date' => Carbon::now()->subDays(rand(1, 30)),
            'description' => 'Admitted to ward.',
            'status' => 1
        ]);
        
        $bed->update(['is_available' => 0]); // Mark bed as unavailable
    }
    
    // Report (Operation)
    OperationReport::create([
        'patient_id' => $patient->id,
        'case_id' => $case->case_id,
        'doctor_id' => $doctor->id,
        'date' => Carbon::now()->subDays(rand(1, 30)),
        'description' => 'Minor surgery executed successfully for patient ' . $patient->patientUser->first_name,
    ]);
}

echo "Successfully injected 100+ records touching " . $patients->count() . " patients!";
