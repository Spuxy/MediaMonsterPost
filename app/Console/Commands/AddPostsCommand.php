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
		collect($payload)->map(function($office) {
			$post = new PostOffice();
			if ( $post->isAlreadySaved($office->PSC) ) {
				$this->info($office->NAZEV . ' is already saved');

				return;
			}
			$post->psc = $office->PSC;
			$post->name = $office->NAZEV;
			$post->address = $office->ADRESA;
			$post->X = $office->SOUR_X;
			$post->Y = $office->SOUR_Y;
			$post->City = $office->OBEC;
			$post->C_City = $office->C_OBCE;
			$post->save();
			$this->warn($office->NAZEV . ' Has been added to DB');
		});
	}

}
