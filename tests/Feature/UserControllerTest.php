<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function testLoginPage()
    {
        $this->get('/login')->assertSeeText('Login');
    }

    public function testLoginPageForMember()
    {
        $this->withSession([
            "user" => "azka"
        ])->get('/login')->assertRedirect("/");
    }

    public function testLoginSuccess()
    {
        $this->post('/login', [
            "user" => "azka",
            "password" => "123"
        ])->assertRedirect("/")->assertSessionHas("user", "azka");
    }

    public function testLoginForUserAlreadyLogin()
    {
        $this->withSession([
            "user" => "azka"
        ])->post('/login', [
            "user" => "azka",
            "password" => "123"
        ])->assertRedirect("/");
    }

    public function testValidationError()
    {
        $this->post("/login", [])->assertSeeText("User Or Password Is Required");
    }

    public function testLoginFailed()
    {
        $this->post('/login', [
            "user" => "wrong",
            "password" => "wrong"
        ])->assertSeeText("User Or Password Is Wrong");
    }

    public function testLogout()
    {
        $this->withSession([
            "user" => "azka"
        ])->post('/logout')->assertRedirect("/")->assertSessionMissing("user");
    }

    public function testLogoutGuest()
    {
        $this->post('/logout')->assertRedirect("/");
    }
}
