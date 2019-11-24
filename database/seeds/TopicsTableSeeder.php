<?php

use Illuminate\Database\Seeder;

class TopicsTableSeeder extends Seeder
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
                'title' => 'VueJS',
                'created_at' => \now(),
                'updated_at' => \now(),
                'slug' => \Illuminate\Support\Str::slug('VueJS'),
            ],
            ['title' => 'Laravel', 'created_at' => \now(), 'updated_at' => \now(), 'slug' => \Illuminate\Support\Str::slug('Laravel')],
            ['title' => 'PHP', 'created_at' => \now(), 'updated_at' => \now(), 'slug' => \Illuminate\Support\Str::slug('PHP')],
            ['title' => 'Javascript', 'created_at' => \now(), 'updated_at' => \now(), 'slug' => \Illuminate\Support\Str::slug('Javascript')],
            ['title' => 'Python', 'created_at' => \now(), 'updated_at' => \now(), 'slug' => \Illuminate\Support\Str::slug('Python')],
            ['title' => 'C / C++', 'created_at' => \now(), 'updated_at' => \now(), 'slug' => \Illuminate\Support\Str::slug('C / C++')],
            ['title' => 'Java', 'created_at' => \now(), 'updated_at' => \now(), 'slug' => \Illuminate\Support\Str::slug('Java')],
        ];
        \DB::table('topics')->insert($data);
    }
}
