<?php 
//load a summary of the items 
echo $this->load->view('requests/summary','', TRUE);?>

<?php 
// load the view containing the total amount quoted per day
echo $this->load->view('requests/line_graph','', TRUE);?>
<?php
//load the view showing a bar graph of the amount made for each item
 echo $this->load->view('requests/bar_graph','', TRUE);?>

