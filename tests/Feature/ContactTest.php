<?php

namespace Tests\Feature;

use App\Models\Contact;
use Database\Seeders\ContactSeeder;
use Database\Seeders\SearchSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Log;
use Tests\TestCase;

class ContactTest extends TestCase
{

    public function testCreateContactSuccess()
    {
        $this->seed(UserSeeder::class);
        $this->post('/api/contacts', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
        ], [
            'Authorization' => 'test'
        ])->assertStatus(201)
            ->assertJson([
                'data' => [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'john@example.com',
                    'phone' => '1234567890',
                ]
            ]);
    }

    public function testCreateFailed()
    {
        $this->seed(UserSeeder::class);
        $this->post('/api/contacts', [
            'first_name' => '',
            'last_name' => '',
            'email' => 'not-an-email',
            'phone' => '1234457',
        ], [
            'Authorization' => 'test'
        ])->assertStatus(422);
    }

    public function testSearchContactSuccess()
    {
        $this->seed([UserSeeder::class, SearchSeeder::class]);
        $response = $this->get('/api/contacts?name=first', [
            'Authorization' => 'test',
        ])->assertStatus(200)->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));
    }

    public function testGetUserSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . $contact->id, [
            'Authorization' => 'test'
        ])->assertStatus(200);
    }

    public function testUpdateContact()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->limit(1)->first();

        $this->put('/api/contacts/' . $contact->id, [
            'first_name' => 'test2',
            'last_name' => 'test2',
            'email' => 'test2@mail.com',
            'phone' => '1111112',
        ], [
            'Authorization' => 'test'
        ])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'first_name' => 'test2',
                    'last_name' => 'test2',
                    'email' => 'test2@mail.com',
                    'phone' => '1111112',
                ]
            ]);
    }

    public function testDeleteContactSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->delete('/api/contacts/' . $contact->id, [], [
            'Authorization' => 'test'
        ])
        ->assertStatus(200)->assertJson([
            'data'=>true
        ]);
    }

    public function testDeleteNotFound(){
        $this->seed([UserSeeder::class,ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();
        $this->delete('/api/contacts/'.$contact->id+1,[],[
            'Authorization'=>'test'
        ])->assertStatus(404)->assertJson(
            [
                'errors'=>[
                    'message'=>[
                        'Contact Not Found'
                    ]
                ]
            ]
        );
    }

}
