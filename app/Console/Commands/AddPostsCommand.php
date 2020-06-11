<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PostOffice;

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

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle() {
		$payload = [];
		// url ('http://napostu.ceskaposta.cz/vystupy/balikovny.xml');
		$xml = simplexml_load_file(config('postoffice.api'));
		for ( $i = 0; $i < count($xml) - 1; $i++ ) {
			$payload[ $i ] = $xml->row[ $i ];
		}
		$this->process($payload);
	}

	/**
	 * Saves offices to DB
	 * @param $payload
	 */
	public function process($payload) {
		collect($payload)->map(function($mrd) {
			$post = new PostOffice();
			if ( $post->isAlreadySaved($mrd->PSC) ) {
				$this->info($mrd->NAZEV . ' is already saved');

				return;
			}
			$post->psc = $mrd->PSC;
			$post->name = $mrd->NAZEV;
			$post->address = $mrd->ADRESA;
			$post->X = $mrd->SOUR_X;
			$post->Y = $mrd->SOUR_Y;
			$post->City = $mrd->OBEC;
			$post->C_City = $mrd->C_OBCE;
			$post->save();
			$this->warn($mrd->NAZEV . ' Has been added to DB');
		});
	}

}
