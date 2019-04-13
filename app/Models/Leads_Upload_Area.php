<?php
/**
 * Model genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leads_Upload_Area extends Model
{
    use SoftDeletes;
	
	protected $table = 'leads_upload_areas';

	protected $fillable = ['membership_id', 'file', 'file_name'];

	
	protected $hidden = [
        
    ];

	protected $guarded = [];

	protected $dates = ['deleted_at'];

	public function memberships(){
	     return $this->belongsTo('App\Models\Membership', 'membership_id');
	}
}
