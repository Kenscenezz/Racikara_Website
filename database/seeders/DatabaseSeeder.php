<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Categories
        \Illuminate\Support\Facades\DB::table('categories')->insert([
            ['id' => 1, 'name' => 'Makanan Utama', 'icon' => '🍲'],
            ['id' => 2, 'name' => 'Dessert', 'icon' => '🧁'],
            ['id' => 3, 'name' => 'Minuman', 'icon' => '🥤'],
            ['id' => 4, 'name' => 'Sarapan', 'icon' => '🌅'],
            ['id' => 5, 'name' => 'Makanan Sehat', 'icon' => '🥗'],
            ['id' => 6, 'name' => 'Camilan', 'icon' => '🍿'],
        ]);

        // Seed Users
        \Illuminate\Support\Facades\DB::table('users')->insert([
            [
                'id' => 1,
                'full_name' => 'Dapur Cirebon',
                'email' => 'user@racikara.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'user',
                'bio' => 'Pecinta masakan Cirebon yang suka berbagi resep.',
                'profile_photo' => 'default-user.png',
            ],
            [
                'id' => 2,
                'full_name' => 'Admin Racikara',
                'email' => 'admin@racikara.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'admin',
                'bio' => 'Admin platform Racikara.',
                'profile_photo' => 'default-user.png',
            ]
        ]);

        // Seed Recipes
        \Illuminate\Support\Facades\DB::table('recipes')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'category_id' => 1,
                'title' => 'Ayam Panggang Madu',
                'description' => 'Resep ayam panggang madu yang manis, gurih, dan mudah dibuat di rumah. Cocok untuk makan siang maupun makan malam bersama keluarga.',
                'ingredients' => "500 gr dada ayam\n3 sdm madu\n2 siung bawang putih (cincang)\n1 sdm kecap asin\n1 sdm minyak zaitun\nGaram secukupnya\nLada secukupnya\nJeruk nipis secukupnya",
                'steps' => "Campurkan madu, bawang putih, kecap asin, minyak zaitun, lada, dan jeruk nipis.\nLumuri ayam dengan bumbu marinasi, diamkan 30 menit.\nPanaskan oven/teflon hingga panas.\nPanggang ayam hingga matang dan kecokelatan sekitar 15 menit per sisi.\nAngkat dan sajikan dengan nasi putih dan lalapan.",
                'cooking_time' => 30,
                'difficulty' => 'Mudah',
                'portion' => 2,
                'calories' => 320,
                'image' => 'ayam-panggang-madu.jpg',
                'rating' => 4.8,
                'tags' => 'Ayam,Panggang,Madu,Rumahan,Mudah',
                'status' => 'published',
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'category_id' => 1,
                'title' => 'Nasi Goreng Spesial',
                'description' => 'Nasi goreng dengan bumbu spesial yang gurih dan lezat, dilengkapi dengan telur dan sayuran segar.',
                'ingredients' => "2 piring nasi putih\n2 butir telur\n3 siung bawang merah (iris)\n2 siung bawang putih (cincang)\n2 sdm kecap manis\n1 sdm saus tiram\nGaram dan merica secukupnya\nMinyak goreng secukupnya",
                'steps' => "Panaskan minyak, tumis bawang merah dan bawang putih hingga harum.\nMasukkan telur, orak-arik hingga setengah matang.\nMasukkan nasi, aduk rata.\nTambahkan kecap manis, saus tiram, garam, dan merica.\nAduk hingga semua tercampur dan matang sempurna.\nSajikan dengan kerupuk dan acar.",
                'cooking_time' => 20,
                'difficulty' => 'Mudah',
                'portion' => 3,
                'calories' => 450,
                'image' => 'nasi-goreng-seafood.jpg',
                'rating' => 4.7,
                'tags' => 'NasiGoreng,Spesial,Sarapan,Mudah',
                'status' => 'published',
            ]
        ]);
    }
}
