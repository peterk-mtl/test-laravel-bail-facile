<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFormatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'e_signable' => $this->faker->boolean(),
            'postable'=> $this->faker->boolean(),
            'emailable'=> true,
        ];
    }
}
