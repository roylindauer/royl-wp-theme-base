<?php

namespace Royl\WpThemeBase\Core\Ajax;
use Royl\WpThemeBase\Util;

/**
 * Base class for Ajax Classes
 *
 * @package     WpThemeBase
 * @subpackage  Core
 * @author      Roy Lindauer <hello@roylindauer.com>
 * @version     1.0
 */
class AjaxBase
{
    private $format = 'json';
    private $response;
    
    public function execute($method) {
        try{
            $this->$method();
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
	/**
	 * Handle response
	 */
	public function response() {
		try {
			$Output = new Output();
		
			if (!method_exists($this->format)) {
				throw new \Exception(sprintf('Output format, %s, does not exist', $this->format));
			}
			
			Output::{$this->format}($this->response);
			
		} catch (\Exception $e) {
			throw $e;
		}
	}
}
