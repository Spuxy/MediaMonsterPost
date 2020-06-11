<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostOffice extends Model
{
	protected $guarded = [];

	public function hours() {
		return $this->hasMany(PostOfficeHour::class);
    }

    public function isAlreadySaved($psc){
		if (count(PostOffice::where('PSC', $psc)->get())==0){
			return false;
		}
		return true;
    }
}
