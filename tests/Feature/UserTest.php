<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Faker\Factory as Faker;

class UserTest extends TestCase
{
    public function testLoginPageVisit()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    public function testLogin()
    {
        $user = User::first();

        $response = $this->post(route('admin.login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertSessionHas('alert-type', 'success');
    }

    public function testRegisterPageVisit()
    {
        $response = $this->get(route('admin.register-form'));

        $response->assertStatus(200);
    }

    public function testRegister()
    {
        $faker = Faker::create();
        $user = [
            'name' => $faker->name,
            'email' => $faker->companyEmail
        ];
        $response = $this->post(route('admin.register'), [
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);
        $response->assertRedirect('/');

        //$this->assertTrue(!!User::where('email', $user['email'])->delete());
    }
}
