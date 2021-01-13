<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class InitAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wishlists:init-adminuser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create first admin user and roles';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        try{
            $users = User::all();

            if (isset($users[0])) {
                $this->error('Already have users');
                return false;
            }

            $roles = Role::all()->keyBy('name')->toArray();
            if (!isset($roles['admin'])) {
                Role::create(['name' => 'admin']);
            }
            if (!isset($roles['user'])) {
                Role::create(['name' => 'user']);
            }

            $newUserObj = new \stdClass();

            $newUserObj->username = $this->ask('Admin username');
            if ($newUserObj->username == '' || strlen($newUserObj->username) < 5) {
                $this->error('Length of username must be bigger or equal than 5!');
                return false;
            }

            $newUserObj->name = $this->ask('Admin fullname');
            if ($newUserObj->name == '') {
                $this->error('Fullname is required');
                return false;
            }

            $newUserObj->password = $this->secret('Admin password (no visible)');
            if ($newUserObj->password == '' && strlen($newUserObj->password) < 10) {
                $this->error('Length of password must be bigger or equal than 10!');
                return false;
            }

            if (preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$#", $newUserObj->password)) {
                $passwordRe = $this->secret('Admin password confirmation (no visible)');
                if ($passwordRe != $newUserObj->password) {
                    $this->error('Password confirmation does not match to password!');
                }
            } else {
                $this->error('Password is too weak! Length would be bigger or equal 10, must contain at least 1 lowercase letter, 1 uppercase letter, 1 number and 1 special character)');
            }

            $newUser = new User();
            $newUser->createUser($newUserObj);

            $newUser->assignRole('admin');

            $this->info($newUserObj->name . ' has been created! Now you can login with this admin user.');

        }
        catch (\Exception $e) {
            $this->error($e);
        }
    }
}
