<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use \Laravel\Sanctum\HasApiTokens;
    use \Illuminate\Notifications\Notifiable;
    use \Starmoozie\CRUD\app\Models\Traits\CrudTrait;
    use \ALajusticia\AuthTracker\Traits\AuthTracking;
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use \Starmoozie\LaravelMenuPermission\app\Traits\GenerateId;
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'role_id',
        'group_ids'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'group_ids' => 'array'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function role()
    {
        return $this->belongsTo(
            \Starmoozie\LaravelMenuPermission\app\Models\Role::class,
            'role_id',
            'id'
        );
    }

    public function groups()
    {
        return $this->belongsToJson(
            Group::class,
            'group_ids',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Select by role
     */
    public function scopeSelectByRole($query, $value)
    {
        return $query->whereRoleId($value);
    }

    public function scopeJoinMenuPermission($query)
    {
        return $query->leftJoin('menu as m', 'menu_permission.menu_id', 'm.id')
            ->leftJoin('permission as p', 'menu_permission.permission_id', 'p.id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getMenuAttribute()
    {
        $user = \starmoozie_user();
        $user->load(['role:id,options']);

        return $user
            ->role
            ->menuPermission()
            ->joinMenuPermission()
            ->select(['m.id', 'm.name', 'route', 'lft', 'rgt', 'depth', 'parent_id', 'p.name as permission'])
            ->orderBy('lft')
            ->get();
    }

    // public function getSidebarAttribute()
    // {
    //     $menu = $this->menu;

    //     if ($menu->count()) {
    //         foreach ($menu as $k => $menu_item) {
    //             $menu_item->children = collect([]);

    //             foreach ($menu as $i => $menu_subitem) {
    //                 if ($menu_subitem->parent_id == $menu_item->id) {
    //                     $menu_item->children->push($menu_subitem);

    //                     // remove the subitem for the first level
    //                     $menu = $menu->reject(function ($item) use ($menu_subitem) {
    //                         return $item->id == $menu_subitem->id;
    //                     });
    //                 }
    //             }
    //         }
    //     }

    //     return $menu;
    // }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
