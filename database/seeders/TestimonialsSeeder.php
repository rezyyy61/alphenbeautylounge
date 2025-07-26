<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestimonialsSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Sophie de Vries', 'Emma Jansen', 'Lotte van Dijk', 'Eva Bakker', 'Julia Visser',
            'Tess Willems', 'Anna Smeets', 'Noa Meijer', 'Zoë Dekker', 'Isabella van Leeuwen',
            'Fleur Hendriks', 'Lynn Vos', 'Milou Peters', 'Nina de Groot', 'Yara Brouwer',
            'Mila de Jong', 'Esmee Jacobs', 'Lisa Kuipers', 'Nova van Dam', 'Sara Mulder'
        ];

        $services = [
            'Gezichtsbehandeling', 'Massage', 'Wenkbrauw shaping', 'Make-up', 'Manicure & Pedicure',
            'Lichaamsscrub', 'Hydrafacial', 'Waxen', 'Eyelash lifting', 'Make-up workshop'
        ];

        $texts = [
            'Fantastische ervaring! Mijn huid voelt zacht en fris.',
            'Heerlijke ontspanning en professionele aanpak.',
            'Zeer tevreden over het resultaat, kom zeker terug!',
            'Topservice, alles tot in de puntjes verzorgd!',
            'Voelde me volledig op mijn gemak. Dank je wel!',
            'Heel vriendelijk personeel en prachtige salon.',
            'Mijn favoriete plek voor verzorging!',
            'Geweldig resultaat, precies zoals ik wilde.',
            'Perfecte behandeling. Echt een verwenmoment.',
            'Professioneel, schoon en sfeervol. Een aanrader!',
        ];

        for ($i = 0; $i < 20; $i++) {
            DB::table('testimonials')->insert([
                'name' => $names[$i],
                'service' => $services[array_rand($services)],
                'text' => $texts[array_rand($texts)],
                'rating' => rand(4, 5),
                'avatar' => "https://i.pravatar.cc/150?img=" . rand(10, 60),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
