<?php

namespace Oyova\WpSupport;

use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as WhoopsRun;
use Whoops\Util\Misc as WhoopsMisc;

class ErrorHandler {

	public function __construct() {
		if (
			defined( 'WP_DEBUG' ) &&
			WP_DEBUG &&
			(
				! defined( 'WP_DEBUG_DISPLAY' ) ||
				WP_DEBUG_DISPLAY
			)
		) {
			$whoops = new WhoopsRun();
			$whoops->pushHandler(
				WhoopsMisc::isAjaxRequest() ?
				new JsonResponseHandler() :
				(
					WhoopsMisc::isCommandLine() ?
					new PlainTextHandler() :
					new PrettyPageHandler()
				)
			);
			$whoops->silenceErrorsInPaths( '/./', E_DEPRECATED );
			$whoops->register();
		}
	}
}
