<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // استيراد النوع
use Spatie\Permission\Models\Permission as SpatiePermission;
class CustomPermission extends  SpatiePermission
{
    use HasFactory;

    protected $fillable = ['name', 'guard_name', 'group_name','trans_name'];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(SpatieRole::class, 'role_has_permissions', 'permission_id', 'role_id');
    }
}
