<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostOfficeHours extends Model {

	protected $guarded = [];

	public function office() {
		return $this->belongsTo(PostOffice::class);
	}

	public static function insertToPostOffice($office, $entity) {

		for ( $i = 0; $i < 7; $i++ ) {
			$postHours = new static();
			$postHours[ 'post_office_id' ] = $entity;
			$postHours[ 'day' ] = $office[ $i ][ 'name' ];
			$postHours[ 'from' ] = $office[ $i ]->od_do->od;
			$postHours[ 'to' ] = $office[ $i ]->od_do->do;
			$postHours->save();
		}
	}
	public static function compare($office, $idPost) {

		if (!$idPost) {
			return false;
		}
		$hoursOfPost = PostOfficeHours::where('post_office_id', $idPost)->get();

		for ( $i = 0; $i < 7; $i++ ) {
			if ( $hoursOfPost[ $i ][ 'day' ] == $office[ $i ][ 'name' ] && mb_substr($hoursOfPost[ $i ][ 'from' ],0,5) == $office[ $i ]->od_do->od && mb_substr($hoursOfPost[ $i ][ 'to' ],0,5) == $office[ $i ]->od_do->do ) {
				continue;
			}
			$hoursOfPost[ $i ][ 'day' ] = $office[ $i ][ 'name' ];
			$hoursOfPost[ $i ][ 'from' ] = $office[ $i ]->od_do->od;
			$hoursOfPost[ $i ][ 'to' ] = $office[ $i ]->od_do->do;
			$hoursOfPost[ $i ]->save();
		}
		return true;
	}
}
