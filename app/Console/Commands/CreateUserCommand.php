<?php

namespace App\Console\Commands;

use App\Models\Address;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CreateUserCommand extends Command
{
    protected $signature = 'users:create';

    protected $description = 'Creates a new user';

    public function handle()
    {
        $user['name'] = $this->ask('Name of the new user');
        $user['surname'] = $this->ask('Surname of the new user');
        $user['email'] = $this->ask('Email of the new user');
        $user['username'] = $this->ask('Username of the new user');
        $user['password'] = $this->secret('Password of the new user');

        $validator = Validator::make($user, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return -1;
        }

        DB::transaction(function () use ($user) {
            $user['address_id'] = Address::create()->id;
            User::create($user);
        });
    }
}
