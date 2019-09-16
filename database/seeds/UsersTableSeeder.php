<?php
use App\User;
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
        //DB::table('users')->delete();
        User::create(array(
            'name' => 'mikhail',
            'email'    => 'sunandlove1980@tutanota.com',
            'password' => Hash::make('awesome'),
        ));
    }
}
