<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition()
    {
        $code = 'EMP-'.$this->faker->unique()->numberBetween(1000, 9999);

        return [
            'employee_id' => $code,
            'employee_code' => $code,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'department' => 'IT',
            'position' => 'Developer',
            'employment_type' => 'full_time',
            'hire_date' => now(),
            'salary' => 5000,
            'status' => 'active',
        ];
    }
}
