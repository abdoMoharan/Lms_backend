<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'first_name',
        'last_name',
        'phone',
        'user_type',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }
    public function setPasswordAttribute($password)
    {
        if ($password) {
            $this->attributes['password'] = Hash::make($password);
        }
    }


public function scopeFilter(Builder $builder, $filters)
    {
        $builder->when(isset($filters['query']) && $filters['query'] !== '', function ($builder) use ($filters) {
            $builder->where('first_name', 'like', "%{$filters['query']}%")
                ->orWhere('last_name', 'like', "%{$filters['query']}%")
                ->orWhere('email', 'like', "%{$filters['query']}%")
                ->orWhere('phone', 'like', "%{$filters['query']}%");
        });

        $builder->when(isset($filters['email']) && $filters['email'] !== '', function ($builder) use ($filters) {
            $builder->where('email', 'like', "%{$filters['email']}%");
        });

        $builder->when(isset($filters['phone']) && $filters['phone'] !== '', function ($builder) use ($filters) {
            $builder->where('phone', 'like', "%{$filters['phone']}%");
        });
        $builder->when(isset($filters['type']) && $filters['type'] !== '', function ($builder) use ($filters) {
            $builder->where('user_type', $filters['type']);
        });

        $builder->when(isset($filters['status']), function ($builder) use ($filters) {
            $statusValue = $filters['status'] == '0' ? 0 : $filters['status'];
            $builder->where('status', $statusValue);
        });

        $builder->when(!empty($filters['start_date']) && !empty($filters['end_date']), function ($builder) use ($filters) {
            $builder->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
        });
    }



   public static function getAllDeleted()
    {
        return self::onlyTrashed()->get();
    }

    // Restore a Deleted Record
    public static function restoreSoft($id)
    {
        $model = self::onlyTrashed()->find($id);
        if ($model) {
            $model->restore();
        }
        return $model;
    }

    // Force Delete a Record
    public static function forceDeleteById($id)
    {
        $model = self::onlyTrashed()->find($id);
        if ($model) {
            $model->forceDelete();
        }
        return $model;
    }
}
