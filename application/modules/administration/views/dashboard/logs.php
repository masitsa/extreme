<?php
	$session_result = $this->reports_model->get_all_sessions();
	$sessions = '';
	
	if($session_result->num_rows() > 0)
	{
		$result = $session_result->result();
		
		foreach($result as $res)
		{
			$session_name_name = $res->session_name_name; 
			$session_time = date('jS M Y H:i a',strtotime($res->session_time)); 
			$personnel_fname = $res->personnel_fname; 
			$personnel_onames = $res->personnel_onames;
			$personnel = $personnel_fname.' '.$personnel_onames;
			
			if($session_name_name == 'Login')
			{
			
				$sessions .= '
				<li>
					  <i class="icon-bell i-green"></i><span class="log-text">'.$personnel.' '.$session_name_name.' </span><span class="time">'.$session_time.'</span>
				  </li>
			';
			}
			
			else
			{
				$sessions .= '
				<li>
					  <i class="icon-warning-sign i-red"></i></i><span class="log-text">'.$personnel.' '.$session_name_name.' </span><span class="time">'.$session_time.'</span>
				  </li>
			';

			}
		}
	}
	
?>
<div class="widget">
                      <!-- Widget title -->
                      <div class="widget-head">
                          <h4 class="pull-left"><i class="icon-calendar"></i>Information</h4>
                          <div class="widget-icons pull-right">
                              <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a>
                              <a href="#" class="wclose"><i class="icon-remove"></i></a>
                          </div>
                          <div class="clearfix"></div>
                      </div>
                      <div class="widget-content">
                          <!-- Widget content -->
                          <div class="tabbable t-info">
                              <ul class="nav nav-tabs">
                                  <li class="active"><a href="#system" data-toggle="tab">Today's Sessions</a></li>
                                  <!-- <li><a href="#activities" data-toggle="tab">Activities</a></li> -->
                              </ul>
                              <div class="tab-content">
                                  <div class="tab-pane active" id="system">
                                      <div class="logs-list">
                                        <ul class="logs">
                                          <?php echo $sessions;?>
                                      </ul>
                                      </div>
                                  </div>
                                  <!--<div class="tab-pane" id="activities">
                                      <div class="logs-list">
                                          <ul class="logs">
                                              <li>
                                                  <i class="icon-tablet i-blue"></i><span class="log-text">New user registered.</span></span><span class="time">43 mins ago</span>
                                              </li>
                                              <li>
                                                  <i class="icon-laptop i-orange"></i><span class="log-text">Disk space to 85% full.</span><span class="time">49 mins ago</span>
                                              </li>
                                              <li>
                                                  <i class="icon-qrcode"></i><span class="log-text">Time successfully synced.</span><span class="time">55 mins ago</span>
                                              </li>
                                              <li>
                                                  <i class="icon-bell i-green"></i><span class="log-text">You've recieved new message </span><span class="time">just now</span>
                                              </li>
                                              <li>
                                                  <i class="icon-plus i-red"></i><span class="log-text">New user registered.</span><span class="time">20 mins ago</span>
                                              </li>
                                              <li>
                                                  <i class="icon-bullhorn i-blue"></i><span class="log-text">New items are in queue.</span><span class="time">25 mins ago</span>
                                              </li>
                                              <li>
                                                  <i class="icon-warning-sign i-red"></i><span class="log-text">High CPU load on cluster #2. </span><span class="time">35 mins ago</span>
                                              </li>
                                              <li>
                                                  <i class="icon-weibo i-orange"></i><span class="log-text">Download finished.</span><span class="time">1 hour ago</span>
                                              </li>
                                              <li>
                                                  <i class="icon-save i-green"></i><span class="log-text">New order received.</span><span class="time">1 hour ago</span>
                                              </li>
                                              <li>
                                                  <i class="icon-zoom-in i-blue"></i><span class="log-text">Download finished.</span><span class="time">1 hour ago</span>
                                              </li>
                                              <li>
                                                  <i class="icon-calendar i-blue"></i><span class="log-text">Download finished.</span><span class="time">1 hour ago</span>
                                              </li>
                                              <li>
                                                  <i class="icon-bell i-green"></i><span class="log-text">You've recieved new message </span><span class="time">just now</span>
                                              </li>
                                              <li>
                                                  <i class="icon-plus i-red"></i><span class="log-text">New user registered.</span><span class="time">20 mins ago</span>
                                              </li>
                                              <li>
                                                  <i class="icon-bullhorn i-blue"></i><span class="log-text">New items are in queue.</span><span class="time">25 mins ago</span>
                                              </li>
                                              <li>
                                                  <i class="icon-warning-sign i-red"></i><span class="log-text">High CPU load on cluster #2.</span><span class="time">35 mins ago</span>
                                              </li>
                                              <li>
                                                  <i class="icon-tablet i-blue"></i><span class="log-text">New user registered.</span><span class="time">43 mins ago</span>
                                              </li>
                                          </ul>
                                      </div>
                                  </div> -->
                              </div>
                          </div>
                      </div>
                  </div>