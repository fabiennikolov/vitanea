<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Fruit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Fruit>
 */
class FruitFactory extends Factory
{
    protected $model = Fruit::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->randomNumber(4),
            'description' => fake()->sentence(),
            'is_signature' => false,
            'position' => 0,
        ];
    }

    public function signature(): static
    {
        return $this->state(fn (): array => ['is_signature' => true]);
    }
}
