<?php
if((is_numeric($_POST['mesi']) === true) && ($_POST['mesi'] >= 0) && ($_POST['mesi'] <= 12)) {
    /*Eseguo l'aggiornamento*/
	//cancellazione dei messaggi più vecchi di tot mesi
	gdrcd_query("DELETE FROM msg WHERE dtsend <= DATE_SUB(NOW(), INTERVAL ".gdrcd_filter('num', $_POST['mesi'])." MONTH)");
    //PULIZIA gruppi vuoti: per ogni gruppo conta i messaggi, se non ci sono messaggi, elimina gruppo e membri
	$query = "SELECT idgroup FROM msggrp";
	$result = gdrcd_query($query, 'result');
	while($group = gdrcd_query($result, 'fetch')) {
		$idgroup = gdrcd_filter('num', $group['idgroup']);
		$queryCount = gdrcd_query("SELECT count(*) as nrMsg FROM msg WHERE idgroup=".$idgroup);
        if(gdrcd_filter('num', $queryCount['nrMsg']) <=0){
			gdrcd_query("DELETE FROM msggrpuser WHERE idgroup=".$idgroup);
			gdrcd_query("DELETE FROM msggrp WHERE idgroup=".$idgroup);			
		}
	}
	gdrcd_query($result, 'free');
    ?>
    <!-- Conferma -->
    <div class="warning">
        <?php echo gdrcd_filter('out', $MESSAGE['warning']['modified']); ?>
    </div>
    <?php
} else {
    ?>
    <div class="error">
        <?php echo gdrcd_filter('out', $MESSAGE['warning']['cant_do']); ?>
    </div>
    <?php
}
?>
<!-- Link di ritorno alla visualizzazione di base -->
<div class="link_back">
    <a href="main.php?page=gestione_manutenzione">
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['maintenance']['link']['back']); ?>
    </a>
</div>