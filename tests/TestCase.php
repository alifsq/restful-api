<?php

namespace Tests;

use \Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    //

    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from adresses");
        DB::delete("delete from contacts");
        DB::delete("delete from users");
    }
}
