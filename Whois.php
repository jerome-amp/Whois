<?php

class Whois
{
	public $data = '';
	
	private $whois = # https://www.iana.org/domains/root/db #
	[
		'fr' => 'whois.nic.fr',
		'io' => 'whois.nic.io',
		'pf' => 'whois.registry.pf',
		'com' => 'whois.verisign-grs.com'
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
		if($handle = fsockopen($whois, 43))
		{
			fputs($handle, $domain."\r\n");
			
			$this->data = stream_get_contents($handle);
			
			fclose($handle);
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
		
		preg_match('#('.implode('|', $patterns).')#i', $this->data, $match);
		
		return isset($match[1]);
	}
}
