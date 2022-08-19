<?php

class Whois
{
	public $data = '';
	
	private $whois = # https://www.iana.org/domains/root/db #
	[
		'com' => 'whois.verisign-grs.com',
		'fr' => 'whois.nic.fr',
		'io' => 'whois.nic.io',
		'pf' => 'whois.registry.pf'
	];
	
	public function __construct(string $domain)
	{
		$tld = substr(strrchr($domain, '.'), 1);
		
		if($whois = $this->whois[$tld])
		{
			$this->request($whois, $domain);
		}
	}
	
	private function request(string $whois, string $domain): void
	{
		if($fp = fsockopen($whois, 43))
		{
			fputs($fp, $domain."\r\n");
			
			$this->data = stream_get_contents($fp);
			
			fclose($fp);
		}
	}
	
	public function available(): bool
	{
		$patterns =
		[
			'is free',
			'no match',
			'no found',
			'not find',
			'not found',
			'not exist',
			'not found',
			'not known',
			'no entries',
			'is available',
			'no data found',
			'nothing found',
			'no such domain',
			'not registered',
			'invalid domain',
			'domain unknown',
			'no object found',
			'not been registered',
		];
		
		preg_match('#('.implode('|', $patterns).')#i', $this->response, $match);
		
		return isset($match[1]);
	}
}
