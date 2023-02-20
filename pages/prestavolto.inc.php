<?php
$lettere = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

?>
<div class="pagina_servizi_anagrafe">
<div class="page_title">
  <h2>ANAGRAFE E PRESTAVOLTI</h2>
</div>
<form action="main.php?page=prestavolto" method="post">
<table class="table-select" style="margin-left:auto; margin-right:auto;  align-items: center;">
  <thead><tr><th>Nome</th><th>Prestavolto</th><th>Iniziale</th><th>Genere</th><th>Razza</th><th></th></tr></thead>
  <tbody>
    <tr>
      <td><input type="text" name="nome" class="form_input"></td>
      <td><input type="text" name="prestavolto" class="form_input"></td>
      <td><select name="iniziale">
        <option value=""> - </option>
        <?php
        //creo il menu a discesa per le lettere iniziali
        foreach ($lettere as $lettera) {
          echo '<option value="'.$lettera.'">'.$lettera.'</option> ';
        }
        ?>
      </select></td>
      <td>
      <select name="genere">
        <option value=""> - </option>
          <option value="m" >
              <?php echo gdrcd_filter('out', $MESSAGE['register']['fields']['gender_m']); ?>
          </option>
          <option value="f" >
              <?php echo gdrcd_filter('out', $MESSAGE['register']['fields']['gender_f']); ?>
          </option>
      </select>
      </td>
      <td>
        <?php
        $result = gdrcd_query("SELECT id_razza, nome_razza FROM razza ORDER BY nome_razza",'result');
        ?>
        <select name="razza">
            <option value=""> - </option>
            <?php while ($row = gdrcd_query($result, 'fetch'))
            { ?>
                <option value="<?php echo $row['id_razza']; ?>">
                    <?php echo gdrcd_filter('out', $row['nome_razza']); ?>
                </option>
            <?php } ?>
        </select>
      </td>
      <td><input type="submit" value="Cerca" class="form_submit"></td>
  </tr>
</tbody>
</table>
</form>

<?php
    if(gdrcd_filter('get', $_REQUEST['nome'])){
      $add=" AND nome LIKE '%".gdrcd_filter('get', $_REQUEST['nome'])."%'";
    }
    if(gdrcd_filter('get', $_REQUEST['iniziale'])){
      $add.=" AND nome LIKE '".gdrcd_filter('get', $_REQUEST['iniziale'])."%'";
    }
    if(gdrcd_filter('get', $_REQUEST['iniziale'])){
      $add.=" AND prestavolto LIKE '%".gdrcd_filter('get', $_REQUEST['prestavolto'])."%'";
    }
    if(gdrcd_filter('get', $_REQUEST['genere'])){
      $add.=" AND sesso = '".gdrcd_filter('get', $_REQUEST['genere'])."'";
    }
    if(gdrcd_filter('get', $_REQUEST['razza'])){
      $add.=" AND id_razza = '".gdrcd_filter('get', $_REQUEST['razza'])."'";
    }
   $query ="SELECT url_img_chat, nome, cognome, prestavolto FROM personaggio WHERE 1 ".$add;
   $result = gdrcd_query($query, 'result');
?>
<div class="elenco_record_gioco">
  <table style="margin-left:auto; margin-right:auto; ">
    <thead>
    <tr>
        <th>Avatar Chat</th>
        <th class="ana-personaggio">Personaggio</th>
        <th>Prestavolto</th>
        <th class="ana-message">#</th></tr></thead>
    <tbody>
  <?php while($row=gdrcd_query($result, 'fetch')){ ?>
          <tr>
            <td align="center">
            <?php if (empty($row['url_img_chat']) || $row['url_img_chat'] == " ") { ?>
                  <img src="imgs/avatars/sigla.png" class="chat_avatar" style="width:<?php echo $PARAMETERS['settings']['chat_avatar']['width'];?>px; height:<?php echo $PARAMETERS['settings']['chat_avatar']['height'];?>px; border-radius:<?php echo $PARAMETERS['settings']['chat_avatar']['radius'];?>px;"  />
                <?php } else { ?>
                    <img src="<?php echo gdrcd_filter('out',$row['url_img_chat']); ?>" class="chat_avatar" style="width:<?php echo $PARAMETERS['settings']['chat_avatar']['width'];?>px; height:<?php echo $PARAMETERS['settings']['chat_avatar']['height'];?>px; border-radius:<?php echo $PARAMETERS['settings']['chat_avatar']['radius'];?>px;"  />
                <?php } ?>
              <!-- <img src="<?php echo gdrcd_bbcoder(gdrcd_filter('out',$row['url_img_chat'])); ?>" class="chat_avatar" style="width:<?php echo $PARAMETERS['settings']['chat_avatar']['width'];?>px; height:<?php echo $PARAMETERS['settings']['chat_avatar']['height'];?>px; border-radius:<?php echo $PARAMETERS['settings']['chat_avatar']['radius'];?>px;" /> -->
            </td>
            <td >
              <a href="main.php?page=scheda&pg=<?php echo gdrcd_filter('out',$row['nome']);?>">
                <?php echo gdrcd_filter('out',$row['nome']).' '.gdrcd_filter('out',$row['cognome']); ?>
              </a>
              </td>
              <td>
                <?php echo gdrcd_bbcoder(gdrcd_filter('out',$row['prestavolto'])); ?>
              </td>
              <td>
                <a href="main.php?page=messages_center&op=create&destinatario=<?php echo gdrcd_filter('out',$row['nome']);?>" class="link_invia_messaggio">
                <?php if (empty($PARAMETERS['names']['private_message']['image_file2'])===FALSE){ ?>
                  <img class="presenti_ico2" src="imgs/icons/mail_new3.png" alt="E-Mail" href="page=messages_center&op=create&destinatario=<?php echo gdrcd_filter('out',$row['nome']);?>"/>
                <?php } else {
                  echo gdrcd_filter('out',$MESSAGE['interface']['sheet']['send_message_to']['send']).' '.gdrcd_filter('out', strtolower($PARAMETERS['names']['private_message']['sing'])).' '.gdrcd_filter('out',$MESSAGE['interface']['sheet']['send_message_to']['to']).' '.gdrcd_filter('out',$row['nome']);
                }?>
              </a>
            </td>
         </tr>
    <?php
  }//while
  ?>
    </tbody>
  </table>
  </div>
 </div>
