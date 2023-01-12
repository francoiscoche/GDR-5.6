<?php /*Controllo che i contatti siano del pg loggato, per inserimento nuove pagine*/
if ($_REQUEST['pg'] == $_SESSION['login']) { ?>
    <form action="main.php?page=scheda_contatti&pg=<?php echo gdrcd_filter('url', $_REQUEST['pg']); ?>" method="post">
        <input type="submit" value="<?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['contact']['new']); ?>">
        <input type="hidden" name="op" value="new"/>
    </form>
<?php } ?>

<div class="fake-table index-table">
    <div class="tr header">
        <div class="td">
Avatar
        </div>
        <div class="td">
            <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['contact']['name']); ?>
        </div>
        <div class="td">
            <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['contact']['alias']); ?>
        </div>
        <div class="td">
            <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['contact']['note']); ?>
        </div>
        <?php
        if ($_REQUEST['pg'] == $_SESSION['login'] || $_SESSION['permessi'] >= MODERATOR) { ?>


            <div class="td">
                <?php echo $MESSAGE['interface']['forums']['link']['edit']; ?>
             |
                <?php echo $MESSAGE['interface']['forums']['link']['delete']; ?>
            </div>
        <?php } ?>
    </div>

    <?php
    $query="SELECT id, alias, contatti.descrizione, personaggio.url_img_chat, personaggio.nome FROM contatti LEFT JOIN personaggio 
ON nome=contatto WHERE pg='".gdrcd_filter('url', $_REQUEST['pg']) . "' ORDER BY contatto DESC";

    $result = gdrcd_query($query, 'result');

    while ($row = gdrcd_query($result, 'fetch')) {
        ?>
        <div class="tr">
            <div class="td">
                <img src="<?=$row['url_img_chat']?>" style="width: 50px; height: 50px">
            </div>
            <div class="td">
                <b><a href="../main.php?page=scheda&pg=<?=gdrcd_filter('in', $row['nome'])?>" class="link_sheet" target="_top">
                        <?=$row['nome']?></a></b>
            </div>
            <div class="td">
                <?=$row['alias']?>
            </div>
            <div class="td">
                <?=$row['descrizione']?>
            </div>


            <?php
            if ($_REQUEST['pg'] == $_SESSION['login'] || $_SESSION['permessi'] >= PERMESSI_CONOSCENZE) { ?>

                <div class="td">
                    <form action="main.php?page=scheda_contatti&pg=<?php echo gdrcd_filter('url', $_REQUEST['pg']); ?>"
                          method="post">
                        <input hidden value="edit" name="op">
                        <button type="submit" name="id" value="<?php echo gdrcd_filter('out', $row['id']); ?>"
                                class="btn-link">[<?php echo $MESSAGE['interface']['forums']['link']['edit']; ?>]
                        </button>
                    </form>
                
                    <form action="main.php?page=scheda_contatti&pg=<?php echo gdrcd_filter('url', $_REQUEST['pg']); ?>"
                          method="post">
                        <input hidden value="delete" name="op">
                        <button type="submit" name="id" onClick='return confirmSubmit()'
                                value="<?php echo gdrcd_filter('out', $row['id']); ?>" class="btn-link">
                            [<?php echo gdrcd_filter('out', $MESSAGE['interface']['forums']['link']['delete']); ?>]
                        </button>
                    </form>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>

<!-- Link a piè di pagina -->
<div class="link_back">
    <a href="main.php?page=scheda&pg=<?php echo gdrcd_filter('url',
        $_REQUEST['pg']); ?>"><?php echo gdrcd_filter('out',
            $MESSAGE['interface']['sheet']['link']['back']); ?></a>
</div>
<script>
    function confirmSubmit() {
        var agree = confirm("Vuoi eliminare la pagina?");
        if (agree)
            return true;
        else
            return false;
    }
</script>
