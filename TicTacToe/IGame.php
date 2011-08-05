<?php
/**
 * Interface for Game class 
 * @author Shaked Klein Orbach
*/
interface IGame {
	/**
	 * Constructor 
	 * @param array $config
	 */
	public function __construct(array $config); 
	/**
	 * Returns JSON object
	 */
	public function __toString();
} 
