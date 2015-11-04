<?php
/**
 * @package    artless Hip
 * @author     Christian Glingener <glingener.christian@gmail.com>
 * @version    1.0.0
 * @copyright  2014 artlessthemes.com
 * @link       http://artlessthemes.com/
 */


class Layouts_Integration_Autoloader {

	private static $instance;

	protected $paths = array();
	protected $prefixes = array();

	protected function __construct() {
		spl_autoload_register( array( $this, 'autoload' ) );
	}

	public static function getInstance() {
		if( self::$instance === null )
			self::$instance = new self;

		return self::$instance;
	}


	public function addPath( $path ) {

		// check if path is readable
		if( is_readable( $path ) ) {
			array_push( $this->paths, $path );
			return true;
		}

		return false;
	}

	public function addPaths( array $paths ) {
		// run this->addPath for each value
		foreach( $paths as $path ) {
			$this->addPath( $path );
		}
	}

	public function getPaths() {
		return $this->paths;
	}

	public function addPrefix( $prefix ) {
		array_push( $this->prefixes, $prefix );
		return $this;
	}

	public function getPrefixes() {
		return $this->prefixes;
	}

	public function autoload( $class ) {

		if( class_exists( $class ) )
			return true;

		$self = self::getInstance();

		foreach( $self->prefixes as $prefix ) {
			if( $class = preg_replace( '#^'.$prefix.'_#', '', $class ) ) {
				break;
			}
		}

		// explode class by _
		$explode_class = explode( '_' , $class );

		// get class filename
		$class_filename = array_pop( $explode_class );
		$class_filename = strtolower( $class_filename ) . '.php';

		// get class path
		$class_path = '';
		foreach( $explode_class as $path ) {
			$class_path .= strtolower( $path ) . '/';
		}

		$file = $class_path . $class_filename;

		// check for file in path
		foreach( $self->getPaths() as $path ) {

			$file_tmp = $file;

			do {
				if( is_file( $path . '/' . $file_tmp ) ) {
					return include_once( $path . '/' . $file_tmp ) ;
				}

				// allows to use underscores in class filename instead of subfolders
				$file_tmp = preg_replace( '/(\/(?!.*\/))/', '_', $file_tmp );

			} while( strpos( $file_tmp, '/' ) !== false );

		}
		return false;
	}

}