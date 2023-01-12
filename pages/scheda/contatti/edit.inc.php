<?php /*HELP: */
//Se non e' stato specificato il nome del pg
if (isset($_REQUEST['pg']) === false) {
    echo gdrcd_filter('out', $MESSAGE['error']['unknonw_character_sheet']);
    exit();
}

$id = gdrcd_filter('num', $_POST['id']);
$result = gdrcd_query("SELECT * FROM contatti WHERE id='{$id}' LIMIT 1");

?>
<div class="page_title">
    <h2><?= gdrcd_filter('out', $result['contatto']); ?></h2>
</div>
<div class="form_container">
    <form class="form" action="main.php?page=scheda_contatti&pg=<?php echo gdrcd_filter('url', $_REQUEST['pg']); ?>"
          method="post">


        <!-- ALIAS -->
        <div class="single_input">
            <div class="label"><?= gdrcd_filter('out', $MESSAGE['interface']['sheet']['contact']['alias']); ?></div>
            <input type="text" name="alias" class="form_input" value="<?= gdrcd_filter('out', $result['alias']); ?>"
                   required/>
        </div>



        <!-- DESCRIZIONE -->
        <div class="single_input">
            <div class="label"><?= gdrcd_filter('out', $MESSAGE['interface']['sheet']['contact']['note']); ?></div>
            <textarea name="descrizione"><?= gdrcd_filter('out', $result['descrizione']); ?></textarea>
        </div>

        <!-- SUBMIT -->
        <div class="single_input">
            <input type="submit" name="submit" value="Salva"/>
            <input hidden name="op" value="save_edit">
            <input hidden name="id" value="<?= $id; ?>">
        </div>
    </form>
</div>

<!-- Link a piè di pagina -->
<div class="link_back">
    <a href="main.php?page=scheda_contatti&pg=<?php echo gdrcd_filter('url', $_REQUEST['pg']); ?>"><?php echo gdrcd_filter('out',
            $MESSAGE['interface']['sheet']['contact']['back']); ?></a>
</div>
