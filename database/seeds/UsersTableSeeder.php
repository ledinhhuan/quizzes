<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Admin Huan',
                'email' => 'huan.ld@neo-lab.vn',
                'password' => '$2y$10$/Ti53ez.QOFILU4wsJ5QjePH8TLHORdQJx6KyMh7lQHSpYRiN2aG.',
                'is_admin' => 1,
                'image' => 'https://img.icons8.com/clouds/100/000000/guest-male.png'
            ],
            [
                'name' => 'Khuong',
                'email' => 'khuong@gmail.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'is_admin' => 1,
                'image' => 'https://img.icons8.com/clouds/100/000000/guest-male.png'
            ],
            [
                'name' => 'Thuan',
                'email' => 'thuan@gmail.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'is_admin' => 1,
                'image' => 'https://img.icons8.com/clouds/100/000000/guest-male.png'
            ]
        ];
        \DB::table('users')->insert($data);
    }
}
