<?php
//Includo i parametri, la configurazione, la lingua e le funzioni
require('../../includes/required.php');

//Eseguo la connessione al database
$handleDBConnection = gdrcd_connect();
$me = gdrcd_filter('out', $_SESSION['login']);
$result = gdrcd_query("SELECT eventi.id,  start, end, eventi_tipo.title, titolo AS description, 
            eventi_colori.backgroundColor, eventi_colori.borderColor, eventi_colori.textColor  FROM eventi 
            LEFT JOIN eventi_tipo ON eventi.title = eventi_tipo.id
            LEFT  JOIN eventi_colori ON eventi.colore = eventi_colori.id ORDER BY id", 'result');

$eventArray = array();

while ($row = gdrcd_query($result, 'fetch')) {
    $eventArray[] = array(
        'id' => $row['id'],
        'title' => (strlen($row['title']) > 6) ? (substr($row['title'], 0, 6) . '...') : ($row['title']),
        'start' => $row['start'],
        'end' => $row['end'],
        'description' => $row['description'],
        'backgroundColor' => $row['backgroundColor'],
        'borderColor' => $row['borderColor'],
        'textColor' => $row['textColor']
    );
}
if(CALENDAR_PERSONAL_GLOBAL && CALENDAR_PERSONAL){
    $result = gdrcd_query("SELECT eventi_personaggio.id,  start, end,eventi_tipo.title, CONCAT('Personale - ', titolo) AS description, 
             eventi_colori.backgroundColor, eventi_colori.borderColor, eventi_colori.textColor  
             FROM eventi_personaggio 
             LEFT JOIN eventi_tipo ON eventi_personaggio.title = eventi_tipo.id
             LEFT  JOIN eventi_colori ON eventi_personaggio.colore = eventi_colori.id 
             WHERE personaggio='{$me}' ORDER BY id", 'result');

    while ($row = gdrcd_query($result, 'fetch')) {
        $eventArray[] = array(
            'id' => $row['id'],
            'title' => (strlen($row['title']) > 6) ? (substr($row['title'], 0, 6) . '...') : ($row['title']),
            'start' => $row['start'],
            'end' => $row['end'],
            'description' => $row['description'],
            'backgroundColor' => $row['backgroundColor'],
            'borderColor' => $row['borderColor'],
            'textColor' => $row['textColor']
        );
    }
}

echo json_encode($eventArray);
?>
