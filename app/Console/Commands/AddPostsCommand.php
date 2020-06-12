<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PostOffice;
use App\PostOfficeHours;

class AddPostsCommand extends Command {

	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'post:fetch';

	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Fetch and add posts in XML format to DB';
	/**
	 * @var PostOfficeHours
	 */
	private $postOfficeHours;
	/**
	 * @var PostOffice
	 */
	private $postOffice;

	public function __construct(PostOfficeHours $postOfficeHours, PostOffice $postOffice) {
		parent::__construct();
		$this->postOfficeHours = $postOfficeHours;
		$this->postOffice = $postOffice;
	}

	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle() {
		// url ('http://napostu.ceskaposta.cz/vystupy/balikovny.xml');
		$xml = simplexml_load_file(config('postoffice.api'));
		$this->process($this->payload($xml));
	}

	/**
	 * Payload iterate to array to wrap values into collection
	 * @param $xml
	 * @return array
	 */
	public function payload($xml) {
		$payload = [];
		for ( $i = 0; $i < count($xml) - 1; $i++ ) {
			$payload[ $i ] = $xml->row[ $i ];
		}

		return $payload;
	}

	/**
	 * Saves offices to DB
	 * @param $payload
	 */
	public function process($payload) {
		collect($payload)->map(function($office) {

			$postOffice = PostOffice::where('Address', $office->ADRESA)->first();

			if ( $postOffice == null ) {
				$this->postOffice->newPostOffice($office);
				$this->warn($office->NAZEV . ' Has been added to DB');

				return;
			}

			if (
				$postOffice[ 'PSC' ] == $office->PSC &&
				$postOffice[ 'Name' ] == $office->NAZEV &&
				$postOffice[ 'Address' ] == $office->ADRESA &&
				$postOffice[ 'X' ] == $office->SOUR_X &&
				$postOffice[ 'Y' ] == $office->SOUR_Y &&
				$postOffice[ 'City' ] == $office->OBEC &&
				$postOffice[ 'C_City' ] == $office->C_OBCE
			) {
				$this->info($postOffice[ 'Name' ] . ' has not changed');
			} else {
				PostOffice::updatePostOffice($office);
				$this->warn('id: '.$postOffice[ 'id' ] . ' Has been updated');
			}

			PostOfficeHours::compare($office->OTEV_DOBY->den, $postOffice[ 'id' ]);
		});
	}

}
