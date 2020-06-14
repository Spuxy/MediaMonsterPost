<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostOffice extends Model {

	protected $guarded = [];

	public function hours() {
		return $this->hasMany(PostOfficeHours::class);
	}

	public static function updatePostOffice($office) {
		$post = PostOffice::where('Address', $office->ADRESA)->first();
		$post->psc = $office->PSC;
		$post->Name = $office->NAZEV;
		$post->Address = $office->ADRESA;
		$post->X = $office->SOUR_X;
		$post->Y = $office->SOUR_Y;
		$post->City = $office->OBEC;
		$post->C_City = $office->C_OBCE;
		$post->save();

		return $post;
	}

	public function getAddress($address) {
		return count(PostOffice::where('Address', $address)->get());
	}

	public function newPostOffice($office) {
		$post = new static();
		$post->psc = $office->PSC;
		$post->name = $office->NAZEV;
		$post->address = $office->ADRESA;
		$post->X = $office->SOUR_X;
		$post->Y = $office->SOUR_Y;
		$post->City = $office->OBEC;
		$post->C_City = $office->C_OBCE;
		$post->save();
		PostOfficeHours::insertToPostOffice($office->OTEV_DOBY->den, $post->getAttributes()[ 'id' ]);
	}
}
