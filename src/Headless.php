<?php

namespace ScriptBurn\HeadlessScrapper;
use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\AbstractEntryPoint;

require_once "PuppeteerProcessDelegate.php";
require_once __DIR__."/Resources/Browser.php";

class Headless extends Puppeteer
{
	protected $userOptions;
	protected $options = [
		'read_timeout' => 30,

		// Logs the output of Browser's console methods (console.log, console.debug, etc...) to the PHP logger
		'log_browser_console' => true,
	];
	public function puppeteer()
	{
		return $this->puppeteer;
	}
	public function browser()
	{
		return $this->browser;
	}

	/*const page = await browser.newPage();
		await page.setRequestInterception(true);
		await page.setUserAgent(userAgent);
		page.on('request', request => {
		  const requestUrl = request._url.split('?')[0].split('#')[0];
		  if (
		    blockedResourceTypes.indexOf(request.resourceType()) !== -1 ||
		    skippedResources.some(resource => requestUrl.indexOf(resource) !== -1)
		  ) {
		    request.abort();
		  } else {
		    request.continue();
		  }
		});
	*/

	public function __construct(array $userOptions = [])
	{
		$userOptions = !is_array($userOptions) ? [] : $userOptions;

		$defaultUserOptions = [
			'args' => [
				'--headless' => false,
				'--proxy-server' => '',
				'--window-size' => '1920x1080',
				'--no-sandbox' => true,
				'--disable-setuid-sandbox' => true,
				'--disable-dev-shm-usage' => true,
				'--disable-accelerated-2d-canvas' => true,
				'--disable-gpu' => true,
				'--allow-file-access-from-files' => true,
				'--start-maximized'=>true

			],
		];
		$this->userOptions = $this->parse_args($userOptions, $defaultUserOptions);
		if (empty($this->userOptions['args']['--proxy-server']))
		{
			unset($this->userOptions['args']['--proxy-server']);
		}
		if (!empty($this->userOptions['logger']) && $this->userOptions['logger'] instanceof LoggerInterface)
		{
			$this->checkPuppeteerVersion($this->userOptions['executable_path'] ?? 'node', $this->userOptions['logger']);
		}
		$reflector = new \ReflectionClass(parent::class);
		$dir = ((dirname($reflector->getFileName())));
		AbstractEntryPoint::__construct(
			$dir.'/PuppeteerConnectionDelegate.js',
			new PuppeteerProcessDelegate,
			$this->options,
			$this->userOptions
		);
	}

	function parse_args($args, $defaults = '')
	{
		if (is_object($args))
		{
			$r = get_object_vars($args);
		}
		elseif (is_array($args))
		{
			$r = &$args;
		}
		else
		{
			$r = [];
		}

		if (is_array($defaults))
		{
			return array_merge($defaults, $r);
		}

		return $r;
	}
	function connect($options = [])
	{
		$options = !is_array($options) ? [] : $options;

		$browserless_options = ['browserWSEndpoint' => [
			'ip' => '127.0.0.1',
			'port' => 9000,
			'token' => function_exists('env') ? env('BROWSERLESS_YOURTOKEN') : '',
		]];

		$this->options['browserless_options'] = $this->parse_args($options, $browserless_options);

		$connectStr = "ws://".@$this->options['browserless_options']['browserWSEndpoint']['ip'].":".@$this->options['browserless_options']['browserWSEndpoint']['port'];

		$args = ['token' => @$this->options['browserless_options']['browserWSEndpoint']['token']];
		foreach ($this->userOptions['args'] as $key => $value)
		{
			$args["--$key"] = $value;
		}
		$connectStr .= "?".http_build_query($args);

		return parent::connect(['browserWSEndpoint' => $connectStr]);
	}
}
