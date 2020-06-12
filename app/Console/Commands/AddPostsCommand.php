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

	public function __construct(PostOfficeHours $postOfficeHours) {
		parent::__construct();
		$this->postOfficeHours = $postOfficeHours;
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
			$post = new PostOffice();
			$postOffice = PostOffice::where('Address', $office->ADRESA)->first();

			if ( $postOffice == null ) {
				$post->psc = $office->PSC;
				$post->name = $office->NAZEV;
				$post->address = $office->ADRESA;
				$post->X = $office->SOUR_X;
				$post->Y = $office->SOUR_Y;
				$post->City = $office->OBEC;
				$post->C_City = $office->C_OBCE;
				$post->save();
				PostOfficeHours::insertToPostOffice($office->OTEV_DOBY->den, $post->getAttributes()[ 'id' ]);
				$this->warn($office->NAZEV . ' Has been added to DB');
				return;
			}

			if (
				 $postOffice[ 'PSC' ] == $office->PSC &&
				 $postOffice[ 'Name' ] == $office->NAZEV &&
				 $postOffice[ 'Address' ] == $office->ADRESA &&
				 $postOffice[ 'X' ] == $office->SOUR_X &&
				 $postOffice[ 'Y' ] == $office->SOUR_Y &&
				 $postOffice[ 'City' ] == $office->C_OBCE &&
				 $postOffice[ 'C_City' ] == $office->OBEC
			)
			{
				$this->info($postOffice[ 'Name' ] . ' has not changed');
			}

			$post::updatePostOffice($office);
			PostOfficeHours::compare($office->OTEV_DOBY->den, $postOffice[ 'id' ]);
			$this->warn($postOffice->Name . ' Has been updated');

		});
	}

}
