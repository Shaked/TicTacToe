<?php
require_once("Game.php");
require_once("MinMax.php");
/**
 * TicTacToe game. 
 * @author Shaked Klein Orbach
*/
class TicTacToe extends Game { 	
	/**
	 *	@var const game board defenition 
	 */
	const BOARD_ROWS = 3; 
	const BOARD_COLS = 3; 
	
	/**
	 *	Just play 
	 *	@param array $move
	 *	@returns void 
	 */
	protected function play(array $move = array()){ 
		//check if game is over (winner\draw\going on)
		if (Game::GOING_ON != ($res = $this->isGameOver())){ 
			$this->_errorMessage[] = $res; 
			return false; 
		}
		
		if (Game::PLAYER == $this->_currentPlayer){ 
			$this->setPosition($move); 
			$this->setPlayer();
			$this->computerTurn(); 
		} else {
			$this->computerTurn(); 
		} 
		
		//check if game is over after computer turn is done (winner\draw\going on)
		if (Game::GOING_ON != ($res = $this->isGameOver())){ 
			$this->_errorMessage[] = $res; 
			return false; 
		}
		
	}

	/**
	 *	Check if the game is over: 
	 *		1. check if we have full row 
	 *		2. check if we have full column 
	 *		3. check if we have diagonal 
	 *		4. its a draw? 
	 *		5. game isn't over 
	 *	@returns TicTacToe::CONST
	 */
	public function isGameOver(){ 
		$gameBoardCount = count($this->_gameBoard); 

		//FULL ROW 
		for($i=0; $i<$gameBoardCount;$i++){
			if (false !== $this->_gameBoard[$i][0] &&($this->_gameBoard[$i][0] == $this->_gameBoard[$i][1]
				&& $this->_gameBoard[$i][1] == $this->_gameBoard[$i][2])){ 
				return $this->_gameBoard[$i][0]; 
			} 
		} 
		
		$gameBoardCount = count($this->_gameBoard[0]);
		//FULL COLUMN
		for($i=0; $i<$gameBoardCount;$i++){
			if (false !== $this->_gameBoard[0][$i] &&($this->_gameBoard[0][$i] == $this->_gameBoard[1][$i]
				&& $this->_gameBoard[1][$i] == $this->_gameBoard[2][$i])){ 
				return $this->_gameBoard[0][$i]; 
			} 
		} 
		
		//DIAGONAL 
		if (($this->_gameBoard[0][0] == $this->_gameBoard[1][1] 
				&& $this->_gameBoard[1][1] == $this->_gameBoard[2][2]) 
				|| ($this->_gameBoard[0][2] == $this->_gameBoard[1][1]
				&& $this->_gameBoard[1][1] == $this->_gameBoard[2][0])){
				
			if (false !== $this->_gameBoard[1][1]){ 
				return $this->_gameBoard[1][1]; 
			} 
		}
		
		
		//DRAW
		if (true === Game::isBoardFull($this->_gameBoard)){
			return Game::DRAW; 
		}
		
		//GAME IS ON
		return Game::GOING_ON;
	} 
} 