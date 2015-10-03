<?php

if ($status==0){
	$this->nurse_model->update_visit_sypmtom($symptoms_id,$visit_id,$description);
}else{

	$this->nurse_model->save_visit_sypmtom($symptoms_id,$visit_id,$status);
}

?>
