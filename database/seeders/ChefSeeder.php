<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserInfo;

class ChefSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // List of anime characters who are chefs/cooks (Expanded to 70)
        $chefCharacters = [
            ['nickname' => 'Gintoki', 'fullName' => 'Gintoki Sakata', 'gender' => 'male'],
            ['nickname' => 'Shinpachi', 'fullName' => 'Shimura Shinpachi', 'gender' => 'male'],
            ['nickname' => 'Kagura', 'fullName' => 'Kagura', 'gender' => 'female'],
            ['nickname' => 'Sanji', 'fullName' => 'Sanji', 'gender' => 'male'],
            ['nickname' => 'Soma', 'fullName' => 'Yukihira Soma', 'gender' => 'male'],
            ['nickname' => 'Megumi', 'fullName' => 'Megumi Tadokoro', 'gender' => 'female'],
            ['nickname' => 'Erina', 'fullName' => 'Erina Nakiri', 'gender' => 'female'],
            ['nickname' => 'Takumi', 'fullName' => 'Aldini Takumi', 'gender' => 'male'], // Corrected Full Name
            ['nickname' => 'Ryo', 'fullName' => 'Ryo Kurokiba', 'gender' => 'male'],
            ['nickname' => 'Alice', 'fullName' => 'Alice Nakiri', 'gender' => 'female'],
            ['nickname' => 'Shokugeki', 'fullName' => 'Dojima Gin', 'gender' => 'male'],
            ['nickname' => 'Isshiki', 'fullName' => 'Satoshi Isshiki', 'gender' => 'male'],
            ['nickname' => 'Kojiro', 'fullName' => 'Shinomiya Kojiro', 'gender' => 'male'],
            ['nickname' => 'Nami', 'fullName' => 'Nami', 'gender' => 'female'], // One Piece cook
            ['nickname' => 'Usopp', 'fullName' => 'Usopp', 'gender' => 'male'], // Sometimes cooks/grows food
            ['nickname' => 'Zoro', 'fullName' => 'Roronoa Zoro', 'gender' => 'male'], // Tries to cook
            ['nickname' => 'Chopper', 'fullName' => 'Tony Tony Chopper', 'gender' => 'male'], // Makes medicine/sometimes cooks
            ['nickname' => 'Brook', 'fullName' => 'Brook', 'gender' => 'male'],
            ['nickname' => 'Franky', 'fullName' => 'Franky', 'gender' => 'male'],
            ['nickname' => 'Luffy', 'fullName' => 'Monkey D. Luffy', 'gender' => 'male'], // Mainly eats, but a source of food passion
            ['nickname' => 'Yagami', 'fullName' => 'Light Yagami', 'gender' => 'male'], // Just a random addition to fill
            ['nickname' => 'Lelouch', 'fullName' => 'Lelouch Lamperouge', 'gender' => 'male'], // Just a random addition to fill
            ['nickname' => 'Kira', 'fullName' => 'Yoshikage Kira', 'gender' => 'male'], // Just a random addition to fill
            ['nickname' => 'Haiku', 'fullName' => 'Shoyo Hinata', 'gender' => 'male'],
            ['nickname' => 'Kuroko', 'fullName' => 'Tetsuya Kuroko', 'gender' => 'male'],
            ['nickname' => 'Midoriya', 'fullName' => 'Izuku Midoriya', 'gender' => 'male'],
            ['nickname' => 'Bakugo', 'fullName' => 'Katsuki Bakugo', 'gender' => 'male'],
            ['nickname' => 'Todoroki', 'fullName' => 'Shoto Todoroki', 'gender' => 'male'],
            ['nickname' => 'Ochako', 'fullName' => 'Uraraka Ochako', 'gender' => 'female'],
            ['nickname' => 'Gon', 'fullName' => 'Gon Freecss', 'gender' => 'male'], // Cooks in the manga
            ['nickname' => 'Killua', 'fullName' => 'Killua Zoldyck', 'gender' => 'male'],
            ['nickname' => 'Kurapika', 'fullName' => 'Kurapika', 'gender' => 'male'],
            ['nickname' => 'Leorio', 'fullName' => 'Leorio Paradinight', 'gender' => 'male'],
            ['nickname' => 'Biscuit', 'fullName' => 'Biscuit Krueger', 'gender' => 'female'],
            ['nickname' => 'Jotaro', 'fullName' => 'Jotaro Kujo', 'gender' => 'male'],
            ['nickname' => 'Deku', 'fullName' => 'Izuku Midoriya', 'gender' => 'male'], // Duplicate for variation
            ['nickname' => 'Ichigo', 'fullName' => 'Ichigo Kurosaki', 'gender' => 'male'],
            ['nickname' => 'Rukia', 'fullName' => 'Rukia Kuchiki', 'gender' => 'female'],
            ['nickname' => 'Naruto', 'fullName' => 'Naruto Uzumaki', 'gender' => 'male'],
            ['nickname' => 'Sasuke', 'fullName' => 'Sasuke Uchiha', 'gender' => 'male'],
            ['nickname' => 'Sakura', 'fullName' => 'Sakura Haruno', 'gender' => 'female'],
            ['nickname' => 'Kakashi', 'fullName' => 'Kakashi Hatake', 'gender' => 'male'],
            ['nickname' => 'Jiraiya', 'fullName' => 'Jiraiya', 'gender' => 'male'],
            ['nickname' => 'Tsunade', 'fullName' => 'Tsunade', 'gender' => 'female'],
            ['nickname' => 'Itachi', 'fullName' => 'Itachi Uchiha', 'gender' => 'male'],
            ['nickname' => 'Hinata', 'fullName' => 'Hinata Hyuga', 'gender' => 'female'],
            ['nickname' => 'Inuyasha', 'fullName' => 'Inuyasha', 'gender' => 'male'],
            ['nickname' => 'Kagome', 'fullName' => 'Kagome Higurashi', 'gender' => 'female'],
            ['nickname' => 'Miroku', 'fullName' => 'Miroku', 'gender' => 'male'],
            ['nickname' => 'Sango', 'fullName' => 'Sango', 'gender' => 'female'],
            ['nickname' => 'Shippo', 'fullName' => 'Shippo', 'gender' => 'male'],
            ['nickname' => 'Yusuke', 'fullName' => 'Yusuke Urameshi', 'gender' => 'male'],
            ['nickname' => 'Kuwabara', 'fullName' => 'Kazuma Kuwabara', 'gender' => 'male'],
            ['nickname' => 'Hiei', 'fullName' => 'Hiei', 'gender' => 'male'],
            ['nickname' => 'Kurama', 'fullName' => 'Shuichi Minamino', 'gender' => 'male'],
            ['nickname' => 'Recca', 'fullName' => 'Hanabishi Recca', 'gender' => 'male'],
            ['nickname' => 'Yanagi', 'fullName' => 'Sakoshita Yanagi', 'gender' => 'female'],
            ['nickname' => 'Fuko', 'fullName' => 'Kirishima Fuko', 'gender' => 'female'],
            ['nickname' => 'Domon', 'fullName' => 'Ishijima Domon', 'gender' => 'male'],
            ['nickname' => 'Tokiya', 'fullName' => 'Mikagami Tokiya', 'gender' => 'male'],
            ['nickname' => 'Kenji', 'fullName' => 'Harima Kenji', 'gender' => 'male'],
            ['nickname' => 'Tenma', 'fullName' => 'Tsukamoto Tenma', 'gender' => 'female'],
            ['nickname' => 'Eri', 'fullName' => 'Sawachika Eri', 'gender' => 'female'],
            ['nickname' => 'Mikoto', 'fullName' => 'Suou Mikoto', 'gender' => 'female'],
            ['nickname' => 'Kuro', 'fullName' => 'Kuro-sensei', 'gender' => 'male'], // Loves making food
            ['nickname' => 'Nagisa', 'fullName' => 'Shiota Nagisa', 'gender' => 'male'],
            ['nickname' => 'Karma', 'fullName' => 'Akabane Karma', 'gender' => 'male'],
            ['nickname' => 'Koro', 'fullName' => 'Koro-sensei', 'gender' => 'male'], // Duplicate for Koro-sensei
            ['nickname' => 'Izumi', 'fullName' => 'Izumi Curtis', 'gender' => 'female'], // FMA alchemist/cook
            ['nickname' => 'Maes', 'fullName' => 'Maes Hughes', 'gender' => 'male'],
            ['nickname' => 'Roy', 'fullName' => 'Roy Mustang', 'gender' => 'male'],
        ];

        $activeCount = 0;
        $inactiveCount = 0;
        $activeLimit = 50; // Set the limit for 'active' users

        foreach ($chefCharacters as $index => $character) {

            // Determine status: first 50 are 'active', the rest are 'inactive'
            $status = ($activeCount < $activeLimit) ? 'active' : 'inactive';

            // Ensure email uniqueness: Use a unique index in the email for all
            $email = strtolower(str_replace(' ', '', $character['nickname'])) . '@gmail.com';

            // Check if user already exists
            if (User::where('email', $email)->exists()) continue;

            // Increment count based on determined status
            if ($status === 'active') {
                $activeCount++;
            } else {
                $inactiveCount++;
            }

            // Create user
            $user = User::create([
                'email' => $email,
                'password' => Hash::make('123456'),
                'role' => 'chef',
                'status' => $status,
            ]);

            // Create user info
            UserInfo::create([
                'userID' => $user->id,
                'userName' => $character['nickname'],
                'fullName' => $character['fullName'],
                'gender' => $character['gender'],
                'profilePath' => null,
                'coverPath' => null,
                'experience' => null,
                'credentials' => 'credentials/CVPubtMFQpOW3YXZGfDQUnLjvOCaiGsCzmmuYkbX.docx',
            ]);
        }

        $this->command->info("Seeded {$activeCount} active chefs and {$inactiveCount} inactive chefs.");
    }
}
