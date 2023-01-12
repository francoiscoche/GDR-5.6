<?php /*HELP: */
//Se non e' stato specificato il nome del pg
if (isset($_REQUEST['pg']) === false) {
    echo gdrcd_filter('out', $MESSAGE['error']['unknonw_character_sheet']);
    exit();
}
?>

<div class="form_container">
    <form class="form"
          action="main.php?page=scheda_contatti&pg=<?php echo gdrcd_filter('url', $_REQUEST['pg']); ?>" method="post">

        <!-- TITOLO -->
        <div class="single_input">
            <div class="label"><?= gdrcd_filter('out', $MESSAGE['interface']['sheet']['contact']['name']); ?></div>
            <?php
            $result = gdrcd_query("SELECT nome FROM personaggio WHERE nome NOT LIKE '{$_REQUEST['pg']}'  ORDER BY nome", 'result'); ?>
            <select name="nome">
                <?php while ($row = gdrcd_query($result, 'fetch'))
                { ?>
                    <option value="<?php echo gdrcd_filter('out', $row['nome']); ?>">
                        <?php echo gdrcd_filter('out', $row['nome']); ?>
                    </option>
                <?php }//while

                gdrcd_query($result, 'free');
                ?>
            </select>
        </div>

        <!-- ALIAS -->
        <div class="single_input">
            <div class="label"><?= gdrcd_filter('out', $MESSAGE['interface']['sheet']['contact']['alias']); ?></div>
            <input type="text" name="alias" class="form_input" required/>
        </div>

        <!-- TESTO -->
        <div class="single_input">
            <div class="label"><?= gdrcd_filter('out', $MESSAGE['interface']['sheet']['contact']['note']); ?></div>
            <textarea name="descrizione"></textarea>
        </div>

        <!-- SUBMIT -->
        <div class="single_input">
            <input type="submit" name="submit" value="Salva"/>
            <input type="hidden" name="op" value="save_new">
            <input type="hidden" name="pg" value="<?= gdrcd_filter('out', $_REQUEST['pg']); ?>">
        </div>
</div>
<!-- Link a piè di pagina -->
<div class="link_back">
    <a href="main.php?page=scheda_contatti&pg=<?php echo gdrcd_filter('url', $_REQUEST['pg']); ?>"><?php echo gdrcd_filter('out',
            $MESSAGE['interface']['sheet']['contact']['back']); ?></a>
</div>
