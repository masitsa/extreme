<?php

$this->lab_model->save_tests($result, $id);

echo
		"
			<div style='width: 60px;'>
					<table style='width: 60px;'>
						<tr class='info'>
							<td>".$result."</td>
						</tr>
				</table></div>
		";
?>