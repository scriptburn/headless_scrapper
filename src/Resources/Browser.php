<?php

namespace ScriptBurn\HeadlessScrapper\Resources;

use Nesk\Rialto\Data\BasicResource;
use Nesk\Rialto\Data\JsFunction;

class Browser extends BasicResource
{
	public function newPage()
	{
		$page = parent::newPage();
		$pageFunction = JsFunction::createWithParameters(['request','page'])
			->body(file_get_contents(__DIR__."/callback.js"));

		$page->setRequestInterception(true);

		try {
			$page->tryCatch->on('request', $pageFunction);
		}
		catch (Node\Exception $exception)
		{
			print_r($exception->getMessage());
		}


		$logFunction = JsFunction::createWithParameters(['consoleObj'])
			->body( "consoleObj => console.log(consoleObj.text())");


		$page->on('console', $logFunction);



		return $page;
	}

}
