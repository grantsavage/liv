<?php

namespace App;
use Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use Notifiable, Searchable;

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
        'password', 'remember_token','email','avatar_url','settings','created_at','updated_at'
    ];

    /*
     * Append the avatar url to the user
     */
    protected $appends = ['avatar'];

    /*
     * Return the collection of posts that belong to the user
     */
    public function posts() {
        return $this->hasMany(Post::class)->notReply()->orderBy('created_at','desc');
    }

    /*
     * Determine whether to serve the default avatar or the user uploaded avatar
     */
    public function getAvatarAttribute(){
        if ($this->avatar_url == null) {
            return '/images/default.png';
        } else {
            return $this->avatar_url;
        }
    }

    /*
     * Return the friends of the current user
     */
    public function friendsOfMine() {
        return $this->belongsToMany('App\User','friends','user_id','friend_id');
    }

    /*
     * 
     */
    public function friendOf() {
        return $this->belongsToMany('App\User','friends','friend_id','user_id');
    }

    /*
     * Returns a collection of friends of the user
     */
    public function friends() {
        return $this->friendsOfMine()->wherePivot('accepted',true)->get()->merge($this->friendOf()->wherePivot('accepted',true)->get());
    }

    /*
     * Returns friend requests
     */
    public function friendRequests() {
        return $this->friendsOfMine()->wherePivot('accepted',false)->get()
        ->merge($this->friendOf()->wherePivot('accepted',false)->get());
    }

    /*
     * Finds friend requests that are pending
     */
    public function friendRequestsPending() {
        return $this->friendOf()->wherePivot('accepted',false)->get();
    }

    /*
     * Determines if friend requests are pending
     */
    public function hasFriendRequestPending(User $user) {
        $value = $this->friendRequestsPending()->where('id', $user->id)->count();
        $this->requestPending = $value;
        return $value;
    }

    /*
     * Determines if friend request is received
     */
    public function hasFriendRequestReceived(User $user) {
        $value = $this->friendRequests()->where('id', $user->id)->count();
        $this->waitingAccept = $value;
        return $value;
    }

    /*
     * Function for adding a friend
     */
    public function addFriend(User $user) {
        $this->friendOf()->attach($user->id);
    }

    /*
     * Removing a friend
     */
    public function deleteFriend(User $user) {
        $this->friendOf()->detach($user->id);
        $this->friendsOfMine()->detach($user->id);
    }

    /*
     * Accept friend request
     */
    public function acceptFriendRequest(User $user) {
        $this->friendRequests()->where('id', $user->id)->first()->pivot->update([
                'accepted' => true
        ]);
    }

    /*
     * Decline a request
     */
    public function declineFriendRequest(User $user) {
        $this->friendRequests()->where('id', $user->id)->first()->pivot->delete();
    }

    /*
     * Determines if friend is friend with another user
     */
    public function isFriendsWith(User $user) {
        $value = (bool) $this->friends()->where('id', $user->id)->count();
        $this->isFriend = $value;
        return $value;
    }
}
