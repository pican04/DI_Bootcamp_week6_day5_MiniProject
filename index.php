
 <!--Mini Project : Tic Tac Toe-->

<!--
  Step 1:
- Let’s start with the game grid itself. Tic tac toe is played on a 3x3 grid, so to represent that in code you just need a 2-dimensional array of three arrays, each with three items in the array. 
- This format allows us to store the state of each cell using a string.
- So, when a player takes their turn one of the items in the array will be changed to an ‘X’ or a ‘O’.

  Step 2:
- With these variables defined you can start drawing out the game board.
- All you++66666 need to do is loop through the multi-dimensional array and convert it into a 3x3 grid represented as a string.
- The active cell and player variables also need to be taken into account to change the output of the board. Whilst the player variable just prints out the current player, the active cell is used to highlight one of the cells in the grid.
- The following function will take in the $stage, $activeCell and $player variables and return a string containing the current state of play.

Step 3:
- All you need is an infinite loop and a way of resetting the command line every time the game output is created.
- This is achieved using a while loop and a call to the system command ‘clear’.
- With this in place, every time the game output is rendered it will be at the top of the screen, which gives the illusion of animating the game output.

Step 4:
- Now you need some way of allowing players to move around the board and select their move.
- This is done by listening to the keys being inputted and acting to that input accordingly. There are a couple of simple rules you need to follow here.
- If the user enters an arrow key then update the active cell accordingly.
- Don’t allow the active cell to be beyond the confines of the game grid.
- If the user presses enter and the current active cell is blank then fill the cell with the token for the current player. If this happens then swap the active player.

Step 5:
- Integrate this into your loop you call the move() function before the renderGame() function so that the game board will update with any change made by the user.
- The players are now able to move around the game board and place tokens.

-->
<?php
$state = [
    ['', '', ''],
    ['', '', ''],
    ['', '', ''],
];
// current player
$player = 'X';
// Actif line
$activeCell = [0 => 0, 1 => 0];


function renderGame($state, $activeCell, $player) {
    $output = '';
    $output .= 'Player:' . $player . "\n";
    foreach ($state as $x => $line) {
      $output .= '|';
      foreach ($line as $y => $item) {
        switch ($item) {
          case '';
            $cell = ' ';
            break;
          case 'X';
            $cell = 'X';
            break;
          case 'O';
            $cell = 'O';
            break;
        }
        if ($activeCell[0] == $x && $activeCell[1] == $y) {
          $cell = '-'. $cell . '-';
        }
        else {
          $cell = ' ' . $cell . ' ';
        }
        $output .= $cell . '|';
      }
      $output .= "\n";
    }
    return $output;
  }
// Function to save keywoard value
function translateKeypress($string) {
    switch ($string) {
      case "\033[A":
        return "UP";
      case "\033[B":
        return "DOWN";
      case "\033[C":
        return "RIGHT";
      case "\033[D":
        return "LEFT";
      case "\n":
        return "ENTER";
      case " ":
        return "SPACE";
      case "\010":
      case "\177":
        return "BACKSPACE";
      case "\t":
        return "TAB";
      case "\e":
        return "ESC";
     }
    return $string;
  }
//   Function to move in game
  function move($stdin, &$state, &$activeCell, &$player) {
    $key = fgets($stdin);
    if ($key) {
      $key = translateKeypress($key);
      switch ($key) {
        case "UP":
          if ($activeCell[0] >= 1) {
            $activeCell[0]--;
          }
          break;
        case "DOWN":
          if ($activeCell[0] < 2) {
            $activeCell[0]++;
          }
          break;
        case "RIGHT":
          if ($activeCell[1] < 2) {
            $activeCell[1]++;
          }
          break;
        case "LEFT":
          if ($activeCell[1] >= 1) {
            $activeCell[1]--;
          }
          break;
        case "ENTER":
        case "SPACE":
          if ($state[$activeCell[0]][$activeCell[1]] == '') {
            $state[$activeCell[0]][$activeCell[1]] = $player;
            if ($player == 'X') {
              $player = 'O';
            } else {
              $player = 'X';
            }
          }
          break;
       }
    }
  }
//To determine the winner
  function isWinState($state) {
    foreach (['X', 'O'] as $player) {
        // To verify line
        foreach ($state as $x => $line) {
        
        if ($state[$x][0] == $player && $state[$x][1] == $player && $state[$x][2] == $player) {
          die($player . ' wins');
        }
        // To verify colum
        foreach ($line as $y => $item) {
          if ($state[0][$y] == $player && $state[1][$y] == $player && $state[2][$y] == $player) {
            die($player . ' wins');
          }
        }
      }
      if ($state[0][0] == $player && $state[1][1] == $player && $state[2][2] == $player) {
        die($player . ' wins');
      }
      if ($state[2][0] == $player && $state[1][1] == $player && $state[0][2] == $player) {
        die($player . ' wins');
      }
    }
    // Verify egality
    $blankQuares = 0;
    foreach ($state as $x => $line) {
      foreach ($line as $y => $item) {
        if ($state[$x][$y] == '') {
          $blankQuares++;
        }
      }
    }
    if ($blankQuares == 0) {
      die('DRAW!');
    }
  }
  // Update data in command line
  $stdin = fopen('php://stdin', 'r');
  stream_set_blocking($stdin, 0);
  system('stty cbreak -echo');
  while (1) {
    system('clear');
    move($stdin, $state, $activeCell, $player);
    echo renderGame($state, $activeCell, $player);
    isWinState($state);
  }
?>