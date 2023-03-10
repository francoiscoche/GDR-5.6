<?php

    /*HELP: */
    /*Controllo permessi utente*/
    if(!gdrcd_controllo_permessi($PARAMETERS['administration']['maintenance']['access_level'])) {
        echo '<div class="error">'.gdrcd_filter('out', $MESSAGE['error']['not_allowed']).'</div>';
        die();
    }

    ?>

<div class="gestione_pagina">

    <!-- Titolo della pagina -->
    <div class="gestione_incipit">
        <div class="title"><?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['maintenance']['page_name']); ?></div>
    </div>
        <!-- Corpo della pagina -->
        <div class="gestione_body">
        <?php
        /*
         * Richieste POST
         */
        switch(gdrcd_filter_get($_POST['op'])) {
            case 'blacklisted': //Elimina blacklist
                include('blacklisted.inc.php');
                break;
            case 'deleted': //Elimina personaggi che non si loggano più
                include('deleted.inc.php');
                break;
            case 'old_chat': //Elimina vecchi log
                include('old_chat.inc.php');
                break;
            case 'old_log': //Elimina vecchi log
                include('old_log.inc.php');
                break;
            case 'old_messages': //Elimina vecchi messaggi
                include('old_messages.inc.php');
                break;
            case 'missing': //Elimina personaggi che non si loggano più
                include('missing.inc.php');
                break;
            default: // Pagina di default
                break;
        }
        /*
         * Richieste GET
         */
        switch(gdrcd_filter_get($_GET['op'])) {
            default: //visualizzazione di base
                include('view.inc.php');
                break;
        }

        echo '</div>'; //<!-- page_body -->
    ?>
</div><!-- pagina -->

<!-- Log -->
<div class="panels_box">
    <form action="main.php?page=gestione_manutenzione"
          method="post"
          class="form_gestione">
        <div class='form_label'>
            <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['maintenance']['old_log']); ?>
        </div>
        <div class='form_field'>
            <select name="mesi" ?>
                <?php for($i = 0; $i <= 12; $i++) { ?>
                    <option value="<?php echo $i; ?>"><?php echo $i.' '.gdrcd_filter('out', $MESSAGE['interface']['administration']['maintenance']['months']); ?></option>
                <?php } ?>
            </select>
        </div>
        <!-- bottoni -->
        <div class='form_submit'>
            <input type="hidden" name="op" value="old_log">
            <input type="submit" value="<?php echo gdrcd_filter('out', $MESSAGE['interface']['forms']['submit']); ?>" />
        </div>
    </form>
</div>
<!-- Chat -->
<div class="panels_box">
    <form action="main.php?page=gestione_manutenzione" method="post" class="form_gestione">
        <div class='form_label'>
            <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['maintenance']['old_chat']); ?>
        </div>
        <div class='form_field'>
            <select name="mesi" ?>
                <?php for($i = 0; $i <= 12; $i++) { ?>
                    <option value="<?php echo $i; ?>"><?php echo $i.' '.gdrcd_filter('out', $MESSAGE['interface']['administration']['maintenance']['months']); ?></option>
                <?php } ?>
            </select>
        </div>
        <!-- bottoni -->
        <div class='form_submit'>
            <input type="hidden" name="op" value="old_chat">
            <input type="submit" value="<?php echo gdrcd_filter('out', $MESSAGE['interface']['forms']['submit']); ?>" />
        </div>
    </form>
</div>
<!-- Messaggi -->
<div class="panels_box">
    <form action="main.php?page=gestione_manutenzione" method="post" class="form_gestione">
        <div class='form_label'>
            <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['maintenance']['old_messages']); ?>
        </div>
        <div class='form_field'>
            <select name="mesi" ?>
                <?php for($i = 0; $i <= 12; $i++) { ?>
                    <option value="<?php echo $i; ?>"><?php echo $i.' '.gdrcd_filter('out', $MESSAGE['interface']['administration']['maintenance']['months']); ?></option>
                <?php } ?>
            </select>
        </div>
    <div class='form_info'>
        <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['maintenance']['old_group_info']); ?>
    </div>
        <!-- bottoni -->
        <div class='form_submit'>
            <input type="hidden" name="op" value="old_messages">
            <input type="submit" value="<?php echo gdrcd_filter('out', $MESSAGE['interface']['forms']['submit']); ?>" />
        </div>
    </form>
</div>
<!-- Cancellati -->
<div class="panels_box">
    <form action="main.php?page=gestione_manutenzione" method="post" class="form_gestione">
        <div class='form_label'>
            <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['maintenance']['deleted']); ?>
        </div>
        <div class='form_info'>
            <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['maintenance']['deleted_info']); ?>
        </div>
        <!-- bottoni -->
        <div class='form_submit'>
            <input type="hidden" name="op" value="deleted">
            <input type="submit" value="<?php echo gdrcd_filter('out', $MESSAGE['interface']['forms']['submit']); ?>" />
        </div>
    </form>
</div>
<!-- Assenti -->
<div class="panels_box">
    <form action="main.php?page=gestione_manutenzione"
          method="post"
          class="form_gestione">
        <div class='form_label'>
            <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['maintenance']['missing']); ?>
        </div>
        <div class='form_field'>
            <select name="mesi" ?>
                <?php
                for($i = 1; $i <= 12; $i++) { ?>
                    <option value="<?php echo $i; ?>"><?php echo $i.' '.gdrcd_filter('out', $MESSAGE['interface']['administration']['maintenance']['months']); ?></option>
                <?php } ?>
            </select>
        </div>
        <div class='form_info'>
            <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['maintenance']['missing_info']); ?>
        </div>
        <!-- bottoni -->
        <div class='form_submit'>
            <input type="hidden" name="op" value="missing">
            <input type="submit" value="<?php echo gdrcd_filter('out', $MESSAGE['interface']['forms']['submit']); ?>" />
        </div>
    </form>
</div>
<!-- Blacklisted -->
<div class="panels_box">
    <form action="main.php?page=gestione_manutenzione" method="post" class="form_gestione">
        <div class='form_label'>
            <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['maintenance']['blacklisted']); ?>
        </div>
        <div class='form_info'>
            <?php echo gdrcd_filter('out', $MESSAGE['interface']['administration']['maintenance']['blacklisted_info']); ?>
        </div>
        <!-- bottoni -->
        <div class='form_submit'>
            <input type="hidden" name="op" value="blacklisted">
            <input type="submit" value="<?php echo gdrcd_filter('out', $MESSAGE['interface']['forms']['submit']); ?>" />
        </div>
    </form>
</div>