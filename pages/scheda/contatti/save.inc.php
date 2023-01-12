<?php
if (isset($_REQUEST['pg']) === false) {
    echo gdrcd_filter('out', $MESSAGE['error']['unknonw_character_sheet']);
    exit();
}
switch ($_POST['op']) {

    # Creazione Contatto
    case 'save_new':
        $nome = gdrcd_filter('in', $_POST['nome']);
        $alias = gdrcd_filter('in', $_POST['alias']);
        $descrizione = gdrcd_filter('in', $_POST['descrizione']);
        $pg=gdrcd_filter('in', $_REQUEST['pg']);

        gdrcd_query("INSERT INTO contatti (pg,contatto,alias,descrizione )  VALUES
        ('{$pg}', '{$nome}','{$alias}','{$descrizione}') ");
        break;

    # Modifica Contatto
    case 'save_edit':
        $id = gdrcd_filter('in', $_POST['id']);
        $alias = gdrcd_filter('in', $_POST['alias']);
        $descrizione = gdrcd_filter('in', $_POST['descrizione']);


        gdrcd_query("UPDATE  contatti 
                SET alias = '{$alias}',descrizione='{$descrizione}'
                WHERE id='{$id}' LIMIT 1 ");
        break;

    # Delete contatto
    case 'delete':
        $id = gdrcd_filter('in', $_POST['id']);
        echo "DELETE FROM contatti WHERE id='{$id}'";
        gdrcd_query("DELETE FROM contatti WHERE id='{$id}'");
        break;

    default:
        die('Operazione non riconosciuta.');
}

echo '<div class="warning">' . gdrcd_filter('out', $MESSAGE['warning']['done']) . '</div>';

?>
<!-- Link a piè di pagina -->
<div class="link_back">
    <a href="main.php?page=scheda_contatti&pg=<?php echo gdrcd_filter('url', $_REQUEST['pg']); ?>"><?php echo gdrcd_filter('out',
            $MESSAGE['interface']['sheet']['contact']['back']); ?></a>
</div>
