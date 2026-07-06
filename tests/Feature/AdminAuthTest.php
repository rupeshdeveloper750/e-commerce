<?php

it('exposes the admin login page and login store routes', function () {
    expect(route('admin.login'))->toContain('/admin/login');
    expect(route('admin.login.store'))->toContain('/admin/login');
});
