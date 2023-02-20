<?php

/** Homepage
 * Markup e procedure della homepage
 * @author Blancks
 */

/*
 * Includo i Crediti
 */
require 'includes/credits.inc.php';

/*
 * Conteggio utenti online
 */
$users = gdrcd_query("SELECT COUNT(nome) AS online FROM personaggio WHERE ora_entrata > ora_uscita AND DATE_ADD(ultimo_refresh, INTERVAL 4 MINUTE) > NOW()");


?>
<div id="main">
    <table class="login-table">
        <tbody>
            <tr>
                <td>
                    <!-- <div class="documenti"></div> -->

                    <!-- <a data-toggle="tooltip" data-placement="right" title="Tooltip on right" href="documentazione.html" onclick="window.open(\'documentazione.html\',\'newwindow\', \'width=768,height=430\'); return false;"><span id="documenti"></span></a> -->
                    <!-- <a data-toggle="tooltip" data-placement="right" title="Tooltip on right" href="http://' . $PARAMETERS['info']['site_url'] . '/pages/frame/documentzione/documentazione.html" onclick='window.open("http://' . $PARAMETERS['info']['site_url'] . '/pages/frame/documentzione/documentazione.html\',\'newwindow\', \'width=768,height=430\' "); return false;" ><img class="icon-lateral" src="../../../themes/'.$PARAMETERS['themes']['current_theme'].'/imgs/custom_imgs/icons/DOCUMENTAZIONE.png'.'"/></a> -->
                    <!-- <div class="box-documentazione">
                        <a

                            onclick='window.open(
                                "<?php echo $PARAMETERS["info"]["site_url"] ; ?>pages/frame/documentazione/documentazione.html",
                                "newwindow",
                                "width=850,height=495"

                            )';
                            onmouseover="this.style.cursor='pointer';"
                            return false;
                        >
                        <img src="themes/<?php echo $PARAMETERS['themes']['current_theme']; ?>/imgs/custom_imgs/login_documentazione.png" alt="" />
                        </a>
                    </div> -->
                    <div class="box-iscrizione">
                        <a href="index.php?page=iscrizione">
                            <img src="themes/<?php echo $PARAMETERS['themes']['current_theme']; ?>/imgs/custom_imgs/login_iscrizione.png" alt="" />
                        </a>
                    </div>

                    <!-- </div> -->
                </td>
                <td>
                    <div class="div-form">
                        <form action="login.php" id="do_login" method="post"
                                <?php if ($PARAMETERS['mode']['popup_choise'] == 'ON') { echo ' onsubmit="check_login(); return false;"';} ?>
                            >
                                <div>
                                    <!-- <span class="form_label"><label for="username"><?php echo $MESSAGE['homepage']['forms']['username']; ?></label></span> -->
                                    <input type="text" id="username" name="login1"/>
                                </div>
                                <div>
                                    <!-- <span class="form_label"><label for="password"><?php echo $MESSAGE['homepage']['forms']['password']; ?></label></span> -->
                                    <input type="password" id="password" name="pass1"/>
                                </div>
                                <?php if (!empty($PARAMETERS['themes']['available']) and count($PARAMETERS['themes']['available']) > 1): ?>
                                    <div>
                                        <!-- <span class="form_label"><label for="theme"><?= gdrcd_filter('out', $MESSAGE['homepage']['forms']['theme_choice']) ?></label></span> -->
                                        <select name="theme" id="theme">
                                            <?php
                                            foreach ($PARAMETERS['themes']['available'] as $k => $name) {
                                                echo '<option value="' . gdrcd_filter('out', $k) . '"';
                                                if ($k == $PARAMETERS['themes']['current_theme']) {
                                                    echo ' selected="selected"';
                                                }
                                                echo '>' . gdrcd_filter('out', $name) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                <?php endif; ?>
                                <!-- <?php if ($PARAMETERS['mode']['popup_choise'] == 'ON') { ?>
                                    <div>
                                        <span class="form_label"><label for="allow_popup"><?php echo $MESSAGE['homepage']['forms']['open_in_popup']; ?></label></span>
                                        <input type="checkbox" id="allow_popup"/>
                                        <input type="hidden" value="0" name="popup" id="popup">
                                    </div>
                                <?php } ?> -->
                                <input
                                    type="image"
                                    src="<?php echo $PARAMETERS["info"]["site_url"] ; ?>imgs/icons/login_entra.png"
                                    class="btn-submit"
                                />
                        </form>
                    </div>
                    <div class="side_modules reset_password">
                        <?php
                            // Include il modulo di reset della password
                            include (__DIR__ . '/reset_password.inc.php');
                        ?>
                    </div>
                </td>
                <td>
                <div class="box-documentazione">
                        <a

                            onclick='window.open(
                                "<?php echo $PARAMETERS["info"]["site_url"] ; ?>pages/frame/documentazione/documentazione.html",
                                "newwindow",
                                "width=850,height=495"

                            )';
                            onmouseover="this.style.cursor='pointer';"
                            return false;
                        >
                        <img src="themes/<?php echo $PARAMETERS['themes']['current_theme']; ?>/imgs/custom_imgs/login_documentazione.png" alt="" />
                        </a>
                    </div>
                    <!-- <div class="crediti"></div> -->
                </td>
            </tr>
        </tbody>
    </table>
</div>
