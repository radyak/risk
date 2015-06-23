<?php namespace Game\Model;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model {

    protected $fillable = ['status'];
    
    
    public function invitedBy()
    {
        return $this->belongsTo('Game\User', 'invited_by_user_id', 'id');
    }
    
    
    public function user()
    {
        return $this->belongsTo('Game\User');
    }
    
    
    public function match()
    {
        return $this->belongsTo('Game\Model\Match');
    }
}