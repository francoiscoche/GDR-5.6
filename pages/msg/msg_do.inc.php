<?php
$msgKO = ""; $pageToRedirect =""; $idgroupConvInProgress=0;
$flGESTCUSTOM = false; // true se si sta operando come mittente speciale custom
$OP = "";
$FROM = ""; $TO = ""; $DSGROUP = ""; $CTGROUP = ""; $TPGROUP =""; $MSG = ""; 
$IDGROUP = 0; $TPGROUP="";

//preparazione parametri
$OP = (isset($_POST['op'])===true && !Empty(gdrcd_filter('in',$_POST['op'])))? trim(gdrcd_filter('in',$_POST['op'])) : "";
$TPGROUP = (isset($_POST['tpgroup'])===true && gdrcd_filter('in',$_POST['tpgroup'])=='ON')? "ON" : "OFF";
$CTGROUP = (isset($_POST['ctgroup'])===true && !Empty(gdrcd_filter('in',$_POST['ctgroup'])))? trim(strip_tags(gdrcd_filter('in',$_POST['ctgroup']))) : "USERGROUP";
$FROM = (isset($_POST['mittente'])===true)? trim(gdrcd_filter('in',$_POST['mittente'])) : gdrcd_filter('in',$_SESSION['login']);
$TO = (isset($_POST['destinatari'])===true && !Empty(gdrcd_filter('in',$_POST['destinatari'])))? trim(strip_tags(gdrcd_filter('in',$_POST['destinatari']))) : "";
$DSGROUP = (isset($_POST['dsgruppo'])===true && !Empty(gdrcd_filter('in',$_POST['dsgruppo'])))? trim(htmlspecialchars(strip_tags(gdrcd_filter('in',$_POST['dsgruppo'])), ENT_QUOTES)) : "";
$MSG = (isset($_POST['messaggio'])===true && !Empty(gdrcd_filter('in',$_POST['messaggio'])))? trim(htmlspecialchars(gdrcd_filter('in',$_POST['messaggio']), ENT_QUOTES)) : "";
$IDGROUP = (isset($_POST['group'])===true && gdrcd_filter('num',$_POST['group']) > 0)? gdrcd_filter('num',$_POST['group']) : 0;
$FLREADONLY = (isset($_POST['flreadonly']))=== true && $_SESSION['permessi'] >= gdrcd_filter('in',$PARAMETERS['setting']['msg']['group']['sender']['access_level'])? 'S': 'N';
$FLALLUSERS = (isset($_POST['fladdusers']))=== true && $_SESSION['permessi'] >= gdrcd_filter('in',$PARAMETERS['setting']['msg']['group']['sender']['access_level'])? 1: 0;
$MEMBER = (isset($_POST['membro'])===true && !Empty(gdrcd_filter('in',$_POST['membro'])))? trim(strip_tags(gdrcd_filter('in',$_POST['membro']))) : "";


// SICUREZZE: variabili di controllo per verifica che utente può effettuare le operazioni richieste (reply, aggiungi, rimuovi, rinomina gruppo...
$flSecView=false; // true se l'utente è membro della conversazione - può quindi leggerne i messaggi inviati al suo interno
$flSecReply=false; // true se l'utente è membro della conversazione e il gruppo non è in only read
if($IDGROUP >0){	

    if(($MEMBER=='CUSTOM' || strtolower($FROM) != strtolower(gdrcd_filter('in',$_SESSION['login']))) && $_SESSION['permessi'] >= gdrcd_filter('in',$PARAMETERS['setting']['msg']['group']['sender']['access_level'])){
		// gestione messaggi come mittente speciale
		$whereFilter = "membri.tpuser ='CUSTOM' ";
		$flGESTCUSTOM = true;
		$listGlobalSender = explode(",", htmlspecialchars($PARAMETERS['setting']['msg']['group']['sender']['dssender'], ENT_QUOTES));
		$currentUser = $listGlobalSender[(int)$FROM]; 	
	} else {
		// gesntione messaggi come utente loggato
		$whereFilter = "membri.nome ='".gdrcd_filter('in',$_SESSION['login'])."' ";
		$flGESTCUSTOM = false;
		$currentUser = gdrcd_filter('in',$_SESSION['login']);
	}

    // recupero conversazione sulla quale si stanno effettuando operazioni (inserimento nuovo messaggio, abbandono, modifica nome, etc...
	$query  = "SELECT *, (SELECT nomesender FROM msg 
				LEFT JOIN msggrpuser ON msggrpuser.idgroup= msg.idgroup and msggrpuser.nome= msg.nomesender
				WHERE msg.idgroup =".$IDGROUP." and msggrpuser.idgrpuser IS NOT NULL and msggrpuser.dtstart<=NOW() and msggrpuser.dtend >= NOW() 
				order by dtsend limit 1) as nomecreator 
				FROM msggrpuser as membri
				LEFT JOIN msggrp as gruppo ON gruppo.idgroup = membri.idgroup 
				WHERE ".$whereFilter." and gruppo.idgroup=".$IDGROUP." and membri.dtstart<= NOW() and membri.dtend >= NOW() limit 1; ";
	$result = gdrcd_query($query,'result');
	if (gdrcd_query($result, 'num_rows') <= 0) {
		$flSecReply = false;
		$flSecView = false;
	} else {
		$row = gdrcd_query($result, 'fetch');
		$tpgroup = gdrcd_filter('in',$row['tpgroup']);
		$ctgroup = gdrcd_filter('in',$row['ctgroup']);
		$idgrpuser = gdrcd_filter('num',$row['idgrpuser']);
		$nomecreator = gdrcd_filter('in', $row['nomecreator']); // membro che ha mandato il primo messaggio ed è ancora membro del gruppo 
		$flpin = gdrcd_filter('num',$row['flpin']);	
		$fladdusers = gdrcd_filter('num',$row['fladdusers'])==1? true:false;
		if($row['flreadonly']=='S' && $_SESSION['permessi'] < gdrcd_filter('in',$PARAMETERS['setting']['msg']['group']['sender']['access_level'])) $flSecReply = false;	 
		else $flSecReply = true;
		$flSecView = true;		
	}
	gdrcd_query($result, 'free');
	
}


if(isset($_POST['op']) === false || Empty($OP) ){
	$msgKO = gdrcd_filter('out', $MESSAGE['error']['unknown_operation']);
}
//------------------------------------------------------
// Nuovo messaggio, creazione nuovo gruppo 
else if($OP=="new_msg"){	

	// TIPO mittente (pg loggato o mittente speciale configurato)	
	if(strtolower($FROM) == strtolower(gdrcd_filter('in',$_SESSION['login'])) || $_SESSION['permessi'] < gdrcd_filter('in',$PARAMETERS['setting']['msg']['group']['sender']['access_level'])){
		$flGESTCUSTOM = false;
		$tpuser = "USER";
		$nomesender = gdrcd_filter('in',$_SESSION['login']); 		
	} else {
		$flGESTCUSTOM = true;	
		$tpuser = "CUSTOM";
		$listGlobalSender = explode(",", htmlspecialchars($PARAMETERS['setting']['msg']['group']['sender']['dssender'], ENT_QUOTES));
		$nomesender = $listGlobalSender[(int)$FROM]; 	
	}
	
	// Oggetto/descrizione conversazione
	$dsgroup = $DSGROUP;
	if(Empty($dsgroup)){
		if($CTGROUP == "USER") $dsgroup = "Conversazione"." ". $TPGROUP ;
		else if($CTGROUP == "USERGROUP") $dsgroup = "Gruppo"." ". $TPGROUP;
		else if ($CTGROUP == "GLOBAL" || $CTGROUP == "ONLINE") $dsgroup = "Gruppo globale" ;		
	}
	
	// Categoria conversazione
	if(substr($CTGROUP, 0, 4) == "USER"){
		/*** $CTGROUP == "USER" oppure $CTGROUP == "USERGROUP"     
		/*   Conversazione singola o gruppo
		*/

		// Destinatari
		$arr_pgList = array();
		$query = "SELECT nome FROM personaggio where esilio is null or esilio < CURDATE() ;";
		$result = gdrcd_query($query, 'result');
		while($record = gdrcd_query($result , 'fetch')){
			$arr_pgList[] = ucfirst(trim(gdrcd_filter('in',$record['nome'])));
		}
		gdrcd_query($result , 'free');	
		
		$arr_recipientsList = array();
		$recipients = array();	
		$recipients = explode(",", $TO);	
		foreach($recipients as $recipient) { 
		    $recipient=ucfirst(trim($recipient));
		    //destinatari consentiti: tutti gli altri e se stessi solo quando non si è già il mittente
		    $bRecipientAllowed = strtolower($recipient)!= strtolower($_SESSION['login']) || ($tpuser != "USER" && strtolower($recipient)==strtolower(gdrcd_filter('in',$_SESSION['login'])));
			if($recipient && $bRecipientAllowed && in_array($recipient, $arr_pgList) && !(in_array($recipient, $arr_recipientsList))){
				array_push($arr_recipientsList,$recipient);
			}
		}	 
	
		// Controlli & inserimento
		if(Empty($MSG) || Empty($nomesender) || count($arr_recipientsList)<=0 ) $msgKO = "Uno o più dati obbligatori errati o mancanti";
		else if(count($arr_recipientsList) > gdrcd_filter('num',$PARAMETERS['setting']['msg']['group']['maxrecipients']))  $msgKO = "Impossibile procedere. Numero massimo destinatari superato (max: ".gdrcd_filter('num',$PARAMETERS['setting']['msg']['group']['maxrecipients']).")";
		else {
			
			//se messaggio tra utenti, conversazione a 2 (=1 destinatario) -> verifica presenza conversazione in corso
			if(!$flGESTCUSTOM && $CTGROUP == 'USER'){
				$participants = array(gdrcd_filter('in',$_SESSION['login']), $arr_recipientsList[0]);
				$query="SELECT msggrpuser.idgroup
						FROM msggrp LEFT JOIN msggrpuser ON msggrpuser.idgroup=msggrp.idgroup and msggrp.ctgroup='USER' and tpgroup ='".$TPGROUP."' 
						GROUP BY msggrpuser.idgroup 
						HAVING COUNT(msggrpuser.nome)=2 and GROUP_CONCAT(DISTINCT msggrpuser.nome SEPARATOR '|') in ('".$participants[0]."|".$participants[1]."','".$participants[1]."|".$participants[0]."') 
						ORDER BY msggrpuser.idgroup DESC 
						LIMIT 1";
				$recordConvInProgress = gdrcd_query($query);
				$idgroupConvInProgress = gdrcd_filter('num', $recordConvInProgress['idgroup']);
			}		

			if(!$flGESTCUSTOM && $idgroupConvInProgress>0){
				/** RISPOSTA a conversazione già avviata
				*/
				// inserimento messaggio di risposta nella conversazione già esistente
				$query="INSERT INTO msg (idgroup, nomesender, message, bynome) VALUES ('".$idgroupConvInProgress."','".gdrcd_filter('in',$_SESSION['login'])."', '".$MSG."','".gdrcd_filter('in',$_SESSION['login'])."');";
				$ret =gdrcd_query($query);
				// aggiornamento data ultima lettura
				if($ret){
					$query="UPDATE msggrpuser SET dtlastread = NOW() WHERE nome ='".gdrcd_filter('in',$_SESSION['login'])."' and idgroup=".$idgroupConvInProgress;
					$retLastRead =gdrcd_query($query);
				}				
				
			} else {
				/** CREAZIONE nuova conversazione/gruppo
				*/
				// creazione conversazione/gruppo
				$retQuery = gdrcd_query("INSERT INTO msggrp (dsgroup,tpgroup,ctgroup,flreadonly) VALUES ('".$dsgroup."','". $TPGROUP."','". $CTGROUP."','". $FLREADONLY."');");  
				$lastId = gdrcd_query($retQuery, 'last_id'); // ultimo id inserito
				if($retQuery){
					// usergroup - inserimento membri della conversazione
					gdrcd_query("INSERT INTO msggrpuser (idgroup, tpuser, nome) VALUES ('". $lastId."','".$tpuser."','".$nomesender."');");
					foreach ($arr_recipientsList as $nome) {
						$recipient = trim($nome);
						if($recipient) gdrcd_query("INSERT INTO msggrpuser (idgroup, tpuser, nome) VALUES ('". $lastId."','USER','".$recipient."');");
					}			
					// inserimento messaggio
					$query="INSERT INTO msg (idgroup, nomesender , message, bynome) VALUES ('".$lastId."','".$nomesender."', '".$MSG."','".gdrcd_filter('in',$_SESSION['login'])."');";	
					$retQuery = gdrcd_query($query);
				}
				// aggiornamento data ultima lettura pm 
				if($retQuery && !$flGESTCUSTOM ){
					$query="UPDATE msggrpuser SET dtlastread = NOW() WHERE nome ='".gdrcd_filter('in',$_SESSION['login'])."' and idgroup=".$lastId;
					gdrcd_query($query);
				}
			}
			
		}			
		
	} else {
		/*** $CTGROUP == "GLOBAL" oppure $CTGROUP == "ONLINE"
		/*   gruppo globale o utenti online
		*/
		
		// Controlli & inserimento
		if(Empty($MSG) || Empty($nomesender)) $msgKO = "Uno o più dati obbligatori errati o mancanti";
		else if ($_SESSION['permessi'] < gdrcd_filter('in',$PARAMETERS['setting']['msg']['group']['sender']['access_level'])) $msgKO = "Operazione non consentita";
		else {
			// creazione gruppo
			$retQuery= gdrcd_query("INSERT INTO msggrp (dsgroup, tpgroup, ctgroup, flreadonly, fladdusers) VALUES ('".$dsgroup."','". $TPGROUP ."','". $CTGROUP."','". $FLREADONLY."','". $FLALLUSERS."');"); 
			$lastId = gdrcd_query($retQuery, 'last_id'); // ultimo id inserito
			if($retQuery){
				// usergroup - inserimento membri del gruppo
				if($tpuser=='CUSTOM') gdrcd_query("INSERT INTO msggrpuser (idgroup, tpuser, nome) VALUES ('". $lastId."','".$tpuser."','".$nomesender."');");
				$query = "SELECT nome FROM personaggio";
				if($CTGROUP == "ONLINE")  $query .= " " . "WHERE personaggio.ora_entrata > personaggio.ora_uscita AND DATE_ADD(personaggio.ultimo_refresh, INTERVAL 4 MINUTE) > NOW()";
				$result = gdrcd_query($query, 'result');
				while($record = gdrcd_query($result , 'fetch')){
					$query="INSERT INTO msggrpuser (idgroup, tpuser, nome) VALUES ('".$lastId."','USER','".gdrcd_filter('in',$record['nome'])."');";
					gdrcd_query($query);
				}	
				gdrcd_query($result , 'free');	
				// inserimento messaggio
				$query="INSERT INTO msg (idgroup, nomesender, message, bynome) VALUES ('".$lastId."','".$nomesender."','".$MSG."','".gdrcd_filter('in',$_SESSION['login'])."');";
				gdrcd_query($query);
			}
			
		}
		
	} 	
	
	$pageToRedirect = "&op=read&group=";
	$pageToRedirect .= isset($idgroupConvInProgress) && $idgroupConvInProgress>0? $idgroupConvInProgress:$lastId;	
	if($flGESTCUSTOM) $pageToRedirect .="&sender=custom";
}
//------------------------------------------------------
// Operazione su conversazione/gruppo: 
// Rispondi
else if($OP=="reply_msg"){

	if(Empty($MSG) || $IDGROUP<=0) $msgKO = "Uno o più dati obbligatori errati o mancanti";
	else if($ctgroup == 'SYSTEM') $msgKO = "Operazione non consentita";
	else if (!$flSecReply) $msgKO = "Operazione non consentita";
	else {
		
		// inserimento messaggio di risposta
		$query="INSERT INTO msg (idgroup, nomesender, message, bynome) VALUES ('".$IDGROUP."','".$currentUser."', '".$MSG."','".gdrcd_filter('in',$_SESSION['login'])."');";
		$ret =gdrcd_query($query);
		// aggiornamento data ultima lettura
		$query="UPDATE msggrpuser SET dtlastread = NOW() WHERE nome ='".$currentUser."' and idgroup=".$IDGROUP;
		$retLastRead =gdrcd_query($query);	

		// aggiunta dei membri mancanti
		if($fladdusers){
			$query = "SELECT personaggio.nome FROM personaggio LEFT JOIN msggrpuser ON msggrpuser.nome = personaggio.nome and idgroup = ".$IDGROUP." and msggrpuser.dtstart<=NOW() and msggrpuser.dtend >= NOW() WHERE permessi > -1 and (esilio is null or esilio < CURDATE()) and msggrpuser.nome IS NULL ORDER BY personaggio.nome";
			$userMissList = gdrcd_query($query, 'result'); 	
			if(gdrcd_query($userMissList, 'num_rows') > 0 ){	
				while($userMiss = gdrcd_query($userMissList, 'fetch')) { 
					$query="INSERT INTO msggrpuser (idgroup, tpuser, nome) VALUES ('".$IDGROUP."','USER','".gdrcd_filter('in', $userMiss['nome'])."');";
					gdrcd_query($query);
				}
			}
			gdrcd_query($userMissList, 'free');				
		}
		
	}

	$pageToRedirect = "&op=read&group=".$IDGROUP;	
	if($flGESTCUSTOM) $pageToRedirect .="&sender=custom";
	
}
//------------------------------------------------------
// Operazione su conversazione/gruppo: 
// Eliminazione logica  - solo per utenti - no per mittenti custom
else if( $OP=="group_del" && $IDGROUP>0 ){
	
	if($IDGROUP<=0) $msgKO = "Uno o più dati obbligatori errati o mancanti";
    else if (!$flSecView) $msgKO = "Operazione non consentita";
	else {		
		$query="UPDATE msggrpuser SET dtdel= NOW() WHERE idgroup=".$IDGROUP." and nome='".$currentUser."' and msggrpuser.dtstart<=NOW() and msggrpuser.dtend >= NOW()";
		$ret =gdrcd_query($query);	
	}
	
	$pageToRedirect = "&what=".strtolower($tpgroup);
}
//------------------------------------------------------
// Operazione su conversazione/gruppo: 
// Aggiungi membro al gruppo - solo per conversazioni di gruppo
else if( $OP=="add_groupmember" ){

	if($IDGROUP<=0 || Empty($MEMBER)) $msgKO = "Uno o più dati obbligatori errati o mancanti";
	else if($ctgroup == 'SYSTEM' || $ctgroup == 'USER') $msgKO = "Operazione non consentita";
	else if (!$flSecView) $msgKO = "Operazione non consentita";
	else {
		//verifica esistenza nome pg da aggiungere + verifica che non sia ancora membro
		$query = "SELECT personaggio.nome as nome, msggrpuser.nome as membro FROM personaggio LEFT JOIN msggrpuser ON msggrpuser.nome = personaggio.nome and msggrpuser.idgroup =  ".$IDGROUP."  and msggrpuser.dtstart<=NOW() and msggrpuser.dtend >= NOW() WHERE permessi > -1 and personaggio.nome= '".$MEMBER."' and msggrpuser.nome IS NULL";
		$record = gdrcd_query($query);
		$notMember = strtolower(gdrcd_filter('in', $record['nome'])) == strtolower($MEMBER)? true:false;
		if($notMember){
			//il pg selezionato non è ancora membro del gruppo -> viene aggiunto
			$query="INSERT INTO msggrpuser (idgroup, tpuser, nome) VALUES ('".$IDGROUP."','USER','".$MEMBER."');";
			$ret =gdrcd_query($query);
			
			// messaggio di sistema: log aggiunta membro
			$MSGsystem = $MEMBER." è stato aggiunto alla conversazione da " .$currentUser;
			$query="INSERT INTO msg (idgroup, nomesender , message, bynome) VALUES "."(".$IDGROUP.",'SYSTEM', '".$MSGsystem."','".gdrcd_filter('in',$_SESSION['login'])."');";	
			$retQuery = gdrcd_query($query);
			
		} else  $msgKO = "L'utente è già membro del gruppo";
		
	}

	$pageToRedirect = "&op=read&group=".$IDGROUP;	
	if($flGESTCUSTOM) $pageToRedirect .="&sender=custom";

}
//------------------------------------------------------
// Operazione su conversazione/gruppo: 
// Abbandona gruppo - solo per conversazioni di gruppo
else if( $OP=="group_leave" ){

	if($IDGROUP<=0) $msgKO = "Uno o più dati obbligatori errati o mancanti";
	else if($ctgroup != 'USERGROUP') $msgKO = "Operazione non consentita";
	else if (!$flSecView) $msgKO = "Operazione non consentita";
	else {
		
		if($MEMBER=='CUSTOM'){
			// recupero del nome del mittente speciale
			$record = gdrcd_query("SELECT nome FROM msggrpuser WHERE tpuser='CUSTOM' and idgroup='".$IDGROUP."'");	
			$nomeMembro = $record['nome'];
		} else $nomeMembro = gdrcd_filter('in',$_SESSION['login']);			
		// messaggio di sistema: log abbandono
		$MSGsystem = $nomeMembro." ha abbandonato la conversazione";
		$query="INSERT INTO msg (idgroup, nomesender , message, dtsend, bynome) VALUES "."(".$IDGROUP.",'SYSTEM', '".$MSGsystem."', NOW(),'".gdrcd_filter('in',$_SESSION['login'])."');";	
		$retQuery = gdrcd_query($query);
		
		// abbandono
		$query = "UPDATE msggrpuser set dtend = NOW() WHERE idgrpuser=".$idgrpuser.";";
		$ret = gdrcd_query($query);
		
	}

	$pageToRedirect = "&what=".strtolower($tpgroup);
	if($flGESTCUSTOM) $pageToRedirect .="&sender=custom";

}
//------------------------------------------------------
// Operazione su conversazione/gruppo: 
// Rimuovi da gruppo - solo per conversazioni di gruppo
// Per figure abilitare da configurazione + creatore = membro del gruppo, tra quelli ad ora appartenenti al gruppo, che per primo ha inviato un messaggio nel gruppo
else if( $OP=="remove_groupmember" ){

	if($IDGROUP<=0 || Empty($MEMBER)) $msgKO = "Uno o più dati obbligatori errati o mancanti";
	else if (!$flSecView) $msgKO = "Operazione non consentita";
	else if($ctgroup == 'SYSTEM' || $ctgroup == 'USER')  $msgKO = "Operazione non consentita";
	else if($nomecreator!=gdrcd_filter('in',$_SESSION['login']) && $_SESSION['permessi'] < gdrcd_filter('in',$PARAMETERS['setting']['msg']['group']['sender']['access_level'])) $msgKO = "Operazione non consentita";
	else {

		// messaggio di sistema: log abbandono
		$MSGsystem = $MEMBER." è stato rimosso dalla conversazione da " .$currentUser;
		$query="INSERT INTO msg (idgroup, nomesender , message, bynome) VALUES "."(".$IDGROUP.",'SYSTEM', '".$MSGsystem."','".gdrcd_filter('in',$_SESSION['login'])."');";	
		$retQuery = gdrcd_query($query);
	
		$query = "UPDATE msggrpuser set dtend = DATE_SUB(NOW(), INTERVAL 10 SECOND) WHERE nome='".$MEMBER."' and idgroup=".$IDGROUP." and dtstart<=NOW() and dtend >= NOW() ;";
		$ret = gdrcd_query($query);
			
	}

	$pageToRedirect = "&op=read&group=".$IDGROUP;
	if($flGESTCUSTOM) $pageToRedirect .="&sender=custom";

}
//------------------------------------------------------
// Operazione su conversazione/gruppo: 
// Modifica nome
else if( $OP=="dsgroup_modify" ){

	if($IDGROUP<=0 || Empty($DSGROUP)) $msgKO = "Uno o più dati obbligatori errati o mancanti";
	else if (!$flSecView || !$flSecReply) $msgKO = "Operazione non consentita";
	else if($ctgroup == 'SYSTEM')  $msgKO = "Operazione non consentita";
	else {

		// Messaggio di sistema
		$MSGsystem = "Il nome della conversazione è stato cambiato da " .$currentUser;
		$query="INSERT INTO msg (idgroup, nomesender , message, bynome) VALUES "."(".$IDGROUP.",'SYSTEM', '".$MSGsystem."','".gdrcd_filter('in',$_SESSION['login'])."');";	
		$ret = gdrcd_query($query);
		
		$query = "UPDATE msggrp set dsgroup = '".$DSGROUP."' WHERE idgroup=".$IDGROUP.";";
		$ret = gdrcd_query($query);
		
	}

	$pageToRedirect = "&op=read&group=".$IDGROUP;
	if($flGESTCUSTOM) $pageToRedirect .="&sender=custom";

}
//------------------------------------------------------
// Operazione su conversazione/gruppo: 
// Abilita/disabilita aggiunta membri mancanti in gruppo globale - solo per ruoli abilitati
else if( $OP=="group_addallusers" ){

	if($IDGROUP<=0) $msgKO = "Uno o più dati obbligatori errati o mancanti";
	else if ($ctgroup != "GLOBAL") $msgKO = "Operazione non consentita";
	else {
		$query = "UPDATE msggrp set fladdusers = ".$FLALLUSERS." WHERE idgroup=".$IDGROUP.";";
		$ret = gdrcd_query($query);		
	}
	
	$pageToRedirect = "&op=read&group=".$IDGROUP;
	if($flGESTCUSTOM) $pageToRedirect .="&sender=custom";

}
//------------------------------------------------------
// Operazione su conversazione/gruppo:  
// pin/unpin conversazione - solo per utenti  - no per mittenti custom	
else if( $OP=="group_pinchange" ){

	if($IDGROUP<=0) $msgKO = "Uno o più dati obbligatori errati o mancanti";
	else {
		
		$flpinUpd = $flpin==1? 0:1;
		$query = "UPDATE msggrpuser set flpin = '".$flpinUpd."' WHERE idgroup=".$IDGROUP." and nome='".$currentUser."';";
		$ret = gdrcd_query($query);
		
	}
	
	$pageToRedirect = "&what=".strtolower($tpgroup);

} 
//------------------------------------------------------
// Operazione su tutte conversazioni/gruppi per tipo:
// Segna tutte le conversazioni/gruppi on/off come letti  - solo per utenti - no per mittenti custom
else if( $OP=="group_allRead" ){

	if(!Empty($TPGROUP)) $query="UPDATE msggrpuser LEFT JOIN msggrp on msggrpuser.idgroup= msggrp.idgroup SET dtlastread= NOW() WHERE msggrp.tpgroup='".$TPGROUP."' and msggrpuser.nome='".gdrcd_filter('in',$_SESSION['login'])."' and msggrpuser.dtstart<=NOW() and msggrpuser.dtend >= NOW()"; 		
	else $query="UPDATE msggrpuser SET dtlastread= NOW() WHERE nome='".gdrcd_filter('in',$_SESSION['login'])."' and msggrpuser.dtstart<=NOW() and msggrpuser.dtend >= NOW()";
	$ret =gdrcd_query($query);
	
	$pageToRedirect = "&what=".strtolower($TPGROUP);

} 
//------------------------------------------------------
// Operazione non riconosciuta
else {
	
	$msgKO = gdrcd_filter('out', $MESSAGE['error']['unknown_operation']);
	
}

$_POST = array();

// redirect 
if(Empty($msgKO)) {
	echo '<div class="warning success">Opersazione eseguita con successo</div>';
	gdrcd_redirect("main.php?page=messages_center".$pageToRedirect ,0);
} else echo '<div class="warning">'.$msgKO.'</div>';

?>

 <div class="link_back">
    <a href="main.php?page=messages_center"><?php echo gdrcd_filter('out', $MESSAGE['interface']['messages']['go_back']); ?></a>
</div>