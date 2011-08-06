<?php
require_once("IGame.php");
require_once("GameException.php");
/**
 * Board game structure
 * @author Shaked Klein Orbach
*/
abstract class Game implements IGame {
	/**
	*	@var array game board
	*/
	protected $_gameBoard;
	/**
	 *	@var array error messages
	 */
	protected $_errorMessage = array();
	/**
	 *	@var array computer move
	 */
	protected $_move = array();
	/**
	 *	@var const players
	 */
	const PLAYER 	= 1;
	const COMPUTER 	= 2;
	/**
	 * 	@var const game status
	 */
	const DRAW			= 400;
	const GOING_ON 		= 401;
	/**
	*	@var const allowd move
	*/
	const ALLOWD_MOVE 	= 2;
	
	/**
	*	Constructor (Send game params on init or ajax calls )
	*	@param array $config, contains: (*) TicTacToe::CONST "player",(*) array "move",(o) array "gameBoard"
	*	@throws GameException
	*/
	public function __construct(array $config){
		if (!isset($config['player'])){
			throw new GameException("You must assign player");
		}
	
		//if player turn he must send his move
		if (!isset($config['move']) && self::PLAYER == $config['player']){
			throw new GameException("Player move is not allowed, please follow the rules");
		}
	
		//Do we continue or starting new game?
		if (isset($config['gameBoard'])){
		$this->setGameBoard($config['gameBoard']);
		} else {
		$this->clearBoard();
		}
	
		$this->_currentPlayer = $config['player'];
	
		$this->play($config['move']); 
	}

	/**
	 *	Computer plays, using MinMax algo to know what is the best move to take 
	 *	@returns boolean
	 */
	protected function computerTurn(){ 
		$getMinMaxResult = new MinMax($this); 
		if ($getMinMaxResult->move) {
			return $this->_move = $getMinMaxResult->move;
		}
		return false; 
	}
	
	/**
	 *	Toggle between players
	 *	@returns void
	 */
	public function setPlayer(){
		$this->_currentPlayer = (self::PLAYER == $this->_currentPlayer)? self::COMPUTER:self::PLAYER;
	}
	
	/**
	*	Check if game board is full
	*	@param array $gameBoard
	*	@returns array|true;
	*/
	protected static function isBoardFull(array $gameBoard){
		foreach($gameBoard as $pos1=>$columns){
			foreach($columns as $pos2=>$player){
				if (false !== $player){
					continue ; 	
				}
				$blankPositions[] = array($pos1,$pos2);
			}
		}
	
		return (!empty($blankPositions))? $blankPositions:true;
	}
	
	
	/**
	 *	Set player move
	 * 	@param array $move
	 *	@param boolean $isEmpty - true returns position to blank spot
	 *	@returns boolean
	 *	@throws GameExcpetion
	 */
	public function setPosition(array $move,$isEmpty=false){
		if (false === self::isAlreadyTaken($move,$this->_gameBoard) || $isEmpty){
			return $this->_gameBoard[$move[0]][$move[1]] = ($isEmpty)? false:$this->_currentPlayer;
		}
		
		throw new GameException("Unallowd move ({$move[0]},{$move[1]}), code need to be fixed");
	}
		
	/**
	 *	Clear game board 
	 *	@returns void 
	*/
	protected function clearBoard(){
		$board = array(); 
		for ($i = 0; $i<static::BOARD_ROWS; $i++){	
			$board[$i] = array(); 
			for ($j = 0; $j<static::BOARD_COLS; $j++){
				array_push($board[$i],false); 
			}
		} 
		$this->_gameBoard = $board; 
	}
	
	
	
	/**
	*	Set game board by request
	*	@param array $gameBoard
	*	@returns void
	*/
	protected function setGameBoard($gameBoard){
		foreach($gameBoard as $pos1=>$columns){
			foreach($columns as $pos2=>$player){
				$this->_gameBoard[$pos1][$pos2] = (!$player)? false:$player;
			}
		}
	}
	
	/**
	* Get current game board
	* @returns array
	*/
	public function getGameBoard(){
		return $this->_gameBoard;
	}
	
	/**
	*	Check if player move is allowd
	*	@param array $move
	*	@returns boolean
	*/
	protected static function isAllowdMove(array $move){
		if (!empty($move) && self::ALLOWD_MOVE == count($move)){
			return true;
		}
		return false;
	}
	
	/**
	 *	Check if this move is allowd and already taken
	 *	@param array $move
	 *	@param array $gameBoard
	 *	@throws GameException
	 *	@returns array|false
	 */
	private static function isAlreadyTaken(array $move,array $gameBoard){
		if (self::isAllowdMove($move)){
			return $gameBoard[$move[0]][$move[1]];
		}
	
		throw new GameException('Unallowd move taken');
	}
	
	/**
	 *	Use __toString magic to return json answers for ajax support
	 *	@returns (json) string
	 */
	public function __toString(){
		$res = array();
		if (!empty($this->_errorMessage)){
			$res['errorMessage'] = $this->_errorMessage;
		}
		if (!empty($this->_move)){
			$res['move'] = $this->_move;
		}
		return json_encode($res);
	}
	
	/**
	 * Defines how game play executes 
	 * @param array $move - define payer's move 
	 * @return void 
	 */
	abstract protected function play(array $move = array());
	
	/**
	 * Defines game rules, How to: 
	 * 	1. Win\Lose
	 * 	2. Draw
	 * 	3. Keep playing 
	 */
	abstract protected function isGameOver();

} 