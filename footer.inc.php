<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="/gdr/includes/corefunctions.js"></script>
<!--<script type="text/javascript" src="includes/gdrcdskills.js"></script>-->
<script type="text/javascript" src="/gdr/includes/modal.js"></script>
<script type="text/javascript" src="/gdr/includes/fullcalendar/lib/main.js"></script>
<script src='/gdr/includes/popper.min.js'></script>
<script src='/gdr/includes/tooltip.min.js'></script>
<?php
/** * Abilitazione tooltip
 * @author Blancks
 */
if($PARAMETERS['mode']['map_tooltip'] == 'ON' || $PARAMETERS['mode']['user_online_state'] == 'ON') { ?>
    <script type="text/javascript">
        var tooltip_offsetX = <?php echo $PARAMETERS['settings']['map_tooltip']['offset_x']; ?>;
        var tooltip_offsetY = <?php echo $PARAMETERS['settings']['map_tooltip']['offset_y']; ?>;
    </script>
    <script type="text/javascript" src="/gdr/includes/tooltip.js"></script>
    <?php
}
/** * Caricamento script per il titolo "lampeggiante" per i nuovi pm
 * @author Blancks
 */
if($PARAMETERS['mode']['alert_pm_via_pagetitle'] == 'ON') {
    echo '<script type="text/javascript" src="/gdr/includes/changetitle.js"></script>';

}
/** * Caricamento script per la scelta popup nel login
 * @author Blancks
 */
if($PARAMETERS['mode']['popup_choise'] == 'ON') {
    echo '<script type="text/javascript" src="/gdr//includes/popupchoise.js"></script>';
}
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src='/gdr/includes/custom.js'></script>
<!--<script type="text/javascript">
    setTimeout("self.location.href.reload();",<?php //echo (int) $_GET['ref'] * 1000; ?>);
</script-->
</body>
</html>
<?php
/*Chiudo la connessione al database*/
gdrcd_close_connection($handleDBConnection);

/**    * Per ottimizzare le risorse impiegate le liberiamo dopo che non ne abbiamo pi� bisogno
 * @author Blancks
 */
unset($MESSAGE);
unset($PARAMETERS);
?>
