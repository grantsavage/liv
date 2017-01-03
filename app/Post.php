<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['body','image_url'];

    protected $appends = ['likeCount', 'likedByCurrentUser'];

    /*
     * Get the latest posts first
     */
    public function scopeLatestFirst($query){
    	return $query->orderBy('created_at','desc');
    }

    /*
     * Attach a like count
     */
    public function getLikeCountAttribute(){
    	return $this->likes->count();
    }

    /*
     * Attach liked by current user attribute
     */
    public function getLikedByCurrentUserAttribute(){
    	return $this->likes->where('user_id', Auth::user()->id)->count() === 1;
    }

    /*
     * The user for which the post belongs to
     */
    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function likes() {
    	return $this->morphMany(Like::class, 'likeable');
    }

    public function photos() {
        return $this->hasMany('App\Media');
    }
}
