<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // --- Үндсэн Төрлүүд (Main Genres) ---
            'Action',
            'Adventure',
            'Action-Adventure',
            'RPG (Role-Playing)',
            'Strategy',
            'Simulation',
            'Sports',
            'Racing',
            'Puzzle',
            'Horror',
            'Arcade',
            'Casual',
            'Indie',

            // --- Буудагч Төрлүүд (Shooters) ---
            'FPS (First-Person Shooter)',
            'TPS (Third-Person Shooter)',
            'Tactical Shooter',
            'Looter Shooter',
            'Hero Shooter',
            'Shoot \'em up',
            'Battle Royale',

            // --- RPG Дэд Төрлүүд ---
            'Action RPG',
            'JRPG (Japanese RPG)',
            'MMORPG',
            'CRPG (Classic RPG)',
            'Turn-Based RPG',
            'Roguelike',
            'Roguelite',
            'Dungeon Crawler',

            // --- Стратеги Дэд Төрлүүд ---
            'RTS (Real-Time Strategy)',
            'TBS (Turn-Based Strategy)',
            'MOBA (Multiplayer Online Battle Arena)',
            'Tower Defense',
            'Grand Strategy',
            '4X Strategy',
            'Card & Board',
            'Auto Battler',

            // --- Симуляци & Спорт ---
            'Life Sim',
            'Farming Sim',
            'City Builder',
            'Space Sim',
            'Flight Simulator',
            'Management',
            'Drifting',
            'Offroad',

            // --- Action Дэд Төрлүүд ---
            'Platformer',
            'Fighting',
            'Beat \'em up',
            'Stealth',
            'Survival',
            'Hack and Slash',
            'Metroidvania',
            'Soulslike',

            // --- Бусад & Сэдэв (Themes & Others) ---
            'Open World',
            'Sandbox',
            'Survival Horror',
            'Psychological Horror',
            'Visual Novel',
            'Point & Click',
            'Interactive Movie',
            'Party Game',
            'Music / Rhythm',
            'Trivia',
            
            // --- Орчин & Style ---
            'Sci-Fi',
            'Cyberpunk',
            'Fantasy',
            'Post-Apocalyptic',
            'Anime',
            'Retro / Pixel Art',
            'Mystery',
            'Zombies',
            'War / Military',

            // --- Горимууд (Modes) ---
            'Singleplayer',
            'Multiplayer',
            'Co-op (Cooperative)',
            'PvP (Player vs Player)',
            'MMO (Massively Multiplayer)',
            'VR (Virtual Reality)'
        ];

        foreach ($categories as $cat) {
            // firstOrCreate нь давхардахаас сэргийлж, 
            // хэрэв байхгүй бол шинээр үүсгэнэ.
            Category::firstOrCreate(['name' => $cat]);
        }
    }
}