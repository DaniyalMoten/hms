<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$input = [
    '_token' => '...',
    'doctor_id' => 1,
    'per_patient_time' => '00:10:00',
    'available_on' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
    'available_from' => ['08:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00'],
    'available_to' => ['12:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00']
];

try {
    $repo = app(\App\Repositories\ScheduleRepository::class);
    $repo->store($input);
    echo "Stored successfully\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
