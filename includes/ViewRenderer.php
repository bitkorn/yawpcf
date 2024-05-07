<?php

namespace Bitkorn\Yawpcf;

#[\AllowDynamicProperties]
class ViewRenderer {
	private string $viewFile;

	/**
	 * @param string $viewFile
	 */
	public function __construct( string $viewFile ) {
		if ( ! file_exists( $viewFile ) ) {
			throw new \RuntimeException( __CLASS__ . '() - View file does not exist: ' . $viewFile );
		}
		$this->viewFile = $viewFile;
	}

	public function __set( $name, $value ) {
		$this->$name = $value;
	}

	public function render( array $vars = [] ): string {
		ob_start();
		foreach ( $vars as $key => $value ) {
			$this->$key = $value;
		}
		include $this->viewFile;

		return ob_get_clean();
	}
}
