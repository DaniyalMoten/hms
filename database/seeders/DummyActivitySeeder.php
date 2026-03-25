<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\PatientCase;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DummyActivitySeeder extends Seeder
{
    public function run(): void
    {
        $this->createAppointments();
        $this->createPatientCases();
        $this->createInvoices();
    }

    private function createAppointments(): void
    {
        // doctor_department_id: 1=Cardiologists, 4=Dermatologists, 6=Nephrologists
        $appointments = [
            // Dr. Ahmed Khan (id=1, Cardiologist, dept=1)
            ['patient_id' => 1,  'doctor_id' => 1, 'department_id' => 1, 'opd_date' => Carbon::now()->subDays(20), 'problem' => 'Chest pain aur saans lene mein takleef. Blood pressure high hai.', 'is_completed' => 1],
            ['patient_id' => 3,  'doctor_id' => 1, 'department_id' => 1, 'opd_date' => Carbon::now()->subDays(15), 'problem' => 'Dil ki dhadkan irregular hai aur chakkar ata hai.', 'is_completed' => 1],
            ['patient_id' => 5,  'doctor_id' => 1, 'department_id' => 1, 'opd_date' => Carbon::now()->subDays(10), 'problem' => 'Hypertension control nahi ho raha. Dawai review karni hai.', 'is_completed' => 1],
            ['patient_id' => 7,  'doctor_id' => 1, 'department_id' => 1, 'opd_date' => Carbon::now()->subDays(5),  'problem' => 'Seene mein dard aur paseena aana – ECG karwana hai.', 'is_completed' => 0],
            ['patient_id' => 9,  'doctor_id' => 1, 'department_id' => 1, 'opd_date' => Carbon::now()->addDays(2),  'problem' => 'Follow up – angioplasty ke baad check-up.', 'is_completed' => 0],

            // Dr. Sara Ali (id=2, Dermatologist, dept=4)
            ['patient_id' => 2,  'doctor_id' => 2, 'department_id' => 4, 'opd_date' => Carbon::now()->subDays(18), 'problem' => 'Chehra par daane aur kharish. Allergy suspected.', 'is_completed' => 1],
            ['patient_id' => 4,  'doctor_id' => 2, 'department_id' => 4, 'opd_date' => Carbon::now()->subDays(12), 'problem' => 'Eczema ki wajah se haath par laal dhabbay.', 'is_completed' => 1],
            ['patient_id' => 6,  'doctor_id' => 2, 'department_id' => 4, 'opd_date' => Carbon::now()->subDays(7),  'problem' => 'Baalon ka girna aur scalp mein kharish.', 'is_completed' => 1],
            ['patient_id' => 8,  'doctor_id' => 2, 'department_id' => 4, 'opd_date' => Carbon::now()->subDays(2),  'problem' => 'Sunburn aur skin peeling after prolonged sun exposure.', 'is_completed' => 0],
            ['patient_id' => 10, 'doctor_id' => 2, 'department_id' => 4, 'opd_date' => Carbon::now()->addDays(3),  'problem' => 'Psoriasis treatment ke liye consultation.', 'is_completed' => 0],

            // Dr. Usman Malik (id=3, Nephrologist, dept=6)
            ['patient_id' => 1,  'doctor_id' => 3, 'department_id' => 6, 'opd_date' => Carbon::now()->subDays(22), 'problem' => 'Peshab mein jalan aur kidney stone ka doubt.', 'is_completed' => 1],
            ['patient_id' => 3,  'doctor_id' => 3, 'department_id' => 6, 'opd_date' => Carbon::now()->subDays(14), 'problem' => 'Creatinine level high hai – kidney function test.', 'is_completed' => 1],
            ['patient_id' => 5,  'doctor_id' => 3, 'department_id' => 6, 'opd_date' => Carbon::now()->subDays(8),  'problem' => 'CKD stage 2 – diet plan aur medication review.', 'is_completed' => 1],
            ['patient_id' => 7,  'doctor_id' => 3, 'department_id' => 6, 'opd_date' => Carbon::now()->addDays(1),  'problem' => 'Dialysis schedule discussion aur test reports review.', 'is_completed' => 0],
            ['patient_id' => 9,  'doctor_id' => 3, 'department_id' => 6, 'opd_date' => Carbon::now()->addDays(4),  'problem' => 'Urinary tract infection – urine culture report dekhna hai.', 'is_completed' => 0],
        ];

        foreach ($appointments as $appt) {
            Appointment::create(array_merge($appt, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]));
        }

        $this->command->info('✅ 15 Appointments created!');
    }

    private function createPatientCases(): void
    {
        $cases = [
            [
                'case_id'     => 'CASE-2024-001',
                'patient_id'  => 1,
                'doctor_id'   => 1,
                'phone'       => '+923001111111',
                'date'        => Carbon::now()->subDays(30),
                'fee'         => 2500,
                'status'      => 1,
                'description' => 'Hypertension aur coronary artery disease ka case. Patient ko regular monitoring ki zaroorat hai.',
            ],
            [
                'case_id'     => 'CASE-2024-002',
                'patient_id'  => 2,
                'doctor_id'   => 2,
                'phone'       => '+923002222222',
                'date'        => Carbon::now()->subDays(25),
                'fee'         => 1500,
                'status'      => 1,
                'description' => 'Chronic eczema aur contact dermatitis. Topical steroids se response acha hai.',
            ],
            [
                'case_id'     => 'CASE-2024-003',
                'patient_id'  => 3,
                'doctor_id'   => 3,
                'phone'       => '+923003333333',
                'date'        => Carbon::now()->subDays(20),
                'fee'         => 3000,
                'status'      => 1,
                'description' => 'Kidney stones (bilateral) – lithotripsy scheduled. Patient needs follow up in 2 weeks.',
            ],
            [
                'case_id'     => 'CASE-2024-004',
                'patient_id'  => 4,
                'doctor_id'   => 2,
                'phone'       => '+923004444444',
                'date'        => Carbon::now()->subDays(18),
                'fee'         => 1200,
                'status'      => 1,
                'description' => 'Severe acne vulgaris. Isotretinoin course shuru kiya gaya hai.',
            ],
            [
                'case_id'     => 'CASE-2024-005',
                'patient_id'  => 5,
                'doctor_id'   => 1,
                'phone'       => '+923005555555',
                'date'        => Carbon::now()->subDays(15),
                'fee'         => 2000,
                'status'      => 0,
                'description' => 'Irregular heartbeat (atrial fibrillation). Warfarin therapy shuru ki gayi hai – INR monitoring.',
            ],
            [
                'case_id'     => 'CASE-2024-006',
                'patient_id'  => 6,
                'doctor_id'   => 2,
                'phone'       => '+923006666666',
                'date'        => Carbon::now()->subDays(12),
                'fee'         => 1800,
                'status'      => 1,
                'description' => 'Alopecia areata – PRP therapy recommended. Patient monthly review pe hai.',
            ],
            [
                'case_id'     => 'CASE-2024-007',
                'patient_id'  => 7,
                'doctor_id'   => 3,
                'phone'       => '+923007777777',
                'date'        => Carbon::now()->subDays(10),
                'fee'         => 3500,
                'status'      => 0,
                'description' => 'Chronic kidney disease stage 3. Hemodialysis 3 times per week. Nephrology follow-up.',
            ],
            [
                'case_id'     => 'CASE-2024-008',
                'patient_id'  => 8,
                'doctor_id'   => 2,
                'phone'       => '+923008888888',
                'date'        => Carbon::now()->subDays(7),
                'fee'         => 1000,
                'status'      => 1,
                'description' => 'Fungal infection on skin – anti-fungal cream prescribed. Review in 2 weeks.',
            ],
            [
                'case_id'     => 'CASE-2024-009',
                'patient_id'  => 9,
                'doctor_id'   => 1,
                'phone'       => '+923009999999',
                'date'        => Carbon::now()->subDays(5),
                'fee'         => 2200,
                'status'      => 0,
                'description' => 'Post angioplasty follow-up. Stent placed in LAD artery. Dual antiplatelet therapy.',
            ],
            [
                'case_id'     => 'CASE-2024-010',
                'patient_id'  => 10,
                'doctor_id'   => 3,
                'phone'       => '+923010000000',
                'date'        => Carbon::now()->subDays(3),
                'fee'         => 2800,
                'status'      => 0,
                'description' => 'Recurrent UTI aur proteinuria. 24-hour urine collection test ordered.',
            ],
        ];

        foreach ($cases as $case) {
            PatientCase::create(array_merge($case, [
                'currency_symbol' => 'Rs',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]));
        }

        $this->command->info('✅ 10 Patient Cases created!');
    }

    private function createInvoices(): void
    {
        $invoices = [
            ['invoice_id' => 'INV-2024-001', 'patient_id' => 1,  'invoice_date' => Carbon::now()->subDays(28)->toDateString(), 'amount' => 2500, 'discount' => 0,   'status' => 1],
            ['invoice_id' => 'INV-2024-002', 'patient_id' => 2,  'invoice_date' => Carbon::now()->subDays(24)->toDateString(), 'amount' => 1500, 'discount' => 100, 'status' => 1],
            ['invoice_id' => 'INV-2024-003', 'patient_id' => 3,  'invoice_date' => Carbon::now()->subDays(19)->toDateString(), 'amount' => 3000, 'discount' => 0,   'status' => 1],
            ['invoice_id' => 'INV-2024-004', 'patient_id' => 4,  'invoice_date' => Carbon::now()->subDays(17)->toDateString(), 'amount' => 1200, 'discount' => 200, 'status' => 1],
            ['invoice_id' => 'INV-2024-005', 'patient_id' => 5,  'invoice_date' => Carbon::now()->subDays(14)->toDateString(), 'amount' => 2000, 'discount' => 0,   'status' => 0],
            ['invoice_id' => 'INV-2024-006', 'patient_id' => 6,  'invoice_date' => Carbon::now()->subDays(11)->toDateString(), 'amount' => 1800, 'discount' => 0,   'status' => 1],
            ['invoice_id' => 'INV-2024-007', 'patient_id' => 7,  'invoice_date' => Carbon::now()->subDays(9)->toDateString(),  'amount' => 3500, 'discount' => 500, 'status' => 0],
            ['invoice_id' => 'INV-2024-008', 'patient_id' => 8,  'invoice_date' => Carbon::now()->subDays(6)->toDateString(),  'amount' => 1000, 'discount' => 0,   'status' => 1],
            ['invoice_id' => 'INV-2024-009', 'patient_id' => 9,  'invoice_date' => Carbon::now()->subDays(4)->toDateString(),  'amount' => 2200, 'discount' => 0,   'status' => 0],
            ['invoice_id' => 'INV-2024-010', 'patient_id' => 10, 'invoice_date' => Carbon::now()->subDays(2)->toDateString(),  'amount' => 2800, 'discount' => 300, 'status' => 0],
        ];

        foreach ($invoices as $inv) {
            Invoice::create(array_merge($inv, [
                'currency_symbol' => 'Rs',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]));
        }

        $this->command->info('✅ 10 Invoices created!');
    }
}
