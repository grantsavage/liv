<?php

namespace App;
use Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'location', 'bio',"avatar_url","settings"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','email','avatar_url'
    ];

    protected $appends = ['avatar'];

    public function posts() {
        return $this->hasMany(Post::class)->orderBy('created_at','desc');
    }

    public function getAvatarAttribute(){
        if ($this->avatar_url == null) {
            return '/images/default.png';
        } else {
            return $this->avatar_url;
        }
    }

    public function friendsOfMine() {
        return $this->belongsToMany('App\User','friends','user_id','friend_id');
    }

    public function friendOf() {
        return $this->belongsToMany('App\User','friends','friend_id','user_id');
    }

    public function friends() {
        return $this->friendsOfMine()->wherePivot('accepted',true)->get()->merge($this->friendOf()->wherePivot('accepted',true)->get());
    }

    public function friendRequests() {
        return $this->friendsOfMine()->wherePivot('accepted',false)->get();
        //->merge($this->friendOf()->wherePivot('accepted',false)->get());
    }

    public function friendRequestsPending() {
        return $this->friendOf()->wherePivot('accepted',false)->get();
    }

    public function hasFriendRequestPending(User $user) {
        return $this->friendRequestsPending()->where('id', $user->id)->count();
    }

    public function hasFriendRequestReceived(User $user) {
        return $this->friendRequests()->where('id', $user->id)->count();
    }

    public function addFriend(User $user) {
        $this->friendOf()->attach($user->id);
    }

    public function deleteFriend(User $user) {
        $this->friendOf()->detach($user->id);
        $this->friendsOfMine()->detach($user->id);
    }

    public function acceptFriendRequest(User $user) {
        $this->friendRequests()->where('id', $user->id)->first()->pivot->update([
                'accepted' => true
        ]);
    }

    public function declineFriendRequest(User $user) {
        $this->friendRequests()->where('id', $user->id)->first()->pivot->delete();
    }

    public function isFriendsWith(User $user) {
        return (bool) $this->friends()->where('id', $user->id)->count();
    }
}
