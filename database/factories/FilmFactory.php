<?php

namespace Database\Factories;

use App\Models\Film;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Film>
 */
class FilmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title" => "filme dyal afsi",
            "description" => "the worst film ",
            "genre" => "deprition",
            "actors" => "me => the victim",
            "duration_minutes" => 2000,
            "minimum_age" => 30,
            "trailer_url" => "https://youtu.be/90UWkHE0Nkc?si=dNIJTt3KAThhZDYc"
        ];
    }
}
