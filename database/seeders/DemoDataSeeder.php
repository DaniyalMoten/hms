<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorDepartment;
use App\Models\Patient;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->createDoctors();
        $this->createPatients();
    }

    private function createDoctors(): void
    {
        $doctorDept = Department::whereName('Doctor')->first();

        $doctors = [
            [
                'user' => [
                    'first_name'        => 'Ahmed',
                    'last_name'         => 'Khan',
                    'email'             => 'ahmed.khan@hms.com',
                    'password'          => Hash::make('123456'),
                    'designation'       => 'Senior Cardiologist',
                    'gender'            => 0,
                    'qualification'     => 'MBBS, MD (Cardiology)',
                    'status'            => 1,
                    'phone'             => '+923001234567',
                    'dob'               => '1980-05-15',
                    'blood_group'       => 'O+',
                    'department_id'     => $doctorDept->id,
                    'email_verified_at' => Carbon::now(),
                ],
                'doctor_department_id' => 1,
                'specialist'           => 'Heart',
            ],
            [
                'user' => [
                    'first_name'        => 'Sara',
                    'last_name'         => 'Ali',
                    'email'             => 'sara.ali@hms.com',
                    'password'          => Hash::make('123456'),
                    'designation'       => 'Dermatologist',
                    'gender'            => 1,
                    'qualification'     => 'MBBS, FCPS (Dermatology)',
                    'status'            => 1,
                    'phone'             => '+923111234567',
                    'dob'               => '1985-09-20',
                    'blood_group'       => 'A+',
                    'department_id'     => $doctorDept->id,
                    'email_verified_at' => Carbon::now(),
                ],
                'doctor_department_id' => 4,
                'specialist'           => 'Skin',
            ],
            [
                'user' => [
                    'first_name'        => 'Usman',
                    'last_name'         => 'Malik',
                    'email'             => 'usman.malik@hms.com',
                    'password'          => Hash::make('123456'),
                    'designation'       => 'Nephrologist',
                    'gender'            => 0,
                    'qualification'     => 'MBBS, MD (Nephrology)',
                    'status'            => 1,
                    'phone'             => '+923211234567',
                    'dob'               => '1978-03-10',
                    'blood_group'       => 'B+',
                    'department_id'     => $doctorDept->id,
                    'email_verified_at' => Carbon::now(),
                ],
                'doctor_department_id' => 6,
                'specialist'           => 'Kidney',
            ],
        ];

        foreach ($doctors as $data) {
            $user = User::create($data['user']);
            $user->assignRole($doctorDept);

            $doctor = Doctor::create([
                'user_id'              => $user->id,
                'doctor_department_id' => $data['doctor_department_id'],
                'specialist'           => $data['specialist'],
            ]);

            $schedule = Schedule::create([
                'doctor_id'        => $doctor->id,
                'per_patient_time' => '01:00:00',
            ]);

            $user->update([
                'owner_id'   => $doctor->id,
                'owner_type' => Doctor::class,
            ]);
        }

        $this->command->info('✅ 3 Doctors created successfully!');
    }

    private function createPatients(): void
    {
        $patientDept = Department::whereName('Patient')->first();

        $patients = [
            ['first_name' => 'Bilal',   'last_name' => 'Ahmed',     'email' => 'bilal.ahmed@gmail.com',    'gender' => 0, 'dob' => '1990-03-15', 'blood_group' => 'A+', 'phone' => '+923001111111'],
            ['first_name' => 'Fatima',  'last_name' => 'Sheikh',    'email' => 'fatima.sheikh@gmail.com',  'gender' => 1, 'dob' => '1995-07-22', 'blood_group' => 'B+', 'phone' => '+923002222222'],
            ['first_name' => 'Hassan',  'last_name' => 'Raza',      'email' => 'hassan.raza@gmail.com',    'gender' => 0, 'dob' => '1988-11-05', 'blood_group' => 'O+', 'phone' => '+923003333333'],
            ['first_name' => 'Ayesha',  'last_name' => 'Nawaz',     'email' => 'ayesha.nawaz@gmail.com',   'gender' => 1, 'dob' => '1992-04-18', 'blood_group' => 'AB+','phone' => '+923004444444'],
            ['first_name' => 'Zain',    'last_name' => 'Ul Abideen','email' => 'zain.abideen@gmail.com',   'gender' => 0, 'dob' => '1985-12-30', 'blood_group' => 'O-', 'phone' => '+923005555555'],
            ['first_name' => 'Sana',    'last_name' => 'Mirza',     'email' => 'sana.mirza@gmail.com',     'gender' => 1, 'dob' => '1998-06-10', 'blood_group' => 'A-', 'phone' => '+923006666666'],
            ['first_name' => 'Tariq',   'last_name' => 'Mehmood',   'email' => 'tariq.mehmood@gmail.com',  'gender' => 0, 'dob' => '1975-08-25', 'blood_group' => 'B-', 'phone' => '+923007777777'],
            ['first_name' => 'Maryam',  'last_name' => 'Iqbal',     'email' => 'maryam.iqbal@gmail.com',   'gender' => 1, 'dob' => '2000-01-14', 'blood_group' => 'AB-','phone' => '+923008888888'],
            ['first_name' => 'Kamran',  'last_name' => 'Hussain',   'email' => 'kamran.hussain@gmail.com', 'gender' => 0, 'dob' => '1982-09-09', 'blood_group' => 'O+', 'phone' => '+923009999999'],
            ['first_name' => 'Nida',    'last_name' => 'Farooq',    'email' => 'nida.farooq@gmail.com',    'gender' => 1, 'dob' => '1993-02-28', 'blood_group' => 'A+', 'phone' => '+923010000000'],
        ];

        foreach ($patients as $data) {
            $userData = array_merge($data, [
                'password'          => Hash::make('123456'),
                'status'            => 1,
                'email_verified_at' => Carbon::now(),
                'department_id'     => $patientDept ? $patientDept->id : null,
            ]);

            $user = User::create($userData);

            if ($patientDept) {
                $user->assignRole($patientDept);
            }

            $patient = Patient::create(['user_id' => $user->id]);

            $user->update([
                'owner_id'   => $patient->id,
                'owner_type' => Patient::class,
            ]);
        }

        $this->command->info('✅ 10 Patients created successfully!');
    }
}
