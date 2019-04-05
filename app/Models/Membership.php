<?php
/**
 * Model genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membership extends Model
{
	
    use SoftDeletes;
	
	protected $table = 'memberships';

	public $primaryKey = 'id';

	protected $fillable = ['id', 'membership_name', 'type', 'cost', 'membership_level'];
	
	protected $hidden = [
        
    ];

	protected $guarded = [];

	protected $dates = ['deleted_at'];

	public function upload_offer(){
	      return $this->hasMany('App\Models\Upload_Offer');
	}
}
