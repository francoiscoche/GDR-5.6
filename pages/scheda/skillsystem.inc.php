<?php
//carico le sole abilità del pg
$abilita = gdrcd_query("SELECT id_abilita, grado FROM clgpersonaggioabilita WHERE nome='".gdrcd_filter('in', $_REQUEST['pg'])."'", 'result');

$px_spesi = 0;
while($row = gdrcd_query($abilita, 'fetch')) {
    /*Costo in px della singola abilità*/
    $px_abi = $PARAMETERS['settings']['px_x_rank'] * (($row['grado'] * ($row['grado'] + 1)) / 2);
    /*Costo totale*/
    $px_spesi += $px_abi;
    $ranks[$row['id_abilita']] = $row['grado'];
}

$personaggio=gdrcd_query("SELECT id_razza, esperienza FROM personaggio WHERE nome='".gdrcd_filter('in', $_REQUEST['pg'])."'", 'query');


$px_totali_pg = gdrcd_filter('int', $personaggio['esperienza']) ;


?>