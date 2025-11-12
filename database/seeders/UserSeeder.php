<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $animeCharacters = [
            ['nickname' => 'Goku', 'fullName' => 'Son Goku', 'gender' => 'male'],
            ['nickname' => 'Vegeta', 'fullName' => 'Prince Vegeta', 'gender' => 'male'],
            ['nickname' => 'Gohan', 'fullName' => 'Son Gohan', 'gender' => 'male'],
            ['nickname' => 'Piccolo', 'fullName' => 'Piccolo', 'gender' => 'male'],
            ['nickname' => 'Krillin', 'fullName' => 'Kuririn', 'gender' => 'male'],
            ['nickname' => 'Trunks', 'fullName' => 'Trunks Brief', 'gender' => 'male'],
            ['nickname' => 'Bulma', 'fullName' => 'Bulma Brief', 'gender' => 'female'],
            ['nickname' => 'Naruto', 'fullName' => 'Uzumaki Naruto', 'gender' => 'male'],
            ['nickname' => 'Sasuke', 'fullName' => 'Uchiha Sasuke', 'gender' => 'male'],
            ['nickname' => 'Sakura', 'fullName' => 'Haruno Sakura', 'gender' => 'female'],
            ['nickname' => 'Kakashi', 'fullName' => 'Hatake Kakashi', 'gender' => 'male'],
            ['nickname' => 'Shikamaru', 'fullName' => 'Nara Shikamaru', 'gender' => 'male'],
            ['nickname' => 'Choji', 'fullName' => 'Akimichi Choji', 'gender' => 'male'],
            ['nickname' => 'Hinata', 'fullName' => 'Hyuga Hinata', 'gender' => 'female'],
            ['nickname' => 'Neji', 'fullName' => 'Hyuga Neji', 'gender' => 'male'],
            ['nickname' => 'RockLee', 'fullName' => 'Lee Rock', 'gender' => 'male'],
            ['nickname' => 'Gaara', 'fullName' => 'Gaara of the Sand', 'gender' => 'male'],
            ['nickname' => 'Luffy', 'fullName' => 'Monkey D. Luffy', 'gender' => 'male'],
            ['nickname' => 'Zoro', 'fullName' => 'Roronoa Zoro', 'gender' => 'male'],
            ['nickname' => 'Nami', 'fullName' => 'Nami', 'gender' => 'female'],
            ['nickname' => 'Robin', 'fullName' => 'Nico Robin', 'gender' => 'female'],
            ['nickname' => 'Franky', 'fullName' => 'Franky', 'gender' => 'male'],
            ['nickname' => 'Brook', 'fullName' => 'Brook', 'gender' => 'male'],
            ['nickname' => 'Jinbe', 'fullName' => 'Jinbe', 'gender' => 'male'],
            ['nickname' => 'Ichigo', 'fullName' => 'Kurosaki Ichigo', 'gender' => 'male'],
            ['nickname' => 'Rukia', 'fullName' => 'Kuchiki Rukia', 'gender' => 'female'],
            ['nickname' => 'Renji', 'fullName' => 'Abarai Renji', 'gender' => 'male'],
            ['nickname' => 'Orihime', 'fullName' => 'Inoue Orihime', 'gender' => 'female'],
            ['nickname' => 'Uryu', 'fullName' => 'Ishida Uryu', 'gender' => 'male'],
            ['nickname' => 'Yasutora', 'fullName' => 'Sado Yasutora', 'gender' => 'male'],
            ['nickname' => 'Edward', 'fullName' => 'Elric Edward', 'gender' => 'male'],
            ['nickname' => 'Alphonse', 'fullName' => 'Elric Alphonse', 'gender' => 'male'],
            ['nickname' => 'Roy', 'fullName' => 'Mustang Roy', 'gender' => 'male'],
            ['nickname' => 'Riza', 'fullName' => 'Hawkeye Riza', 'gender' => 'female'],
            ['nickname' => 'Winry', 'fullName' => 'Rockbell Winry', 'gender' => 'female'],
            ['nickname' => 'Eren', 'fullName' => 'Yeager Eren', 'gender' => 'male'],
            ['nickname' => 'Mikasa', 'fullName' => 'Ackerman Mikasa', 'gender' => 'female'],
            ['nickname' => 'Armin', 'fullName' => 'Arlert Armin', 'gender' => 'male'],
            ['nickname' => 'Levi', 'fullName' => 'Ackerman Levi', 'gender' => 'male'],
            ['nickname' => 'Historia', 'fullName' => 'Reiss Historia', 'gender' => 'female'],
            ['nickname' => 'Jean', 'fullName' => 'Kirschtein Jean', 'gender' => 'male'],
            ['nickname' => 'Connie', 'fullName' => 'Springer Connie', 'gender' => 'male'],
            ['nickname' => 'Sasha', 'fullName' => 'Blaustein Sasha', 'gender' => 'female'],
            ['nickname' => 'Tanjiro', 'fullName' => 'Kamado Tanjiro', 'gender' => 'male'],
            ['nickname' => 'Nezuko', 'fullName' => 'Kamado Nezuko', 'gender' => 'female'],
            ['nickname' => 'Zenitsu', 'fullName' => 'Agatsuma Zenitsu', 'gender' => 'male'],
            ['nickname' => 'Inosuke', 'fullName' => 'Hashibira Inosuke', 'gender' => 'male'],
            ['nickname' => 'Giyu', 'fullName' => 'Tomie Giyu', 'gender' => 'male'],
            ['nickname' => 'Shinobu', 'fullName' => 'Kocho Shinobu', 'gender' => 'female'],
            ['nickname' => 'Kanao', 'fullName' => 'Tsuyuri Kanao', 'gender' => 'female'],
            ['nickname' => 'SailorMoon', 'fullName' => 'Usagi Tsukino', 'gender' => 'female'],
            ['nickname' => 'TuxedoMask', 'fullName' => 'Mamoru Chiba', 'gender' => 'male'],
            ['nickname' => 'Chibiusa', 'fullName' => 'Small Lady Serenity', 'gender' => 'female'],
            ['nickname' => 'Mercury', 'fullName' => 'Ami Mizuno', 'gender' => 'female'],
            ['nickname' => 'Mars', 'fullName' => 'Rei Hino', 'gender' => 'female'],
            ['nickname' => 'Jupiter', 'fullName' => 'Makoto Kino', 'gender' => 'female'],
            ['nickname' => 'Venus', 'fullName' => 'Minako Aino', 'gender' => 'female'],
            ['nickname' => 'Light', 'fullName' => 'Yagami Light', 'gender' => 'male'],
            ['nickname' => 'L', 'fullName' => 'L Lawliet', 'gender' => 'male'],
            ['nickname' => 'Misa', 'fullName' => 'Amane Misa', 'gender' => 'female'],
            ['nickname' => 'Near', 'fullName' => 'Near', 'gender' => 'male'],
            ['nickname' => 'Mello', 'fullName' => 'Mello', 'gender' => 'male'],
            ['nickname' => 'Ryuk', 'fullName' => 'Ryuk', 'gender' => 'male'],
            ['nickname' => 'Gintoki', 'fullName' => 'Gintoki Sakata', 'gender' => 'male'],
            ['nickname' => 'Shinpachi', 'fullName' => 'Shimura Shinpachi', 'gender' => 'male'],
            ['nickname' => 'Kagura', 'fullName' => 'Kagura', 'gender' => 'female'],
            ['nickname' => 'Okita', 'fullName' => 'Sougo Okita', 'gender' => 'male'],
            ['nickname' => 'Hijikata', 'fullName' => 'Toshirou Hijikata', 'gender' => 'male'],
            ['nickname' => 'Kenshin', 'fullName' => 'Himura Kenshin', 'gender' => 'male'],
            ['nickname' => 'Kaoru', 'fullName' => 'Kamiya Kaoru', 'gender' => 'female'],
            ['nickname' => 'Yusuke', 'fullName' => 'Urameshi Yusuke', 'gender' => 'male'],
            ['nickname' => 'Kuwabara', 'fullName' => 'Kazuma Kuwabara', 'gender' => 'male'],
            ['nickname' => 'Kurama', 'fullName' => 'Kurama', 'gender' => 'male'],
            ['nickname' => 'Hiei', 'fullName' => 'Hiei', 'gender' => 'male'],
            ['nickname' => 'Gon', 'fullName' => 'Gon Freecss', 'gender' => 'male'],
            ['nickname' => 'Killua', 'fullName' => 'Zoldyck Killua', 'gender' => 'male'],
            ['nickname' => 'Kurapika', 'fullName' => 'Kurta Kurapika', 'gender' => 'male'],
            ['nickname' => 'Leorio', 'fullName' => 'Paradinight Leorio', 'gender' => 'male'],
            ['nickname' => 'Simon', 'fullName' => 'Simon', 'gender' => 'male'],
            ['nickname' => 'Kamina', 'fullName' => 'Kamina', 'gender' => 'male'],
            ['nickname' => 'Yoko', 'fullName' => 'Yoko Littner', 'gender' => 'female'],
            ['nickname' => 'Nia', 'fullName' => 'Nia Teppelin', 'gender' => 'female'],
            ['nickname' => 'Lain', 'fullName' => 'Iwakura Lain', 'gender' => 'female'],
            ['nickname' => 'Spike', 'fullName' => 'Spike Spiegel', 'gender' => 'male'],
            ['nickname' => 'Faye', 'fullName' => 'Faye Valentine', 'gender' => 'female'],
            ['nickname' => 'Edward2', 'fullName' => 'Edward Wong Hau Pepelu Tivrusky IV', 'gender' => 'male'],
            ['nickname' => 'Al', 'fullName' => 'Alphonse Wong', 'gender' => 'male'],
            ['nickname' => 'Kaname', 'fullName' => 'Tosen Kaname', 'gender' => 'male'],
            ['nickname' => 'Orihara', 'fullName' => 'Izaya Orihara', 'gender' => 'male'],
            ['nickname' => 'Shizuo', 'fullName' => 'Heiwajima Shizuo', 'gender' => 'male'],
            ['nickname' => 'Mikoto', 'fullName' => 'Misaka Mikoto', 'gender' => 'female'],
        ];




        for ($i = 0; $i < 100; $i++) {
            $character = $animeCharacters[array_rand($animeCharacters)];
            $email = strtolower($character['nickname']) . '@gmail.com';

            // Check if the email is already registered
            if (User::where('email', $email)->exists()) {
                continue; // skip this iteration if already exists
            }

            $user = User::create([
                'email' => $email,
                'password' => Hash::make('123456'),
                'role' => 'user',
                'status' => 'active',
            ]);

            UserInfo::create([
                'userID' => $user->id,
                'userName' => $character['nickname'],
                'fullName' => $character['fullName'],
                'gender' => $character['gender'],
                'profilePath' => null,
                'coverPath' => null,
                'experience' => null,
                'credentials' => null,
            ]);
        }
    }
}
