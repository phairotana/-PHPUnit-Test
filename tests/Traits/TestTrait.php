<?php

namespace Tests\Traits;

use App\User;
use Illuminate\Support\Facades\Hash;

trait TestTrait{

    private $response;

    // Expect what we can see in the view
    function assertViewSee($keys=[])
    {
        foreach ($keys as $key)
        {
            $this->response->assertSee($key);
        }
    }

    // Expect what we can not see in the view
    function assertViewNotSee($keys=[])
    {
        foreach ($keys as $key)
        {
            $this->response->assertDontSee($key);
        }
    }

    // Expect what we can see list view
    function assertCrudList($view)
    {
        $this->response->assertStatus(200)
                       ->assertViewIs($view);
    }

    // Expect what we can see preview view
    function assertCrudShow($entry,$data)
    {

        $this->response->assertViewHas($entry,$data);
    }

    // Expect view not found
    function assertViewNotFound()
    {
        $this->response->assertStatus(404);
    }

    // Expect what we can create entity
    function assertSuccessCreated($table,$data)
    {
        $this->response->assertStatus(302);
        $this->assertDatabaseHas($table,$data);
    }

    // Expect what we can update entity
    function assertSuccessUpdated($table,$data)
    {
        $this->response->assertStatus(302);
        $this->assertDatabaseHas($table,$data);
    }

    // Expect what we can delete entity
    function assertSuccessDeleted($table,$data)
    {
        $this->response->assertStatus(200);
        $this->assertDatabaseMissing($table,$data);
    }

    // Expect to have wrong validation
    function assertErrorValidation($fields)
    {
        $this->response->assertStatus(302);
        $this->response->assertSessionHasErrors($fields);
    }

    // Expect not having permission
    function assertAccessDeny()
    {
        $this->response->assertStatus(403);
    }

    // Login as Dev
    function loginAsDev()
    {
        $userLogin=$this->post('/admin/login',[
            'email' => $this->email,
            'password'  => $this->password,
        ]);

        return $userLogin;
    }

    // Login in as any role
    function loginAs($role)
    {
        $user = factory(User::class)->create(
            ['password' => Hash::make('123456789')]
        )->assignRole($role);

        $userLogin=$this->post('/admin/login',[
            'email' => $user->email,
            'password' => '123456789',
        ]);

        return $userLogin;
    }

    // Get last record from table
    function getLastRecord($model)
    {
        return $model::orderBy('id','desc')->first();
    }

    // Get All record from table
    function getAllRecord($model)
    {
        return $model::all();
    }


}
