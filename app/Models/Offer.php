<?php
/**
 * Model genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{	

    use SoftDeletes;
	
	protected $table = 'offers';
	
	protected $fillable = ['id', 'membership_name', 'type', 'cost', 'membership_level'];
	
	public $primaryKey = 'id';
	
	protected $hidden = [
        
    ];

	protected $guarded = [];

	protected $dates = ['deleted_at'];


	public function memberships(){
	     return $this->belongsTo('App\Models\Membership', 'id');
	}
}
