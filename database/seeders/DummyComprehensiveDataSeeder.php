<?php

namespace Database\Seeders;

use App\Models\NoticeBoard;
use App\Models\OpdPatientDepartment;
use App\Models\Prescription;
use App\Models\PatientAdmission;
use App\Models\PatientCase;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Bed;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DummyComprehensiveDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedNoticeBoards();
        $this->seedOpd();
        $this->seedPrescriptions();
        $this->seedMoreAdmissions();
    }

    private function seedNoticeBoards(): void
    {
        // Only run if empty
        if (NoticeBoard::count() > 0) return;

        $notices = [
            ['title' => 'Important: Annual Staff Meeting', 'description' => 'All doctors and nurses must attend the quarterly meeting in Conference Room A on Friday.', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['title' => 'COVID-19 SOPs Update', 'description' => 'Please strictly follow the new protocol for patient screening at the main entrance. Masks are mandatory.', 'created_at' => Carbon::now()->subDays(2), 'updated_at' => Carbon::now()->subDays(2)],
            ['title' => 'HMS System Maintenance', 'description' => 'The software will be down for maintenance on Saturday from 2 AM to 4 AM. Please plan accordingly.', 'created_at' => Carbon::now()->subDays(5), 'updated_at' => Carbon::now()->subDays(5)],
            ['title' => 'New Cardiology Equipment Arrival', 'description' => 'We have received new ECG machines. Training will be held next Wednesday for cardiology staff.', 'created_at' => Carbon::now()->subDays(10), 'updated_at' => Carbon::now()->subDays(10)],
            ['title' => 'Blood Donation Drive', 'description' => 'A blood donation drive is being organized next week. Staff members are encouraged to participate.', 'created_at' => Carbon::now()->subDays(15), 'updated_at' => Carbon::now()->subDays(15)],
        ];
        
        NoticeBoard::insert($notices);
        $this->command->info('✅ Notice Boards created!');
    }

    private function seedOpd(): void
    {
        if (OpdPatientDepartment::count() > 0) return;

        $doctors = Doctor::take(3)->get();
        $patients = Patient::take(6)->get();
        if ($doctors->isEmpty() || $patients->isEmpty()) return;

        foreach ($patients as $index => $patient) {
            $doctor = $doctors[$index % count($doctors)];
            OpdPatientDepartment::create([
                'patient_id' => $patient->id,
                'opd_number' => OpdPatientDepartment::generateUniqueOpdNumber(),
                'height' => (string)rand(5, 6) . '.' . rand(0, 9),
                'weight' => (string)rand(50, 90),
                'bp' => '120/' . rand(70, 90),
                'symptoms' => 'Fever, cough, and headache for past ' . rand(2, 5) . ' days.',
                'notes' => 'Advised to take complete rest and start prescribed medication.',
                'appointment_date' => Carbon::now()->subDays(rand(1, 10)),
                'case_id' => null, 
                'is_old_patient' => 0,
                'doctor_id' => $doctor->id,
                'standard_charge' => 1500,
                'payment_mode' => 1,
                'currency_symbol' => 'Rs',
            ]);
        }
        $this->command->info('✅ OPD Departments created!');
    }

    private function seedPrescriptions(): void
    {
        if (Prescription::count() > 0) return;

        $doctors = Doctor::take(3)->get();
        $patients = Patient::take(6)->get();
        if ($doctors->isEmpty() || $patients->isEmpty()) return;

        foreach ($patients as $index => $patient) {
            $doctor = $doctors[$index % count($doctors)];
            Prescription::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'medical_history' => 'No prior major diseases or surgeries recorded.',
                'current_medication' => 'Panadol or paracetamol as needed.',
                'health_insurance' => 'State Life / Jubilee Insurance',
                'problem_description' => 'Patient complained of upper respiratory tract infection symptoms.',
                'test' => 'CBC, Chest X-ray if symptoms persist.',
                'advice' => 'Drink plenty of water and rest. Follow up after 3 days.',
                'next_visit_qty' => 3,
                'next_visit_time' => 'Days',
                'status' => 1,
            ]);
        }
        $this->command->info('✅ Prescriptions created!');
    }

    private function seedMoreAdmissions(): void
    {
        $doctors = Doctor::take(3)->get();
        // Skip first 2 patients which might already have admissions from PatientAdmissionTableSeeder
        $patients = Patient::skip(2)->take(5)->get(); 
        $bed = Bed::where('is_available', 1)->first(); // Just get an available bed or the first one
        if (!$bed) $bed = Bed::first();
        
        if ($doctors->isEmpty() || $patients->isEmpty() || !$bed) return;

        foreach ($patients as $index => $patient) {
            $doctor = $doctors[$index % count($doctors)];
            PatientAdmission::create([
                'patient_admission_id' => mb_strtoupper(PatientAdmission::generateUniquePatientId()),
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'admission_date' => Carbon::now()->subDays(rand(1, 7)),
                'discharge_date' => (rand(0,1) == 1) ? Carbon::now()->subDays(rand(0, 1)) : null, // some are still admitted
                'package_id' => 1,
                'insurance_id' => 1,
                'bed_id' => $bed->id, 
                'status' => 1,
            ]);
        }
        $this->command->info('✅ More Patient Admissions (IPD) created!');
    }
}
