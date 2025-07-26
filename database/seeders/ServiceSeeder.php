<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Knippen',
                'description' => 'Knipbehandelingen voor heren, dames en kinderen.',
                'image' => 'knippen.jpeg',
                'children' => [
                    ['title' => 'Wassen, knippen (heren)', 'price' => 24.95, 'duration' => 30],
                    ['title' => 'Alleen knippen (dames)', 'price' => 28.90, 'duration' => 30],
                    ['title' => 'Wassen, knippen, drogen', 'price' => 34.90, 'duration' => 45],
                    ['title' => 'Wassen, knippen, föhnen', 'price' => 44.50, 'duration' => 60],
                    ['title' => 'Knippen kind t/m 11 jaar', 'price' => 17.50, 'duration' => 30],
                    ['title' => 'Tondeuse', 'price' => 15.00, 'duration' => 15],
                    ['title' => 'Pony knippen', 'price' => 8.50, 'duration' => 15],
                ]
            ],
            [
                'title' => 'Epileren',
                'description' => 'Wenkbrauwen en gezicht professioneel geëpileerd.',
                'image' => 'epileren.jpg',
                'children' => [
                    ['title' => 'Wenkbrauw epileren met de draad', 'price' => 18.00, 'duration' => 15],
                    ['title' => 'Wenkbrauw epileren en verven', 'price' => 24.00, 'duration' => 30],
                    ['title' => 'Hele gezicht epileren', 'price' => 18.00, 'duration' => 15],
                    ['title' => 'Gezicht en wenkbrauwen epileren', 'price' => 30.00, 'duration' => 45],
                    ['title' => 'Bovenlip', 'price' => 6.50, 'duration' => 15],
                    ['title' => 'Wenkbrauw + bovenlip', 'price' => 21.00, 'duration' => 30],
                    ['title' => 'Wenkbrauw + bovenlip + kaak', 'price' => 25.00, 'duration' => 30],
                    ['title' => 'Bovenlip + kaak', 'price' => 13.00, 'duration' => 15],
                ]
            ],
            [
                'title' => 'Verven & Highlights',
                'description' => 'Verschillende kleur- en highlighttechnieken.',
                'image' => 'highlight.jpeg',
                'children' => [
                    ['title' => 'Uitgroei verven', 'price' => 34.90, 'duration' => 45],
                    ['title' => 'Verven (vanaf)', 'price' => 48.50, 'duration' => 60],
                    ['title' => 'Highlights (5-10 folies)', 'price' => 34.90, 'duration' => 45],
                    ['title' => 'Highlights (tot 20 folies)', 'price' => 54.90, 'duration' => 60],
                    ['title' => 'Highlights hele hoofd', 'price' => 110.00, 'duration' => 90],
                ]
            ],
            [
                'title' => 'Permanent',
                'description' => 'Permanent inclusief knippen of styling.',
                'image' => 'permanent.jpg',
                'children' => [
                    ['title' => 'Permanent (incl. knippen en drogen)', 'price' => 85.00, 'duration' => 90],
                    ['title' => 'Wassen watergolf', 'price' => 28.00, 'duration' => 45],
                    ['title' => 'Wassen, drogen', 'price' => 9.90, 'duration' => 15],
                ]
            ],
            [
                'title' => 'Oor Piercing',
                'description' => 'Oorbellen schieten – veilig en hygiënisch.',
                'image' => 'oor.jpg',

                'children' => [
                    ['title' => 'Oorbellen schieten', 'price' => 25.00, 'duration' => 30],
                ]
            ],
            [
                'title' => 'Make-up',
                'description' => 'Make-up voor speciale gelegenheden.',
                'image' => 'makeup.webp',

                'children' => [
                    ['title' => 'Make-up', 'price' => 0, 'duration' => '30'],
                ]
            ],
        ];

        foreach ($services as $parentData) {
            $children = $parentData['children'] ?? [];
            unset($parentData['children']);

            $parent = Service::create($parentData);

            foreach ($children as $child) {
                Service::create([
                    'title' => $child['title'],
                    'parent_id' => $parent->id,
                    'price' => $child['price'],
                    'duration' => $child['duration'],
                ]);
            }
        }
    }
}
