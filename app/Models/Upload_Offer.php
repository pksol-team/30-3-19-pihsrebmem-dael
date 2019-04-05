<?php
/**
 * Model genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Upload_Offer extends Model
{
    use SoftDeletes;
	
	protected $table = 'upload_offers';

	 protected $fillable = ['membership_id', 'file'];
	
	protected $hidden = [
        
    ];

	protected $guarded = [];

	protected $dates = ['deleted_at'];

	public function memberships(){
	     return $this->belongsTo('App\Models\Membership', 'membership_id');
	}
}
