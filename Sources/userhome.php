<div class="usercont">
<? global $nick, $acc, $data; ?>
	<div class="inucont">
		<h1><?=$nick;?><div <? if ( $data['vip'] == 0 ) { ?> id="moneyred" <? } else { ?> id="money" <? } ?> id="button">
		<? if ( $data['vip'] == 0 ) { echo "Sin VIP"; } else { echo "VIP Activo"; } ?>
		</div>
		<div id="moneyblue" id="button">$<?=$data['bankmoney'];?></div>
		<div id="money" id="button">$<?=$data['money'];?></div>
		</h1>
	</div>
</div>