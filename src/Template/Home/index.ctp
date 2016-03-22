<?php
$this->layout = 'default';
$this->assign('title','Connect Four Online Game - 2 Remote Players');
?>
<div class="text-center"><h2><?= $this->fetch('title') ?></h2></div>
		<div class="text-center">
			<h4 id="winner" class="notify"></h4>
		</div>
<div class="row">
	<div class="medium-2 columns text-center">
		<h3 class="bluebg">Player 1</h3>
		<div id="player1">
			<span class="offline playerstatus" id="player1-status">Offline</span><br />
			<img id="redPlayer" src="img/red.png" class="hidden">
		</div>
	</div>
	<div class="medium-8 columns text-center">
		<h3>Game Board</h3>		
		<div class="medium-12 column" id="board">
			    <table>
                    <tr id="arrows">
                        <td class="arrow col7"><button id="col7"><img src="img/down.png"></button></td>
                        <td class="arrow col6"><button id="col6"><img src="img/down.png"></button></td>
                        <td class="arrow col5"><button id="col5"><img src="img/down.png"></button></td>
                        <td class="arrow col4"><button id="col4"><img src="img/down.png"></button></td>
                        <td class="arrow col3"><button id="col3"><img src="img/down.png"></button></td>
                        <td class="arrow col2"><button id="col2"><img src="img/down.png"></button></td>
                        <td class="arrow col1"><button id="col1"><img src="img/down.png"></button></td>
                    </tr>
                    <tr id="row7">
                        <td class="free col7"></td>
                        <td class="free col6"></td>
                        <td class="free col5"></td>
                        <td class="free col4"></td>
                        <td class="free col3"></td>
                        <td class="free col2"></td>
                        <td class="free col1"></td>
                    </tr>
                    <tr id="row6">
                        <td class="free col7"></td>
                        <td class="free col6"></td>
                        <td class="free col5"></td>
                        <td class="free col4"></td>
                        <td class="free col3"></td>
                        <td class="free col2"></td>
                        <td class="free col1"></td>
                    </tr>
                    <tr id="row5">
                        <td class="free col7"></td>
                        <td class="free col6"></td>
                        <td class="free col5"></td>
                        <td class="free col4"></td>
                        <td class="free col3"></td>
                        <td class="free col2"></td>
                        <td class="free col1"></td>
                    </tr>
                    <tr id="row4">
                        <td class="free col7"></td>
                        <td class="free col6"></td>
                        <td class="free col5"></td>
                        <td class="free col4"></td>
                        <td class="free col3"></td>
                        <td class="free col2"></td>
                        <td class="free col1"></td>
                    </tr>
                    <tr id="row3">
                        <td class="free col7"></td>
                        <td class="free col6"></td>
                        <td class="free col5"></td>
                        <td class="free col4"></td>
                        <td class="free col3"></td>
                        <td class="free col2"></td>
                        <td class="free col1"></td>
                    </tr>
                    <tr id="row2">
                        <td class="free col7"></td>
                        <td class="free col6"></td>
                        <td class="free col5"></td>
                        <td class="free col4"></td>
                        <td class="free col3"></td>
                        <td class="free col2"></td>
                        <td class="free col1"></td>
                    </tr>
                    <tr id="row1">
                        <td class="free bot col7"></td>
                        <td class="free bot col6"></td>
                        <td class="free bot col5"></td>
                        <td class="free bot col4"></td>
                        <td class="free bot col3"></td>
                        <td class="free bot col2"></td>
                        <td class="free bot col1"></td>
                    </tr>
                </table>
				<button id="newGame">New Game</button>
		</div>
	</div>
	<div class="medium-2 columns text-center">
		<h3 class="redbg">Player 2</h3>		
		<div id="player2">
            <span class="offline playerstatus" id="player2-status">Offline</span><br />
            <img id="bluePlayer" src="img/blue.png" class="hidden">
        </div>
	</div>
</div>
<footer>
<h5 class="text-center">The game can be played remotely by 2 players using separate computers over the internet or local network.</h5>
<h4 class="text-center">Coded By: Ariel Lim</h4>
</footer>
<style>

</style>