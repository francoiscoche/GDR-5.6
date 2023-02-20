<?php

$pg = gdrcd_filter('out', $_REQUEST['pg']);
$me = gdrcd_filter('out',$_SESSION['login']);
$permessi  = gdrcd_filter('out',$_SESSION['permessi']);


?>

<ul class="">
<?php


# Modifica
if (($pg == $me) || ($permessi >= GUILDMODERATOR)) { ?>
<li>
<a href="main.php?page=scheda_modifica&pg=<?=$pg;?>">
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['update']); ?>
    </a>
</li>

<?php } ?>
    <!-- Descrizione e Storia separate dalla pagina principale della scheda -->
    <li>
    <a href="main.php?page=scheda_descrizione&pg=<?=$pg;?>">
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['detail']); ?>
    </a>
    </li>
<li>
<!-- <a href="main.php?page=scheda_storia&pg=<?=$pg;?>">
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['background']); ?>
    </a>
</li> -->

    <!-- TRASFERIMENTI -->
    <li>
    <a href="main.php?page=scheda_trans&pg=<?=$pg;?>">
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['transictions']); ?>
    </a>

    </li>


    <!-- ESPERIENZA -->
    <li>
    <a href="main.php?page=scheda_px&pg=<?=$pg;?>">
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['experience']); ?>
    </a>
    </li>


    <!-- OGGETTI -->
    <!-- <li>
    <a href="main.php?page=scheda_oggetti&pg=<?=$pg;?>">
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['inventory']); ?>
    </a>
    </li> -->


    <!-- INVENTARIO -->
    <!-- <li>
    <a href="main.php?page=scheda_equip&pg=<?=$pg;?>">
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['equipment']); ?>
    </a>
    </li> -->


    <!-- CALENDARIO -->
    <?php if (
        (CALENDAR and CALENDAR_PERSONAL and CALENDAR_PERSONAL_PUBLIC)
        ||
        (CALENDAR and CALENDAR_PERSONAL and $permessi >= ROLE_PERM)
        || (CALENDAR and CALENDAR_PERSONAL and $pg == $me)

    ) { ?>
    <li>
        <a href="main.php?page=scheda_calendario&pg=<?=$pg;?>">Calendario</a>
    </li>
    <?php } ?>

    <!-- CONTATTI -->
    <?php if (defined('PG_CONTACT_ENABLED') and PG_CONTACT_ENABLED) { ?>
        <li>
        <a href="main.php?page=scheda_contatti&pg=<?=$pg;?>">
            <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['contact']); ?>
        </a>
        </li>

    <?php } ?>

    <!-- STATUS -->
    <li>
    <a href="main.php?page=scheda_note_fato&pg=<?=$pg;?>">
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['status']); ?>
    </a>

    </li>

    <!-- DIARIO -->
<?php if (defined('PG_DIARY_ENABLED') and PG_DIARY_ENABLED) { ?>
    <li>
    <a href="main.php?page=scheda_diario&pg=<?=$pg;?>">
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['diary']); ?>
    </a>
    </li>

<?php } ?>

    <!-- ROLES -->
<?php if ( ( ($permessi >= ROLE_PERM) || ($pg == $me) ) && REG_ROLE) { ?>
    <li>
    <a href="main.php?page=scheda_roles&pg=<?=$pg;?>">
        Giocate registrate
    </a>
    </li>

<?php } ?>

    <!-- Se maggiore di moderatore -->
<?php if ($permessi >= MODERATOR) { ?>

    <!-- LOG -->
    <li>
    <a href="main.php?page=scheda_log&pg=<?=$pg;?>">
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['log']); ?>
    </a>
    </li>


    <!-- AMMINISTRA -->
    <li>
    <a href="main.php?page=scheda_gst&pg=<?=$pg;?>">
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['sheet']['menu']['gst']); ?>
    </a>
    </li>

</ul>
<?php }
