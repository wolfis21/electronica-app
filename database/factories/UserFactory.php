<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $company = \App\Models\Company::first() ?? \App\Models\Company::create([
            'name' => 'Default Company C.A.',
            'phone' => '123456',
            'email' => 'default@company.com',
            'address' => 'Default Address',
        ]);

        $employee = \App\Models\Employee::create([
            'fullname' => fake()->name(),
            'dni' => 'V-' . fake()->unique()->randomNumber(8),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'companies_id' => $company->id,
        ]);

        $role = \App\Models\Role::firstOrCreate(
            ['name' => 'Administrador'],
            ['description' => 'Administrador del sistema']
        );

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'employees_id' => $employee->id,
            'role_id' => $role->id,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
