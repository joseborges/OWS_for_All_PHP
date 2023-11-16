<?php
/*This file is part of "OWS for All PHP" (Rolf Joseph)
  https://github.com/owsPro/OWS_for_All_PHP/
  A spinn-off for PHP Versions 5.4 to 8.2 from:
  OpenWebSoccer-Sim(Ingo Hofmann), https://github.com/ihofmann/open-websoccer.

  "OWS for All PHP" is is distributed in WITHOUT ANY WARRANTY;
  without even the implied warranty of MERCHANTABILITY
  or FITNESS FOR A PARTICULAR PURPOSE.

  See GNU Lesser General Public License Version 3 http://www.gnu.org/licenses/

*****************************************************************************/

$mainTitle = Message('entitylogging_navlabel');

if (!$admin['r_admin'] && !$admin['r_demo']) {
  echo '<p>'. Message('error_access_denied') . '</p>';
  exit;
}

if (!$show) {

  ?>

  <h1><?php echo $mainTitle; ?></h1>

  <p><?php echo Message('entitylogging_intro'); ?></p>

  <code>&lt;overview delete=&quot;true&quot; edit=&quot;true&quot; <strong>logging=&quot;true&quot; loggingcolumns=&quot;name,liga_id&quot;</strong>&gt;</code>

  <?php

  $datei = '../generated/entitylog.php';

  if (!file_exists($datei)) echo createErrorMessage(Message('alert_error_title'), Message('all_logging_filenotfound'));
  else {

    $datei_gr = filesize($datei);

    if (!$datei_gr) echo '<p>'. Message('empty_list') . '</p>';
    else {

      ?>

            <table class='table table-bordered table-striped' style='margin-top: 10px'>
              <tr>
                <th><?php echo Message('entitylogging_label_no'); ?></th>
                <th><?php echo Message('entitylogging_label_time'); ?></th>
                <th><?php echo Message('entitylogging_label_user'); ?></th>
                <th><?php echo Message('entitylogging_label_type'); ?></th>
                <th><?php echo Message('entitylogging_label_data'); ?></th>
              </tr>
              <?php

              $file = file($datei);
              $lines = count($file);
              $min = $lines - 50;
              if ($min < 0) $min = 0;

              for ($i = $lines-1; $i >= $min; $i--) {
				$line = $file[$i];

                $row = explode(';', $line);

				$n = $i + 1;
                echo '<tr>
                  <td><b>'. $n .'</b></td>
                  <td>'. $row[0] .'</td>
                  <td>'. ESC($row[1]) .' ('. ESC($row[2]) . ')</td>
                  <td>';

                  	if ($row[3] == 'edit') {
						echo '<span class=\'label label-info\'><i class=\'icon-white icon-pencil\'></i> '. Message('entitylogging_action_edit') . '</span>';
					} elseif ($row[3] == 'delete') {
						echo '<span class=\'label label-important\'><i class=\'icon-white icon-trash\'></i> '. Message('entitylogging_action_delete') . '</span>';
					} else {
						echo $row[3];
					}
                  echo '</td>
				  <td>'. Message('entity_' . $row[4]) .': { ';
                  	$itemFields = json_decode($row[5], TRUE);
                  	$firstField = TRUE;
                  	foreach ($itemFields as $fieldKey => $fieldValue) {
						if ($firstField) {
							$firstField = FALSE;
						} else {
							echo ', ';
						}

						echo $fieldKey . ': ' . ESC($fieldValue);

					}
				   echo ' }</td>
                </tr>';
              }

              ?>
            </table>

      <?php

    }

  }

}


?>
