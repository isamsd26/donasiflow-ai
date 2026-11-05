<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\AdminSite;

abstract class AdminController extends Controller
{
    protected function adminShell(): array
    {
        return AdminSite::shell();
    }

    protected function adminPage(string $key, ?string $title = null, ?string $description = null): array
    {
        $page = AdminSite::page($key);

        if ($title !== null) {
            $page['title'] = $title;
        }

        if ($description !== null) {
            $page['description'] = $description;
        }

        return $page;
    }
}
