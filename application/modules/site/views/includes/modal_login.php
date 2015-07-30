<form role="form" action="<?php echo site_url().'checkout/login';?>" method="POST">
<!-- Modal Login start -->
<div class="modal signUpContent fade" id="ModalLogin" tabindex="-1" role="dialog" >
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
        <h3 class="modal-title-site text-center" > Login  to Fad Shoppe </h3>
      </div>
      <div class="modal-body">
        <div class="form-group login-username">
          <div >
            <input type="email" class="form-control" size="20" name="email" placeholder="Enter email" value="<?php echo set_value('email');?>" required>
          </div>
        </div>
        <div class="form-group login-password">
          <div >
            <input type="password" class="form-control" name="password" size="20" placeholder="Password" required>
          </div>
        </div>
        <div class="form-group">
          <div >
            <div class="checkbox login-remember">
              <label>
                <input name="rememberme"  value="forever" checked="checked" type="checkbox">
                Remember Me </label>
            </div>
          </div>
        </div>
        <div >
          <div >
            <input name="submit" class="btn  btn-block btn-lg btn-primary" value="LOGIN" type="submit">
          </div>
        </div>
        <!--userForm--> 
        
      </div>
      <div class="modal-footer">
        <p class="text-center"> Not here before? <a data-toggle="modal"  data-dismiss="modal" href="#ModalSignup"> Sign Up. </a> <br>
          <a href="<?php echo site_url().'forgot-password';?>" > Lost your password? </a> </p>
      </div>
    </div>
    <!-- /.modal-content --> 
    
  </div>
  <!-- /.modal-dialog --> 
  
</div>
<!-- /.Modal Login --> 
</form>

<form role="form" action="<?php echo site_url().'checkout/register';?>" method="POST">
<!-- Modal Signup start -->
<div class="modal signUpContent fade" id="ModalSignup" tabindex="-1" role="dialog" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
        <h3 class="modal-title-site text-center" > REGISTER </h3>
      </div>
      <div class="modal-body">
        <!--<div class="control-group"> <a class="fb_button btn  btn-block btn-lg " href="#"> SIGNUP WITH FACEBOOK </a> </div>
        <h5 style="padding:10px 0 10px 0;" class="text-center"> OR </h5>-->
        <div class="form-group reg-email">
          <div >
            <input type="text" class="form-control" name="first_name" placeholder="Enter first name" value="<?php echo set_value('first_name');?>" required>
          </div>
        </div>
        <div class="form-group reg-email">
          <div >
            <input type="text" class="form-control" name="other_names" placeholder="Enter last name" value="<?php echo set_value('other_names');?>" required>
          </div>
        </div>
        <div class="form-group reg-email">
          <div >
            <input type="email" class="form-control" size="20" name="email" placeholder="Enter email" value="<?php echo set_value('email');?>" required>
          </div>
        </div>
        <div class="form-group reg-password">
          <div >
            <input type="password" class="form-control" name="password" size="20" placeholder="Password" required>
          </div>
        </div>
        <div class="form-group reg-email">
          <div >
            <input  required type="tel"  name="phone" class="form-control" placeholder="Enter phone" value="<?php echo set_value('phone');?>">
          </div>
        </div>
        <div class="form-group">
          <div >
            <div class="checkbox login-remember">
              <label>
                <input name="rememberme" id="rememberme" value="forever" checked="checked" type="checkbox">
                Remember Me </label>
            </div>
          </div>
        </div>
        <div >
          <div >
            <input name="submit" class="btn  btn-block btn-lg btn-primary" value="REGISTER" type="submit">
          </div>
        </div>
        <!--userForm--> 
        
      </div>
      <div class="modal-footer">
        <p class="text-center"> Already member? <a data-toggle="modal"  data-dismiss="modal" href="#ModalLogin"> Sign in </a> </p>
      </div>
    </div>
    <!-- /.modal-content --> 
    
  </div>
  <!-- /.modal-dialog --> 
  
</div>
</form>