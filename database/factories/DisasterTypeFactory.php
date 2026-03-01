<?php

namespace Database\Factories;

use App\Models\DisasterType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DisasterType>
 */
class DisasterTypeFactory extends Factory
{
    protected $model = DisasterType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $disasters = ['Banjir', 'Gempa Bumi', 'Gunung Meletus', 'Tanah Longsor', 'Angin Kencang', 'Tsunami'];
        $disasterName = $this->faker->randomElement($disasters);

        return [
            'name' => $disasterName,
            'slug' => str($disasterName)->slug(),
        ];
    }
}
