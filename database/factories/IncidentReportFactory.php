<?php

namespace Database\Factories;

use App\Models\IncidentReport;
use App\Models\DisasterType;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IncidentReport>
 */
class IncidentReportFactory extends Factory
{
    protected $model = IncidentReport::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'report_no' => 'RPT-' . $this->faker->unique()->numerify('########'),
            'reported_at' => $this->faker->dateTime(),
            'occurred_at' => $this->faker->dateTime(),
            'disaster_type_id' => DisasterType::factory(),
            'region_id' => Region::factory(),
            'location_text' => $this->faker->address(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'reporter_name' => $this->faker->name(),
            'reporter_phone' => $this->faker->phoneNumber(),
            'status' => $this->faker->randomElement([
                IncidentReport::STATUS_SUBMITTED,
                IncidentReport::STATUS_UNDER_REVIEW,
                IncidentReport::STATUS_VERIFIED,
                IncidentReport::STATUS_REJECTED,
            ]),
            'verified_at' => $this->faker->optional()->dateTime(),
            'verified_by' => null,
            'verification_notes' => $this->faker->optional()->text(),
            'casualty_deaths' => $this->faker->numberBetween(0, 50),
            'casualty_missing' => $this->faker->numberBetween(0, 30),
            'casualty_injured' => $this->faker->numberBetween(0, 100),
            'house_heavy_damage' => $this->faker->numberBetween(0, 200),
            'house_moderate_damage' => $this->faker->numberBetween(0, 300),
            'house_light_damage' => $this->faker->numberBetween(0, 500),
            'house_flooded' => $this->faker->numberBetween(0, 500),
            'district_name' => $this->faker->city(),
            'village_name' => $this->faker->streetName(),
            'synced_to_sheets_at' => $this->faker->optional()->dateTime(),
        ];
    }

    /**
     * Indicate that the model's status is verified.
     */
    public function verified(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => IncidentReport::STATUS_VERIFIED,
            'verified_at' => now(),
        ]);
    }

    /**
     * Indicate that the model's status is submitted.
     */
    public function submitted(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => IncidentReport::STATUS_SUBMITTED,
        ]);
    }

    /**
     * Indicate that the model's status is under review.
     */
    public function underReview(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => IncidentReport::STATUS_UNDER_REVIEW,
        ]);
    }

    /**
     * Indicate that the model's status is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => IncidentReport::STATUS_REJECTED,
        ]);
    }
}
