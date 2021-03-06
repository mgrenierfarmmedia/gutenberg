<?php
/**
 * Blocks API: WP_Block_Patterns_Registry class
 *
 * @package Gutenberg
 */

/**
 * Class used for interacting with patterns.
 */
final class WP_Block_Patterns_Registry {
	/**
	 * Registered patterns array.
	 *
	 * @var array
	 */
	private $registered_patterns = array();

	/**
	 * Container for the main instance of the class.
	 *
	 * @var WP_Block_Patterns_Registry|null
	 */
	private static $instance = null;

	/**
	 * Registers a pattern.
	 *
	 * @param string $pattern_name       Pattern name including namespace.
	 * @param array  $pattern_properties Array containing the properties of the pattern: label, content.
	 * @return boolean True if the pattern was registered with success and false otherwise.
	 */
	public function register( $pattern_name, $pattern_properties ) {
		if ( ! isset( $pattern_name ) || ! is_string( $pattern_name ) ) {
			$message = __( 'Pattern name must be a string.', 'gutenberg' );
			_doing_it_wrong( __METHOD__, $message, '7.8.0' );
			return false;
		}

		$this->registered_patterns[ $pattern_name ] = array_merge(
			$pattern_properties,
			array( 'name' => $pattern_name )
		);

		return true;
	}

	/**
	 * Unregisters a pattern.
	 *
	 * @param string $pattern_name     Pattern name including namespace.
	 * @return boolean True if the pattern was unregistered with success and false otherwise.
	 */
	public function unregister( $pattern_name ) {
		if ( ! $this->is_registered( $pattern_name ) ) {
			/* translators: 1: Pattern name. */
			$message = sprintf( __( 'Pattern "%1$s" not found.', 'gutenberg' ), $pattern_name );
			_doing_it_wrong( __METHOD__, $message, '7.8.0' );
			return false;
		}

		unset( $this->registered_patterns[ $pattern_name ] );

		return true;
	}

	/**
	 * Retrieves an array containing the properties of a registered pattern.
	 *
	 * @param string $pattern_name       Pattern name including namespace.
	 * @return array Registered pattern properties.
	 */
	public function get_registered( $pattern_name ) {
		if ( ! $this->is_registered( $pattern_name ) ) {
			return null;
		}

		return $this->registered_patterns[ $pattern_name ];
	}

	/**
	 * Retrieves all registered patterns.
	 *
	 * @return array Array of arrays containing the registered patterns properties,
	 *               and per style.
	 */
	public function get_all_registered() {
		return array_values( $this->registered_patterns );
	}

	/**
	 * Checks if a pattern is registered.
	 *
	 * @param string $pattern_name       Pattern name including namespace.
	 * @return bool True if the pattern is registered, false otherwise.
	 */
	public function is_registered( $pattern_name ) {
		return isset( $this->registered_patterns[ $pattern_name ] );
	}

	/**
	 * Utility method to retrieve the main instance of the class.
	 *
	 * The instance will be created if it does not exist yet.
	 *
	 * @since 5.3.0
	 *
	 * @return WP_Block_Patterns_Registry The main instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

/**
 * Registers a new pattern.
 *
 * @param string $pattern_name       Pattern name including namespace.
 * @param array  $pattern_properties Array containing the properties of the pattern.
 *
 * @return boolean True if the pattern was registered with success and false otherwise.
 */
function register_block_pattern( $pattern_name, $pattern_properties ) {
	return WP_Block_Patterns_Registry::get_instance()->register( $pattern_name, $pattern_properties );
}

/**
 * Unregisters a pattern.
 *
 * @param string $pattern_name       Pattern name including namespace.
 *
 * @return boolean True if the pattern was unregistered with success and false otherwise.
 */
function unregister_block_pattern( $pattern_name ) {
	return WP_Block_Patterns_Registry::get_instance()->unregister( $pattern_name );
}
