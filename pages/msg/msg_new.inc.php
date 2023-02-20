<?php 
/******************************************************************************/
/******     Form di composizione di un messaggio e creazione gruppo      ******/
/******************************************************************************/
// prevalorizzazione destinatario
$destinatari='';
if(isset($_REQUEST['pg'])===true) $destinatari = gdrcd_filter('get',$_REQUEST['pg']);	
if(isset($_REQUEST['destinatario'])===true) $destinatari = gdrcd_filter('get',$_REQUEST['destinatario']);

$bNewGroup = isset($_REQUEST['op']) && gdrcd_filter('get',$_REQUEST['op']) == 'creategrp'? true:false; // si sta creando un nuovo gruppo?
?> 
<div class="panels_box">

        <form class="form_messaggi" action="main.php?page=messages_center" method="post">

			<!-- Tipo --> <?php //Campo tabellato ENUM "ON','OFF'?>
			<div class='form_label'><?php echo gdrcd_filter('out', $MESSAGE['interface']['messages']['type']['title']); ?></div>
			<div class='form_field'>
				<input type="radio" id="OFF" name="tpgroup" value="OFF"  style="width: auto; min-width: 0;" checked >
				<label for="OFF">OFF</label>
				<input type="radio" id="ON" name="tpgroup" value="ON" style="width: auto; min-width: 0;" >
				<label for="ON">ON</label>
			</div>
			<br>
			
			<!-- Mittente -->
			<?php
			// Creazione gruppo: utenza con il permesso, da configurazione, di inviare messaggi con mittente speciale
			if($bNewGroup && $_SESSION['permessi']>= gdrcd_filter('in', $PARAMETERS['setting']['msg']['group']['sender']['access_level'])){
				$listSender=array();
				$listCustomSender = array();
				$listCustomSender = explode(",", htmlspecialchars($PARAMETERS['setting']['msg']['group']['sender']['dssender'], ENT_QUOTES));
				foreach($listCustomSender as $customSender) { 
					if(trim($customSender)) array_push($listSender,trim($customSender));
				}
				if(count($listSender)>0){
					?>
					<div class='form_label'>
						<?php echo gdrcd_filter('out', $MESSAGE['interface']['messages']['sender']); ?>
					</div>
					<div class="form_field">
						<select class="" name="mittente">
							<option selected value=<?php echo gdrcd_filter('in', $_SESSION['login']);?>>Te stesso (Uso personale)</option>
							<?php 
							foreach($listSender as $index => $customSender) { 
								?>
								<option value="<?php echo $index; ?>" >
									<?php echo trim(stripslashes($customSender)); ?>
								</option>
								<?php
							}
							?>
						</select>
					</div>
					<?php 
				} // altrimenti il mittente di default è sempre l'utente loggato
			} // altrimenti il mittente di default è sempre l'utente loggato
			?>

			<!-- Destinatari -->
			<div class='form_label'>
				<?php echo gdrcd_filter('out', $MESSAGE['interface']['messages']['recipient']); ?>
			</div>
			<?php 
			if($bNewGroup ){
				// Gruppo: destinatari =  uno o più utenti - textbox libero
				if($_SESSION['permessi']>= gdrcd_filter('in', $PARAMETERS['setting']['msg']['group']['sender']['access_level'])){ ?>
					<div class='form_field'>
						<select id="sel_ctgroup" name="ctgroup" required>
							<option selected value='USERGROUP'><?php echo gdrcd_filter('out', $MESSAGE['interface']['messages']['multiple']['options']['recipients']); ?></option>
							<option value='GLOBAL'><?php echo gdrcd_filter('out', $MESSAGE['interface']['messages']['multiple']['options']['all']); ?></option>
							<option value='ONLINE'><?php echo gdrcd_filter('out', $MESSAGE['interface']['messages']['multiple']['options']['online']); ?></option>
						</select>
					</div>
				<?php } else {  ?>
					<input type="hidden" name="ctgroup" value="USERGROUP" />
				<?php }   ?>
				<div class='form_field'>
					<input  id="destinatari" type="text" name="destinatari" placeholder="<?php echo gdrcd_filter('out', $MESSAGE['interface']['messages']['multiple']['info']) ." (max ".gdrcd_filter('num',$PARAMETERS['setting']['msg']['group']['maxrecipients']).")";?>" value="<?php echo $destinatari ?>" <?php if($_SESSION['permessi']< gdrcd_filter('in', $PARAMETERS['setting']['msg']['group']['sender']['access_level'])) echo "required"; ?>/>
				</div>
				
				<?php if($_SESSION['permessi']>= gdrcd_filter('in', $PARAMETERS['setting']['msg']['group']['sender']['access_level'])){ ?>
				    <!-- Disabilita possibilità di rispondere -->
					<div class='form_label'>Disabilita possibilità di replica <br><small>(messaggi solo in lettura)</small></div>
					<div class='form_field'>
						<input type="checkbox" name="flreadonly" class="form_input" style="width: 50px;"/>
					</div>			 
				    <!-- Per gruppo globale: aggiungi automaticamente membri mancanti -->
					<div id="divfladdusers">
						<div class='form_label'>Aggiungi automaticamente destinatari mancanti<br><small>(permette di mantenere la conversazione globale. <br>Ad ogni nuova risposta un controllo verifica la presenza di partecipanti mancanti e li aggiunge al gruppo)</small></div>
						<div class='form_field'>
							<input id="fladdusers" type="checkbox" name="fladdusers" class="form_input" style="width: 50px;" />
						</div>	
					</div>
					<br>
				<?php }   ?>
				
				<?php 
			} else { 
                // messaggio a utente specifico - utente selezionato da select
                ?>
                <select name="destinatari" class="form_gestione_selectbox" required>
						<!-- PG -->
						<?php
						$query = "SELECT nome, cognome FROM personaggio WHERE permessi > -1 and nome <> '".gdrcd_filter('in', $_SESSION['login'])."' ORDER BY nome";
						$nomi = gdrcd_query($query, 'result'); ?>
						<option value=""></option>
						<?php while($option = gdrcd_query($nomi, 'fetch')) { 
							?>
							<option value="<?php echo gdrcd_filter('in',$option['nome']); ?>" <?php echo strtolower(gdrcd_filter('out', $option['nome']))==strtolower($destinatari)? 'selected':'';?>>
							<?php echo gdrcd_filter('out', $option['nome'])." ".gdrcd_filter('out', $option['cognome']); ?>
							</option>
							<?php 
						}
						gdrcd_query($nomi, 'free');
						?>
				</select>
				<input type="hidden" name="ctgroup" value="USER" />
				<?php 
			} ?>

			
			<?php 
			if($bNewGroup ){ 
				?>
				 <!-- Nome gruppo -->
				<div class='form_label'>
					<?php echo gdrcd_filter('out', $MESSAGE['interface']['messages']['subjectgroup']); ?>
				</div>
				<div class='form_field'>
					<input type="text" name="dsgruppo" />
				</div>	
				<?php 
			} 
			?>
			
			 <!-- Messaggio -->
			<div class='form_label'>
				<?php echo gdrcd_filter('out', $MESSAGE['interface']['messages']['body']); ?>
			</div>
			<div class='form_field'>
				<textarea class="textbox_new_msg" type="textbox" name="messaggio" id="messaggio" class="ed" required ></textarea>
			</div>
			<small><?php echo gdrcd_filter('out', $MESSAGE['interface']['help']['bbcode']); ?></small>	
			<br><br>
			
			<!-- Submit -->
			<div class='form_submit'>
				 <input type="hidden" name="op" value="new_msg" />
				 <input type="submit" value="<?php echo gdrcd_filter('out',$MESSAGE['interface']['forms']['submit']); ?>" />
			</div>
 
        </form>
		
</div>
<!-- Ritorno -->
<div class="link_back">
    <a href="main.php?page=messages_center"><?php echo gdrcd_filter('out', $MESSAGE['interface']['msggrp']['go_back']); ?></a>
</div>
<script type="application/javascript">
$(function() {
    $('#divfladdusers').hide();
    $('#fladdusers').prop('checked', false);
			
    $('#sel_ctgroup').change(function(){
		
		if($('#sel_ctgroup').val() == 'USERGROUP') $('#destinatari').show(); 	
		else {
			$('#destinatari').hide();
			$('#destinatari').val("");
		}
		if($('#sel_ctgroup').val() == 'GLOBAL') $('#divfladdusers').show();
		else {
			$('#divfladdusers').hide();
			$('#fladdusers').prop('checked', false);
		}
	
    });
});
</script>


