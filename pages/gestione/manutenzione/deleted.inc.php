<?php
/*Eseguo l'aggiornamento*/
gdrcd_query("DELETE FROM clgpersonaggiooggetto WHERE nome IN (SELECT nome FROM personaggio WHERE permessi = -1)");
gdrcd_query("OPTIMIZE TABLE clgpersonaggiooggetto");

gdrcd_query("DELETE FROM clgpersonaggioabilita WHERE nome IN (SELECT nome FROM personaggio WHERE permessi = -1)");
gdrcd_query("OPTIMIZE TABLE clgpersonaggioabilita");

gdrcd_query("DELETE FROM clgpersonaggiomostrine WHERE nome IN (SELECT nome FROM personaggio WHERE permessi = -1)");
gdrcd_query("OPTIMIZE TABLE clgpersonaggiomostrine");

gdrcd_query("DELETE FROM clgpersonaggioruolo WHERE personaggio IN (SELECT nome AS personaggio FROM personaggio WHERE permessi = -1)");
gdrcd_query("OPTIMIZE TABLE clgpersonaggioruolo");

gdrcd_query("UPDATE msggrpuser set nome = CONCAT(nome,'_del'), tpuser='DELETED', dtend= NOW() WHERE nome IN (SELECT nome AS personaggio FROM personaggio WHERE permessi = -1);");
$query = "SELECT nome AS personaggio FROM personaggio WHERE permessi = -1";
$result = gdrcd_query($query, 'result');
while($pg = gdrcd_query($result, 'fetch')) {
	$pg = gdrcd_filter('in', $pg['personaggio']);
	$nome = $pg .'_del';
	gdrcd_query("UPDATE msg SET nomesender ='". $nome ."' WHERE nomesender='".$pg."'");
}
gdrcd_query($result, 'free');

gdrcd_query("DELETE FROM araldo_letto WHERE nome IN (SELECT nome AS personaggio FROM personaggio WHERE permessi = -1)");
gdrcd_query("OPTIMIZE TABLE araldo_letto");

gdrcd_query("UPDATE chat SET mittente = 'Cancellato' WHERE mittente IN (SELECT nome AS personaggio FROM personaggio WHERE permessi = -1)");
gdrcd_query("UPDATE chat SET destinatario = 'Cancellato' WHERE destinatario IN (SELECT nome AS personaggio FROM personaggio WHERE permessi = -1)");
gdrcd_query("OPTIMIZE TABLE chat");

gdrcd_query("UPDATE log SET nome_interessato = 'Cancellato' WHERE nome_interessato IN (SELECT nome AS personaggio FROM personaggio WHERE permessi = -1)");
gdrcd_query("OPTIMIZE TABLE log");

gdrcd_query("UPDATE messaggioaraldo SET autore = 'Cancellato' WHERE autore IN (SELECT nome AS personaggio FROM personaggio WHERE permessi = -1)");
gdrcd_query("OPTIMIZE TABLE messaggiaraldo");

gdrcd_query("DELETE FROM personaggio WHERE permessi = -1");
gdrcd_query("OPTIMIZE TABLE personaggio");
?>
<!-- Conferma -->
<div class="warning">
    <?php echo gdrcd_filter('out', $MESSAGE['warning']['modified']); ?>
</div>
<!-- Link di ritorno alla visualizzazione di base -->
<div class="link_back">
    <a href="main.php?page=gestione_manutenzione">
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['maintenance']['link']['back']); ?>
    </a>
</div>