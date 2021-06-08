<?php

namespace Areas\_Admin;

use Illuminate\View\View;

class AdminController {
    public function index(): view {
        return view('_admin.index');
    }
}