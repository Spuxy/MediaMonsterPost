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
		$hoursOfPost = PostOfficeHours::where('post_office_id', $idPost)->get();
		for ( $i = 0; $i < 7; $i++ ) {
			if ( $hoursOfPost[ $i ][ 'day' ] == $office[ $i ][ 'name' ] && $hoursOfPost[ $i ][ 'from' ] == $office[ $i ]->od_do->od && $hoursOfPost[ $i ][ 'do' ] == $office[ $i ]->od_do->do ) {
				return;
			}
			$hoursOfPost[ $i ][ 'day' ] = $office[ $i ][ 'name' ];
			$hoursOfPost[ $i ][ 'from' ] = $office[ $i ]->od_do->od;
			$hoursOfPost[ $i ][ 'to' ] = $office[ $i ]->od_do->do;
			$hoursOfPost[ $i ]->save();
		}
	}
}
