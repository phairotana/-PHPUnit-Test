<?php

namespace Tests\Feature;

use App\User;

use Tests\TestCase;

class LoginTest extends TestCase
{

    //You must use /** @test */ on the function test

    //This function use for test user can see login form

       /** @test */
       public function UserCanLoginForm()
       {
           $response = $this->get('admin/login'); //User request URI (admin/login)

           $response->assertSuccessful(); //User request to URI successful

           $response->assertViewIs('backpack::auth.login'); //User can see a backpack login form
       }

       //This function use for test user can login

       /** @test */
       public function UserCanLogin()
       {
           $response = $this->post('/admin/login', //Make request user' email and password
               [
                   'email' => 'dev@dev.com',
                   'password' => '123456789'
               ]);

           $response->assertRedirect('/admin/dashboard'); //When request success it's redirect to admin dashboard
       }

       //This function use for test user can login using factories

       /** @test */
       public function UserCanLoginUsingFactory()
       {
           $user = factory(User::class)->create(); //Using factory for create user

           $response = $this->post('/admin/login', //Make request user's email and password
               [
                   'email' => $user->email,
                   'password' => '123456789',
               ]);

           $response->assertRedirect('/admin/dashboard'); ////When request success it's redirect to admin dashboard
       }
}


