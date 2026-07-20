<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicPageTest extends TestCase
{
    /**
     * Landing page dapat dibuka.
     */
    public function test_landing_page_dapat_dibuka(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertSee(
            'Sistem Informasi SPMI',
            false
        );
    }

    /**
     * Halaman login dapat dibuka.
     */
    public function test_halaman_login_dapat_dibuka(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * Request GET ke logout tidak diperbolehkan.
     */
    public function test_logout_tidak_dapat_diakses_dengan_get(): void
    {
        $response = $this->get('/logout');

        $response->assertStatus(405);
    }
}