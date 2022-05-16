<?php

namespace Tests\Feature\UseTraitTest;

use App\Models\Account;
use Tests\TestCase;
use Tests\Traits\TestTrait;

class AccountCrudTest extends TestCase
{
    use TestTrait;

    private $email = 'dev@dev.com', $password = '123456789';

    public function setUp():void
    {
        parent::setUp();

        $this->loginAsDev();
    }

    /** @test */
    public function listAllAccounts()
    {
        $this->response = $this->get(route('account.index'));

        $this->assertCrudList('crud::list');
    }

    /** @test */
    public function previewAccountDetail()
    {
        $account = factory(Account::class)->create();

        $this->response = $this->get(route('account.show',$account->id));

        $this->assertCrudShow('entry',$account);
    }

    /** @test */
    public function createAccount()
    {
        $account = factory(Account::class)->make()->toArray();

        $this->response = $this->post(route('account.store',$account));

        $this->assertSuccessCreated('accounts',$account);
    }

    /** @test */
    public function updateAccount()
    {
        $account = factory(Account::class)->create();

        $editAccount = factory(Account::class)->make(['id'=>$account->id])->toArray();

        $this->response = $this->put(route('account.update',$account->id),$editAccount);

        $this->assertSuccessUpdated('accounts',$editAccount);
    }

    /** @test */
    public function deleteAccount()
    {
        $account = factory(Account::class)->create();

        $this->response = $this->delete(route('account.destroy',$account->id));

        $this->assertSuccessDeleted('accounts',$account->toArray());
    }

    /** @test */
    public function createAccountWithNullField()
    {
        $account = factory(Account::class)->make(['name'=>null,'phone'=>null,'email'=>null,'address'=>null])->toArray();

        $this->response = $this->post(route('account.store',$account));

        $this->assertErrorValidation(['name','phone','email','address']);
    }

    /** @test */
    public function createAccountWithNameLessThan2Characters()
    {
        $account = factory(Account::class)->make(['name'=>'a'])->toArray();

        $response = $this->post(route('account.store',$account));

        $response->assertSessionHasErrors(['name'=>'The name must be at least 2 characters.']);
    }

    /** @test */
    public function createAccountWithNameGreaterThan50Characters()
    {
        $account = factory(Account::class)->make(['name'=>\Str::random(51)])->toArray();

        $response = $this->post(route('account.store',$account));

        $response->assertSessionHasErrors(['name'=>'The name may not be greater than 50 characters.']);
    }

    /** @test */
    public function createAccountWithEmailNotEmailFormat()
    {
        $account = factory(Account::class)->make(['email'=>'email'])->toArray();

        $response = $this->post(route('account.store',$account));

        $response->assertSessionHasErrors(['email'=>'The email must be a valid email address.']);
    }

    /** @test */
    public function createAccountWithNumberNotNumberFormat()
    {
        $account = factory(Account::class)->make(['number'=>'NotNumber'])->toArray();

        $response = $this->post(route('account.store',$account));

        $response->assertSessionHasErrors(['number'=>'The number must be a number.']);
    }

    /** @test */
    public function updateAccountWithNullField()
    {
        $account = factory(Account::class)->create();

        $editAccount = factory(Account::class)->make(['name'=>null,'phone'=>null,'email'=>null,'address'=>null])->toArray();

        $this->response = $this->put(route('account.update',$account->id),$editAccount);

        $this->assertErrorValidation(['name','phone','email','address']);
    }

    /** @test */
    public function updateAccountWithNameLessThan2Characters()
    {
        $account = factory(Account::class)->create();

        $editAccount = factory(Account::class)->make(['name'=>'a'])->toArray();

        $response = $this->put(route('account.update',$account->id),$editAccount);

        $response->assertSessionHasErrors(['name'=>'The name must be at least 2 characters.']);
    }

    /** @test */
    public function updateAccountWithNameGreaterThan50Characters()
    {
        $account = factory(Account::class)->create();

        $editAccount = factory(Account::class)->make(['name'=>\Str::random(51)])->toArray();

        $response = $this->put(route('account.update',$account->id),$editAccount);

        $response->assertSessionHasErrors(['name'=>'The name may not be greater than 50 characters.']);
    }

    /** @test */
    public function updateAccountWithEmailNotEmailFormat()
    {
        $account = factory(Account::class)->create();

        $editAccount = factory(Account::class)->make(['email'=>'email'])->toArray();

        $response = $this->put(route('account.update',$account->id),$editAccount);

        $response->assertSessionHasErrors(['email'=>'The email must be a valid email address.']);
    }

    /** @test */
    public function updateAccountWithNumberNotNumberFormat()
    {
        $account = factory(Account::class)->create();

        $editAccount = factory(Account::class)->make(['number'=>'NotNumber'])->toArray();

        $response = $this->put(route('account.update',$account->id),$editAccount);

        $response->assertSessionHasErrors(['number'=>'The number must be a number.']);
    }
}
