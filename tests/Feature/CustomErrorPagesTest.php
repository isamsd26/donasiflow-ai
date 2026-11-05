<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomErrorPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_404_error_page_renders_custom_brand_identity(): void
    {
        $response = $this->get('/halaman-yang-pasti-tidak-ada-12345');

        $response->assertStatus(404);
        $response->assertSee('404');
        $response->assertSee('Halaman yang Anda Cari Tidak Ditemukan');
        $response->assertSee('Yayasan Riyadhush Sholihin');
        $response->assertSee('Kembali ke Beranda');
    }

    public function test_error_views_exist_and_render_cleanly(): void
    {
        $views = [
            'errors.404',
            'errors.500',
            'errors.419',
            'errors.403',
            'errors.503',
            'errors.401',
            'errors.429',
        ];

        foreach ($views as $viewName) {
            $rendered = view($viewName)->render();
            $this->assertNotEmpty($rendered);
            $this->assertStringContainsString('Yayasan Riyadhush Sholihin', $rendered);
        }
    }
}
