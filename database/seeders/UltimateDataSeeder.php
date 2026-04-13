<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Bed;
use App\Models\Package;
use App\Models\Insurance;
use App\Models\OpdPatientDepartment;
use App\Models\PatientAdmission;
use App\Models\Prescription;
use App\Models\NoticeBoard;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\PatientCase;
use App\Models\EmployeePayroll;
use App\Models\InvestigationReport;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UltimateDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting Ultimate Detailed Seeder...');

        $patients = Patient::all();
        $doctors = Doctor::all();
        
        if ($patients->isEmpty() || $doctors->isEmpty()) {
            $this->command->error('No patients or doctors found. Please ensure basic data is seeded first.');
            return;
        }

        $beds = Bed::where('is_available', 1)->get();
        if ($beds->isEmpty()) {
            $beds = Bed::all();
        }

        $packages = Package::all();
        $insurances = Insurance::all();

        // 1. Notice Board Data
        NoticeBoard::insert([
            ['title' => 'Monthly System Update', 'description' => 'The HMS system will be upgraded this weekend.', 'created_at' => Carbon::now()->subDays(1), 'updated_at' => Carbon::now()->subDays(1)],
            ['title' => 'New Staff Orientations', 'description' => 'Welcoming new members to the OPD unit next Monday.', 'created_at' => Carbon::now()->subDays(4), 'updated_at' => Carbon::now()->subDays(4)],
            ['title' => 'Emergency Protocol Reminder', 'description' => 'A reminder to follow strict protocols during triage.', 'created_at' => Carbon::now()->subDays(8), 'updated_at' => Carbon::now()->subDays(8)],
        ]);
        $this->command->info('✅ Notice Boards Additions Completed');

        // 2. Doctor Payrolls
        foreach ($doctors as $doctor) {
            EmployeePayroll::create([
                'sr_no' => rand(100, 999),
                'payroll_id' => mb_strtoupper(Str::random(8)),
                'type' => 2, // 2 for Doctor usually
                'owner_id' => $doctor->id,
                'owner_type' => Doctor::class,
                'month' => Carbon::now()->subMonth()->format('F'),
                'year' => Carbon::now()->subMonth()->year,
                'net_salary' => 85000,
                'status' => 1,
                'basic_salary' => 80000,
                'allowance' => 10000,
                'deductions' => 5000,
                'currency_symbol' => 'Rs',
            ]);
            
            EmployeePayroll::create([
                'sr_no' => rand(1000, 1999),
                'payroll_id' => mb_strtoupper(Str::random(8)),
                'type' => 2, 
                'owner_id' => $doctor->id,
                'owner_type' => Doctor::class,
                'month' => Carbon::now()->format('F'),
                'year' => Carbon::now()->year,
                'net_salary' => 85000,
                'status' => 0, // Unpaid
                'basic_salary' => 80000,
                'allowance' => 10000,
                'deductions' => 5000,
                'currency_symbol' => 'Rs',
            ]);
        }
        $this->command->info('✅ Doctor Payrolls Generated');

        // 3. Exhaustive Data for EVERY Patient
        foreach ($patients as $index => $patient) {
            // Pick a random doctor for this patient's primary care
            $doctor = $doctors->random();
            $secondDoctor = $doctors->random();

            // A. Patient Case
            $case = PatientCase::create([
                'case_id' => mb_strtoupper(PatientCase::generateUniqueCaseId()),
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'phone' => '+9230000000' . rand(10, 99),
                'date' => Carbon::now()->subDays(rand(5, 30)),
                'fee' => rand(1000, 3000),
                'status' => rand(0, 1),
                'description' => 'General checkup for ongoing symptoms.',
                'currency_symbol' => 'Rs',
            ]);

            // B. Appointment
            Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'department_id' => $doctor->doctor_department_id,
                'opd_date' => Carbon::now()->subDays(rand(1, 10)),
                'problem' => 'Patient complained of fever and fatigue.',
                'is_completed' => 1,
            ]);

            // C. OPD (Out Patient Department)
            OpdPatientDepartment::create([
                'patient_id' => $patient->id,
                'opd_number' => OpdPatientDepartment::generateUniqueOpdNumber(),
                'height' => rand(5,6).'.'.rand(1,9),
                'weight' => rand(60, 90),
                'bp' => '120/'.rand(70, 85),
                'symptoms' => 'Regular OPD visit for checkup.',
                'notes' => 'Vitals are stable. Advised rest.',
                'appointment_date' => Carbon::now()->subDays(rand(1, 5)),
                'case_id' => $case->id,
                'is_old_patient' => ($index % 2 == 0) ? 1 : 0,
                'doctor_id' => $doctor->id,
                'standard_charge' => 1500,
                'payment_mode' => 1,
                'currency_symbol' => 'Rs',
            ]);

            // D. IPD (Patient Admission)
            $bed = $beds->random();
            PatientAdmission::create([
                'patient_admission_id' => mb_strtoupper(PatientAdmission::generateUniquePatientId()),
                'patient_id' => $patient->id,
                'doctor_id' => $secondDoctor->id,
                'admission_date' => Carbon::now()->subDays(rand(2, 6)),
                'discharge_date' => (rand(0, 1) == 1) ? Carbon::now()->subDays(rand(0, 1)) : null,
                'package_id' => $packages->isNotEmpty() ? $packages->random()->id : null,
                'insurance_id' => $insurances->isNotEmpty() ? $insurances->random()->id : null,
                'bed_id' => $bed->id,
                'status' => 1,
            ]);

            // E. Prescription
            Prescription::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'medical_history' => 'Nothing abnormal.',
                'current_medication' => 'Multivitamins.',
                'health_insurance' => 'Yes - Private',
                'problem_description' => 'Viral infection suspected.',
                'test' => 'CBC, LFTs',
                'advice' => 'Complete bed rest for 3 days.',
                'next_visit_qty' => 1,
                'next_visit_time' => 'Weeks',
                'status' => 1,
            ]);

            // F. Investigation Report
            InvestigationReport::create([
                'patient_id' => $patient->id,
                'date' => Carbon::now()->subDays(rand(1, 4)),
                'title' => 'Blood Profile Analysis',
                'description' => 'The blood profile suggests a minor infection. White blood cell count is slightly elevated.',
                'doctor_id' => $doctor->id,
                'status' => 1,
            ]);

            // G. Invoice
            Invoice::create([
                'invoice_id' => 'INV-' . strtoupper(Str::random(6)),
                'patient_id' => $patient->id,
                'invoice_date' => Carbon::now()->subDays(rand(1, 10))->toDateString(),
                'amount' => rand(2000, 15000),
                'discount' => rand(0, 500),
                'status' => rand(0, 1),
                'currency_symbol' => 'Rs',
            ]);
        }
        $this->command->info('✅ Comprehensive Data created for ALL Patients!');
        $this->command->info('Seeding finished successfully!');
    }
}
