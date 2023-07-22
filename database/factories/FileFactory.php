<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->word();
        $extension = fake()->fileExtension();

        return [
            'name' => $name.'.'.$extension,
            'file_name' => $name,
            'mime_type' => $extension,
            'path' => $name,
            'disk' => config('app.uploads.disk'),
            'file_hash' => md5(
                storage_path(
                    path: $name,
                ),
            ),
            'collection' => '',
            'size' => rand(1, 1000),
        ];
    }
}
