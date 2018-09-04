<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' =>'Nguyễn Nam',
            'email' =>'nam@gmail.com',
            'password' => bcrypt('123456'),
            'type' => 1,
        ]);
        $this->call(OriginSeeder::class);
    }
}
class OriginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('origins')->insert([
           array(
            'name' => 'Hệ thống cảnh báo email spam từ nước ngoài',
            'address' =>  'mail.vncert.vn:993',
            'typemap' =>  'imap/spam_1',
            'account' =>  'report@vncert.vn',
            'maxget' => '20', 
            ),
           array(
            'name' => 'Thu thập thư rác do người dùng forward ',
            'address' =>  'mail.gmail.com:993',
            'typemap' =>  'imap/spam',
            'account' =>  'tiepnhanthurac@gmail.com',
            'maxget' => '20',
            )
            ]);
    }
}