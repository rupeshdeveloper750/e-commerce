<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRoles
{
    /**
     * Check if user has roles loaded
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id')
            ->wherePivot('model_type', static::class)
            ->withPivotValue('model_type', static::class);
    }

    /**
     * Check if user has direct permissions loaded
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'model_has_permissions', 'model_id', 'permission_id')
            ->wherePivot('model_type', static::class)
            ->withPivotValue('model_type', static::class);
    }

    /**
     * Check if model has a given role.
     */
    public function hasRole(string|array|Role $role): bool
    {
        if (is_string($role)) {
            return $this->roles->contains('slug', $role);
        }

        if ($role instanceof Role) {
            return $this->roles->contains('id', $role->id);
        }

        if (is_array($role)) {
            foreach ($role as $r) {
                if ($this->hasRole($r)) {
                    return true;
                }
            }
            return false;
        }

        return false;
    }

    /**
     * Check if model has permission (via roles or directly).
     */
    public function hasPermissionTo(string|Permission $permission): bool
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->first();
            if (!$permission) {
                return false;
            }
        }

        // Direct permission check
        if ($this->permissions->contains('id', $permission->id)) {
            return true;
        }

        // Check via roles
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('id', $permission->id)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Assign a role to the model.
     */
    public function assignRole(string|Role $role): void
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        }

        $this->roles()->syncWithoutDetaching([$role->id]);
    }

    /**
     * Remove a role from the model.
     */
    public function removeRole(string|Role $role): void
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->first();
        }

        if ($role) {
            $this->roles()->detach($role->id);
        }
    }

    /**
     * Give a direct permission to the model.
     */
    public function givePermissionTo(string|Permission $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->firstOrFail();
        }

        $this->permissions()->syncWithoutDetaching([$permission->id]);
    }

    /**
     * Revoke a direct permission from the model.
     */
    public function revokePermissionTo(string|Permission $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->first();
        }

        if ($permission) {
            $this->permissions()->detach($permission->id);
        }
    }
}
