<?php

//Includio i parametri, la configurazione, la lingua e le funzioni
require ('../../../includes/required.php');

// Determino il tema selezionato
if(!empty($_SESSION['theme']) and array_key_exists($_SESSION['theme'], $PARAMETERS['themes']['available'])){
    $PARAMETERS['themes']['current_theme'] = $_SESSION['theme'];
}

$linkMessages = '
    <a data-toggle="tooltip" data-placement="right" title="Tooltip on right" href="documentazione.html" onclick="window.open(\'documentazione.html\',\'newwindow\', \'width=860,height=430\'); return false;" >
        <img class="icon_documentazione" src="../../../themes/'.$PARAMETERS['themes']['current_theme'].'/imgs/custom_imgs/icons/DOCUMENTAZIONE.png'.'"/>
    </a>';

$cntMessagesFrame = '<div class="messaggio_forum">'.$linkMessages.'</div>';

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
    <!--meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../../themes/<?php echo $PARAMETERS['themes']['current_theme']; ?>/presenti.css" TYPE="text/css">
    <link rel="stylesheet" href="../../../themes/<?php echo $PARAMETERS['themes']['current_theme']; ?>/main.css" TYPE="text/css">
    <link rel="stylesheet" href="../../../themes/<?php echo $PARAMETERS['themes']['current_theme']; ?>/chat.css" TYPE="text/css">
    <link rel="stylesheet" href="../../../themes/<?php echo $PARAMETERS['themes']['current_theme']; ?>/custom.css" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <title>Documentazione</title>
</head>
<body class="transparent_body">
    <?=AudioController::build('messages');?>
    <div class="box_messages"><?=isset($cntMessagesFrame) ? $cntMessagesFrame : '';?></div>

<?php include('../../../footer.inc.php'); /*Footer comune*/
