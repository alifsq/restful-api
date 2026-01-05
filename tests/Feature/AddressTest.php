<?php

namespace Tests\Feature;

use App\Models\Contact;
use Database\Seeders\ContactSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddressTest extends TestCase
{
    public function testCreateAddress()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();
        $this->post(
            '/api/contacts/' . $contact->id . '/addresses',
            [
                'street' => 'test',
                'city' => 'test',
                'province' => 'test',
                'country'=>'test',
                'postal_code' => '12345'
            ],
            [
                'Authorization' => 'test'
            ]
        )
        ->assertStatus(201)
        ->assertJson([
            'data'=>[
                'street' => 'test',
                'city' => 'test',
                'province' => 'test',
                'country'=>'test',
                'postal_code' => '12345'
            ]
        ]);
    }
    
}
