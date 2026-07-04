<?php

it('exposes the admin login page and logon alias routes', function () {
    expect(route('admin.login'))->toContain('/admin/login');
    expect(route('admin.login.submit'))->toContain('/admin/login');
    expect(route('admin.logon'))->toContain('/admin/logon');
    expect(route('admin.logon.submit'))->toContain('/admin/logon');
});
