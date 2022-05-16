<?php

namespace Tests\Traits;

use App\User;
use Tests\Traits\TestTrait;

trait ControllerTestTrait{

    use TestTrait;

    public function setUp():void
    {
        parent::setUp();

        $this->loginAsDev();

        $this->fakeData = factory($this->model)->make();
    }

    // Create Fake data
    private $fakeData;

    /** @test */
    public function list()
    {
        // Go to route index
        $this->response = $this->get(route($this->baseRoute.'.index'));

        // Expect See View List
        $this->assertCrudList($this->viewList);
    }

    /** @test */
    public function show()
    {
        // Create data in database
        $data = $this->createModel();

        // Go to route show
        $this->response = $this->get(route($this->baseRoute.'.show',$data->id));

        // Expect See View Show
        $this->assertCrudShow('entry',$data);
    }

    /** @test */
    public function created()
    {
        // Create fake data
        $data = $this->makeModel();

        // Go to route store
        $this->response = $this->post(route($this->baseRoute.'.store',$data));

        // Expect Successful Create
        $this->assertSuccessCreated($this->table,$data);
    }

    /** @test */
    public function updated()
    {


        // Create fake data
        $data = $this->createModel();
        $editData = $this->makeModel(['id' => $data['id']]);

        // Go to route update
        $this->response = $this->PUT(route($this->baseRoute.'.update',$data['id']),$editData);

        // Expect Successful Update
        $this->assertSuccessUpdated($this->table,$editData);
    }

    /** @test */
    public function deleted()
    {
        // Create data in database
        $data = $this->createModel();

        // Go to route destroy
        $this->response = $this->delete(route($this->baseRoute.'.destroy',$data->id));

        //Expect Successful Delete
        $this->assertSuccessDeleted($this->table,$data->toArray());
    }

    /** @test */
    public function create_or_update_with_not_null_fields()
    {
        // Check if data has null fields or not
        if(!isset($this->not_null_fields))
        {
            // Skip test if no fields is null
            $this->markTestSkipped($this->table.' expect not having not-null field');
        }

        // Create fake array with null fields
        $null_data = Array();
        foreach($this->not_null_fields as $item)
        {
            $null_data[$item] = null;
        }

        /*-- CREATE --*/

        // Create fake data with null fields
        $data = $this->makeModel($null_data);

        // Go to route store
        $this->response = $this->post(route($this->baseRoute.'.store',$data));

        // Expect error validation with null fields
        $this->assertErrorValidation($this->not_null_fields);

        /*-- UPDATE --*/

        // Create data in database
        $dataUpdate = $this->createModel()->toArray();
        $data['id'] = $dataUpdate['id'];

        // Go to route update
        $this->response = $this->PUT(route($this->baseRoute.'.update',$dataUpdate['id']),$data);

        // Expect error validation with null fields
        $this->assertErrorValidation($this->not_null_fields);
    }

    /** @test */
    public function create_or_update_with_is_email_fields()
    {
        // Check if data has email fields or not
        if(!isset($this->is_email_fields))
        {
            // Skip test if no fields is email
            $this->markTestSkipped($this->table.' expect not having email field');
        }

        // Define array for store email fields
        $email_data = Array();

        // If fields is string //

        // Define email fields as String
        foreach($this->is_email_fields as $item)
        {
            $email_data[$item] = 'notEmail';
        }

        /*-- CREATE --*/

        $data = $this->makeModel($email_data);

        $this->response = $this->post(route($this->baseRoute.'.store',$data));

        // Expect error validation not email fields
        $this->assertErrorValidation($this->is_email_fields);

        /*-- UPDATE --*/

        $dataUpdate = $this->createModel()->toArray();
        $data['id'] = $dataUpdate['id'];

        $this->response = $this->PUT(route($this->baseRoute.'.update',$dataUpdate['id']),$data);

        // Expect error validation not email fields
        $this->assertErrorValidation($this->is_email_fields);

        // Field as Number //

        foreach($this->is_email_fields as $item)
        {
            $email_data[$item] = 123;
        }

        /*-- CREATE --*/
        $data = $this->makeModel($email_data);

        $this->response = $this->post(route($this->baseRoute.'.store',$data));

        // Expect error validation not email fields
        $this->assertErrorValidation($this->is_email_fields);

        /*-- UPDATE --*/
        $dataUpdate = $this->createModel()->toArray();
        $data['id'] = $dataUpdate['id'];

        $this->response = $this->PUT(route($this->baseRoute.'.update',$dataUpdate['id']),$data);

        // Expect error validation not email fields
        $this->assertErrorValidation($this->is_email_fields);

        // Field as Email //

        foreach($this->is_email_fields as $item)
        {
            $email_data[$item] = 'email@email.email';
        }

        /*-- CREATE --*/

        $data = $this->makeModel($email_data);

        $this->response = $this->post(route($this->baseRoute.'.store',$data));

        // Expect Successful Create
        $this->assertSuccessCreated($this->table,$data);

        /*-- UPDATE --*/

        $dataUpdate = $this->createModel()->toArray();
        $data['id'] = $dataUpdate['id'];

        $this->response = $this->PUT(route($this->baseRoute.'.update',$dataUpdate['id']),$data);

        // Expect Successful Create
        $this->assertSuccessUpdated($this->table,$data);
    }

    /** @test */
    public function create_or_update_with_only_string_fields()
    {
        // Check if data has string fields or not
        if(!isset($this->only_string_fields))
        {
            // Skip test if no fields is string
            $this->markTestSkipped($this->table.' expect not having only string field');
        }

        $only_string_data = Array();

        // Field as Number //

        foreach($this->only_string_fields as $item)
        {
            $only_string_data[$item] = 123;
        }

        /*-- CREATE --*/

        $data = $this->makeModel($only_string_data);

        $this->response = $this->post(route($this->baseRoute.'.store',$data));

        // Expect error validation not strng fields
        $this->assertErrorValidation($this->only_string_fields);

        /*-- UPDATE --*/

        $dataUpdate = $this->createModel()->toArray();
        $data['id'] = $dataUpdate['id'];

        $this->response = $this->PUT(route($this->baseRoute.'.update',$dataUpdate['id']),$data);

        // Expect error validation not strng fields
        $this->assertErrorValidation($this->only_string_fields);

        // Field as String //

        foreach($this->only_string_fields as $item)
        {
            $only_string_data[$item] = "string";
        }

        /*-- CREATE --*/

        $data = $this->makeModel($only_string_data);

        $this->response = $this->post(route($this->baseRoute.'.store',$data));

        // Expect Successful Create
        $this->assertSuccessCreated($this->table,$data);

        /*-- UPDATE --*/

        $dataUpdate = $this->createModel()->toArray();
        $data['id'] = $dataUpdate['id'];

        $this->response = $this->PUT(route($this->baseRoute.'.update',$dataUpdate['id']),$data);

        // Expect Successful Create
        $this->assertSuccessUpdated($this->table,$data);
    }

    /** @test */
    public function create_or_update_with_only_number_fields()
    {
        // Check if data has number fields or not
        if(!isset($this->only_number_fields))
        {
            // Skip test if no fields is number
            $this->markTestSkipped($this->table.' expect not having only number field');
        }

        $only_number_data = Array();

        // Field as String //

        foreach($this->only_number_fields as $item)
        {
            $only_number_data[$item] = 'notNumber';
        }

        /*-- CREATE --*/

        $data = $this->makeModel($only_number_data);

        $this->response = $this->post(route($this->baseRoute.'.store',$data));

        // Expect error validation
        $this->assertErrorValidation($this->only_number_fields);

        /*-- UPDATE --*/

        $dataUpdate = $this->createModel()->toArray();
        $data['id'] = $dataUpdate['id'];

        $this->response = $this->PUT(route($this->baseRoute.'.update',$dataUpdate['id']),$data);

        // Expect error validation
        $this->assertErrorValidation($this->only_number_fields);

        // Field as Number //

        foreach($this->only_number_fields as $item)
        {
            $only_number_data[$item] = 123456789;
        }

        /*-- CREATE --*/

        $data = $this->makeModel($only_number_data);

        $this->response = $this->post(route($this->baseRoute.'.store',$data));

        // Expect Successful Update
        $this->assertSuccessCreated($this->table,$data);

        /*-- UPDATE --*/

        $dataUpdate = $this->createModel();
        $data['id'] = $dataUpdate['id'];

        $this->response = $this->PUT(route($this->baseRoute.'.update',$dataUpdate['id']),$data);

        // Expect Successful Update
        $this->assertSuccessUpdated($this->table,$data);
    }

    /** @test */
    public function create_or_update_with_specific_length_fields()
    {
        if(!isset($this->specific_length_fields))
        {
            $this->markTestSkipped($this->table.' expect not having specific fields length');
        }

        $specific_length_field = Array();
        $all_specific_length_fields = Array();

        // Field less than MIN//

        foreach($this->specific_length_fields as $item)
        {
            $specific_length_field[$item[0]] = \Str::random($item[1]-1);
            array_push($all_specific_length_fields,$item[0]);
        }

        /*-- CREATE --*/

        $data = $this->makeModel($specific_length_field);

        $this->response = $this->post(route($this->baseRoute.'.store',$data));

        // Expect error validation
        $this->assertErrorValidation($all_specific_length_fields);

        /*-- UPDATE --*/

        $dataUpdate = $this->createModel();
        $data['id'] = $dataUpdate['id'];

        $this->response = $this->PUT(route($this->baseRoute.'.update',$dataUpdate['id']),$data);

        // Expect error validation
        $this->assertErrorValidation($all_specific_length_fields);

        // Field Greater than MAX//

        foreach($this->specific_length_fields as $item)
        {
            $specific_length_field[$item[0]] = \Str::random($item[2]+1);
            array_push($all_specific_length_fields,$item[0]);
        }

        /*-- CREATE --*/

        $data = $this->makeModel($specific_length_field);

        $this->response = $this->post(route($this->baseRoute.'.store',$data));

        // Expect error validation
        $this->assertErrorValidation($all_specific_length_fields);

        /*-- UPDATE --*/

        $dataUpdate = $this->createModel();
        $data['id'] = $dataUpdate['id'];

        $this->response = $this->PUT(route($this->baseRoute.'.update',$dataUpdate['id']),$data);

        // Expect error validation
        $this->assertErrorValidation($all_specific_length_fields);
    }

    function makeModel($fields=null)
    {
        if($fields == null)
        {
            $fields = [];
        }

        $data = $this->fakeData->toArray();

        foreach($fields as $key => $value)
        {
            $data[$key] = $value;
        }

        return $data;
    }

    function createModel()
    {
        $data = $this->makeModel();

        $data = factory($this->model)->create($data);

        return $data;
    }

}
