# Whois

```

require 'Whois.php';

$whois = new Whois('rickastley.com');

if(!$whois->available())
{
	echo $whois->data;
}

```

## Author

**Jérôme Taillandier**

## License

This project is licensed under the WTFPL License - see the [LICENSE.md](LICENSE.md) file for details
