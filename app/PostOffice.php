<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostOffice extends Model {

	protected $guarded = [];

	public function hours() {
		return $this->hasMany(PostOfficeHours::class);
	}

	public static function updatePostOffice($office) {
		$wtf = PostOffice::where('Address', $office->ADRESA)->first();
		$wtf->psc = $office->PSC;
		$wtf->Name = $office->NAZEV;
		$wtf->Address = $office->ADRESA;
		$wtf->X = $office->SOUR_X;
		$wtf->Y = $office->SOUR_Y;
		$wtf->City = $office->C_OBCE;
		$wtf->C_City = $office->OBEC;
		$wtf->save();

		return $wtf;
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
