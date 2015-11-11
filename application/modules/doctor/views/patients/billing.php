<?php
$rs = $this->nurse_model->check_visit_type($visit_id);
if(count($rs)>0){
  foreach ($rs as $rs1) {
    # code...
      $visit_t = $rs1->visit_type;
  }
}
$order = 'service_charge.service_charge_name';

$where = 'service_charge.service_id = service.service_id AND (service.service_name = "Procedure" OR service.service_name = "procedure" OR service.service_name = "procedures" OR service.service_name = "Procedures" ) AND service.department_id = departments.department_id AND departments.department_name = "General practice" AND service.branch_code = "OSH" AND service_charge.visit_type_id = visit_type.visit_type_id  AND service_charge.visit_type_id = '.$visit_t;

$procedure_search = $this->session->userdata('procedure_search');

if(!empty($procedure_search))
{
    $where .= $procedure_search;
}

$table = 'service_charge,visit_type,service, departments';
//pagination
$this->load->library('pagination');
$config['base_url'] = site_url().'nurse/procedures/'.$visit_id;
$config['total_rows'] = $this->reception_model->count_items($table, $where);
$config['uri_segment'] = 4;
$config['per_page'] = 15;
$config['num_links'] = 5;

$config['full_tag_open'] = '<ul class="pagination pull-right">';
$config['full_tag_close'] = '</ul>';

$config['first_tag_open'] = '<li>';
$config['first_tag_close'] = '</li>';

$config['last_tag_open'] = '<li>';
$config['last_tag_close'] = '</li>';

$config['next_tag_open'] = '<li>';
$config['next_link'] = 'Next';
$config['next_tag_close'] = '</span>';

$config['prev_tag_open'] = '<li>';
$config['prev_link'] = 'Prev';
$config['prev_tag_close'] = '</li>';

$config['cur_tag_open'] = '<li class="active"><a href="#">';
$config['cur_tag_close'] = '</a></li>';

$config['num_tag_open'] = '<li>';
$config['num_tag_close'] = '</li>';
$this->pagination->initialize($config);

$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
$v_data["links"] = $this->pagination->create_links();
$query = $this->nurse_model->get_procedures($table, $where, $config["per_page"], $page, $order);


?>
<div class="row">
    <div class="col-md-12"> 
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Procedures</h2>
            </header>
            <div class="panel-body">
                <center>
                  <h2>
                    InstaFlix
                  </h2>
                </center>
                <div class="col-lg-12">
                  <input type="search" class="form-control" id="input-search" placeholder="Search For Movie..." >
                </div>
                <br>
                <br>
                <br>
                <div class="searchable-container">

                <table border="0" class="table table-hover table-condensed">
                    <thead> 
                        <th> </th>
                        <th>Procedure</th>
                        <th>Patient Type</th>
                        <th>Cost</th>
                    </thead>
        
                    <?php 
                    //echo "current - ".$current_item."end - ".$end_item;
                    
                    $rs9 = $query->result();
                    var_dump($rs9);
                    foreach ($rs9 as $rs10) :
                    
                    
                    $procedure_id = $rs10->service_charge_id;
                    $proced = $rs10->service_charge_name;
                    $visit_type = $rs10->visit_type_id;
                    $visit_type_name = $rs10->visit_type_name;
                    
                    $stud = $rs10->service_charge_amount;
                    
                    ?>
                    <tr class="items clearfix">
                        <td></td>
                        
                        <td> <?php $suck=1; ?>                
                        <a href="#" onClick="procedures(<?php echo $procedure_id?>,<?php echo $visit_id?>,<?php echo $suck; ?>)"><?php echo $proced?> </a></td>
                        <td><?php echo $visit_type_name;?></td>
                        <td><?php echo $stud?></td>
                    </tr>
                    <?php endforeach;?>
                </table>
          
            </div>
            <div class="widget-foot">
                                
                <?php if(isset($links)){echo $links;}?>
            
                <div class="clearfix"></div> 
            
            </div>
                     
                <div class='navbar-inner'><p style='text-align:center; color:#0e0efe;'><input type='button' class='btn btn-primary' value='Add Procedure' onclick='myPopup3(<?php echo $visit_id; ?>)'/></p></div>
                <!-- visit Procedures from java script -->
                <div id="procedures"></div>
                <!-- end of visit procedures -->
            </div>
         </section>
    </div>
</div>

<div class="row">
	<div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Vaccines</h2>
            </header>
            <div class="panel-body">
                <div class='navbar-inner'><p style='text-align:center; color:#0e0efe;'><input type='button' class='btn btn-primary' value='Add Vaccine' onclick='myPopup4(<?php echo $visit_id; ?>)'/></p></div>
                <!-- visit Procedures from java script -->
                <div id="vaccines_to_patients"></div>
                <!-- end of visit procedures -->
            </div>
         </section>
    </div>
</div>
<script type="text/javascript">
    
     $(function() {    
        $('#input-search').on('keyup', function() {
          var rex = new RegExp($(this).val(), 'i');
            $('.searchable-container .items').hide();
            $('.searchable-container .items').filter(function() {
                return rex.test($(this).text());
            }).show();
        });
    });
</script>
