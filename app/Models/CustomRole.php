<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as SpatieRole;

class CustomRole extends SpatieRole
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * Core system roles that form the functional backbone of BAMA+.
     */
    public const CORE_ROLES = [
        'God Admin',
        'super-admin',
        'super admin',
        'sales-director',
        'sales-executive',
        'pmo-manager',
        'finance-officer',
        'hcd-manager',
        'hsse-officer',
        'procurement-officer',
        'asset-manager',
    ];

    /**
     * Check if a given role name is a protected core system role.
     */
    public static function isCoreRole(?string $name): bool
    {
        if (! $name) {
            return false;
        }

        $normalized = strtolower(trim($name));
        $coreList = array_map(fn ($r) => strtolower(trim($r)), self::CORE_ROLES);

        return in_array($normalized, $coreList, true);
    }

    /**
     * Determine if this role instance is a protected core role.
     */
    public function isCore(): bool
    {
        return self::isCoreRole($this->name);
    }

    /**
     * Determine if this role is the root God Admin.
     */
    public function isGodAdmin(): bool
    {
        return strtolower(trim((string) $this->name)) === 'god admin';
    }

    /**
     * Determine if the role name can be edited.
     * Core system roles can NEVER have their names renamed/edited.
     */
    public function canEditName(): bool
    {
        return ! $this->isCore();
    }

    /**
     * Determine if the role can be deleted by the given user.
     * Rule:
     * - God Admin role can NEVER be deleted.
     * - Core roles can ONLY be deleted by a user with role 'God Admin'.
     * - Custom roles can be deleted normally.
     */
    public function canBeDeletedBy(?User $user): bool
    {
        if ($this->isGodAdmin()) {
            return false;
        }

        if ($this->isCore()) {
            return $user !== null && $user->hasRole('God Admin');
        }

        return true;
    }
}
