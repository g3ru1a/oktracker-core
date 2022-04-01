<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function reports(){
        return $this->hasMany(Report::class, 'assignee_id');
    }

    public function unfinishedReports()
    {
        return $this->hasMany(Report::class, 'assignee_id')->whereNotIn('status', [Report::STATUS_COMPLETED, Report::STATUS_DROPPED]);
    }

    public function collections(){
        return $this->hasMany(Collection::class)->whereNull("deleted_at");
    }

    public function badges()
    {
        return $this->belongsToMany(SocialBadges::class);
    }

    public function activity(){
        return $this->hasMany(SocialActivity::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'follow_id', 'user_id')->whereNull("deleted_at");
    }

    // Get all users we are following
    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follow_id')->whereNull("deleted_at");
    }

    public function liked(){
        return $this->hasMany(Like::class);
    }
}
