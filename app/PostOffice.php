<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostOffice extends Model {

	protected $guarded = [];

	public function hours() {
		return $this->hasMany(PostOfficeHour::class);
	}

	public function isAlreadySaved($address) {
		if ( $this->getAddress($address) == 0 ) {
			return false;
		}

		return true;
	}

	public function getAddress($address) {
		return count(PostOffice::where('Address', $address)->get());
	}
}
