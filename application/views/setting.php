<?php $shop=$_GET[ "shop"]; if($shop !='' ) { $data=$this->db->select('id, plan_id')->where('domain', $shop)->get('shopify_stores')->row();
 $plan_id = $data->plan_id;
// echo "<pre>";
// var_dump($plan_id);
// exit;
 ?>
<section>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<h2>Setting</h2>
			</div>
      <div class="col-md-6 text-right">
        <form method="post" id="settingform" action="" enctype="multipart/form-data">
          <button type="submit" class="btn btn-success">Submit</button>
      </div>
      <div class="col-md-10 mx-auto">
        <a class="collapsible text-white">Customize Your wishlist app</a>
        <div class="content bg-white">

          <div class="form-group row pt-3 ">
            <label  class="col-form-label col-md-4" for="callwish">What do you wish to call your Wishlist?</label>
						<div class="col-md-8">
            	<input type="text" class="form-control" id="callwish" maxlength="30" name="callwish" value="<?php echo $getsetting->callwish ?>">
						</div>
					</div>

          <div class="form-group row">
              <label  class="col-form-label col-md-4" for="btntxtcolr">Button text color</label>
							<div class="col-md-8">
              	<input class="jscolor" value="<?php echo $getsetting->btntxtcolr ?>" name="btntxtcolr">
            	</div>
					</div>

					<div class="form-group row">
              <label  class="col-form-label col-md-4" for="btnbckcolr">Button background color</label>
							<div class="col-md-8">
	              <input class="jscolor" value="<?php echo $getsetting->btnbckcolr ?>" name="btnbckcolr">
	            </div>
          </div>

          <div class="form-group row">
            <label  class="col-form-label col-md-4" for="btnTxtBeforeAdding">Button Text Before adding to Wishlist</label>
						<div class="col-md-8">
            	<input type="text" class="form-control" id="btnTxtBeforeAdding" maxlength="100" name="btnTxtBeforeAdding" value="<?php echo $getsetting->btnTxtBeforeAdding ?>">
						</div>
					</div>

          <div class="form-group row">
            <label  class="col-form-label col-md-4" for="btnTxtAfterAdding">Button Text After adding to Wishlist</label>
						<div class="col-md-8">
            	<input type="text" class="form-control" id="btnTxtAfterAdding" maxlength="100" name="btnTxtAfterAdding" value="<?php echo $getsetting->btnTxtAfterAdding ?>">
						</div>
					</div>

          <div class="form-group row ">
            <label  class="col-form-label col-md-4">Icon for wishlist button</label>
						<div class="col-md-8">
	            <div class="form-check form-check-inline">
	              <input class="form-check-input" type="radio" name="btnIcon" id="btnIcon1" value="fa fa-star" <?php if ($getsetting->btnIcon == 'fa fa-star') { echo "checked"; } ?>>
	              <label class="form-check-label" for="btnIcon1"> <i class="far fa-star"></i>
	              </label>
	            </div>
	            <div class="form-check form-check-inline">
	              <input class="form-check-input" type="radio" name="btnIcon" id="btnIcon2" value="fa fa-heart" <?php if ($getsetting->btnIcon == 'fa fa-heart') { echo "checked"; } ?>>
	              <label class="form-check-label" for="btnIcon2"> <i class="far fa-heart"></i>
	              </label>
	            </div>
	            <div class="form-check form-check-inline">
	              <input class="form-check-input" type="radio" name="btnIcon" id="btnIcon3" value="fa fa-bookmark" <?php if ($getsetting->btnIcon == 'fa fa-bookmark') { echo "checked"; } ?>>
	              <label class="form-check-label" for="btnIcon3"> <i class="far fa-bookmark"></i>
	              </label>
	            </div>
						</div>
          </div>
          <hr>
          <div <?php echo $plan_id==0 ? 'style="pointer-events:none;opacity:0.4"': ''?>>
            <h5 class="text-uppercase">Control the wishitems</h5>

            <div class="form-group row ">
                <label class="form-check-label col-form-label col-md-4">Remove products from wishlist after adding to cart</label>
                <div class="col-md-8">
                  <input type="checkbox" class="form-check-input" id="removeItemFromWishlistToggle" data-onstyle="primary" data-offstyle="light" data-size="mini" <?php echo $getsetting->removeItemFromWishlist == 1 ? 'checked':'' ?>>
                  <input type="hidden" name="removeItemFromWishlist" id="removeItemFromWishlist" value="<?php echo $getsetting->removeItemFromWishlist ?>">
                </div>
            </div>
          </div>
            <hr>
          <h5>WISHLIST LIST/POPUP VIEW</h5>
          <div class="form-group row">
            <label  class="col-form-label col-md-4" for="btnTxtForCart">Button Text for Add to Cart</label>
						<div class="col-md-8">
	            <input type="text" class="form-control" id="btnTxtForCart" maxlength="100" name="btnTxtForCart" value="<?php echo $getsetting->btnTxtForCart ?>">
						</div>
					</div>
          <div class="form-group row">
            <label  class="col-form-label col-md-4" for="textWhenNoItem">Text To -show when there are not item in wishlist</label>
						<div class="col-md-8">
							<input type="text" class="form-control" id="textWhenNoItem" maxlength="100" name="textWhenNoItem" value="<?php echo $getsetting->textWhenNoItem ?>">
						</div>
				  </div>
          <hr>
          <h5>REMOVE ITEM FROM WISHLIST POPUP</h5>
          <div class="form-group row">
            <label  class="col-form-label col-md-4" for="mgsForRemoveItem">Message Text for removing product from wishlist confirmation</label>
						<div class="col-md-8">
							<input type="text" class="form-control" id="mgsForRemoveItem" maxlength="100" name="mgsForRemoveItem" value="<?php echo $getsetting->mgsForRemoveItem ?>">
	          </div>
					</div>
        </div>

        <!-- //second collapse -->
        <a class="collapsible text-white">Email reminders for on sale and Low stock wishlisted products
        <?php echo $plan_id==0 ? '<i class="fa fa-star" style="color:yellow"></i> Go To Premium': ''?></a>
        <div class="content bg-white"  <?php echo $plan_id==0 ? 'style="pointer-events:none;opacity:0.4"': ''?>>
					<div><h5 class="mt-3">Set up email configuration</h5></div>
					<div class="form-group row pt-3 ">
            <label class="col-form-label col-md-4">Enable emails</label>
						<div class="col-md-8">
							<select class="form-control" id="enable_email" name="enable_email" <?php echo  $getsetting->host_name == NULL || $getsetting->host_name == ''
							 																																						&& $getsetting->port_number == NULL || $getsetting->port_number == ''
                                                                                                                                       && $getsetting->email_service == NULL || $getsetting->email_service == ''
																																													&& $getsetting->sender_name  == NULL || $getsetting->sender_name  == ''
																																													&& $getsetting->sender_email == NULL || $getsetting->sender_email == ''
																																													&& $getsetting->password	 == NULL || $getsetting->password	 == '' ? 'disabled' : '' ;?> >
								<option value="1" <?php echo $getsetting->enable_email == '1' ?"selected" : ''; ?>>Enable</option>
								<option value="0" <?php echo $getsetting->enable_email == '0' ?"selected" : ''; ?>>Disable</option>
							</select>
						</div>
					</div>
               <div class="form-group  row">
                  <label class="col-form-label col-md-4">Email Service</label>
						<div class="col-md-8">
                     <select name="email_service" class="form-control">
                        <option disabled selected>Please select</option>
                        <option value="ssl" <?php echo $getsetting->email_service == 'ssl' ?"selected" : ''; ?>>SSL</option>
                        <option value="tls" <?php echo $getsetting->email_service == 'tls' ?"selected" : ''; ?>>TLS</option>
                     </select>
						</div>
					</div>
					<div class="form-group  row">
            <label class="col-form-label col-md-4">Host name</label>
						<div class="col-md-8">
	            <input type="text"  class="form-control email_config" placeholder="Host name" name="host_name" id="host_name" value="<?php echo $getsetting->host_name; ?>">
						</div>
					</div>
					<div class="form-group  row">
            <label class="col-form-label col-md-4">Port number</label>
						<div class="col-md-8">
	            <input type="number"  class="form-control email_config" min="0" placeholder="Port number" name="port_number" id="port_number" value="<?php echo $getsetting->port_number; ?>">
						</div>
					</div>
          <div class="form-group  row ">
            <label class="col-form-label col-md-4">Sender name</label>
						<div class="col-md-8">
	            <input type="text"  class="form-control email_config" placeholder="Sender name" minlenght="5" name="sender_name" id="sender_name" value="<?php echo $getsetting->sender_name; ?>">
						</div>
					</div>
          <div class="form-group row">
            <label class="col-form-label col-md-4">Sender email</label>
						<div class="col-md-8">
	            <input type="email"  class="form-control email_config" placeholder="Sender email" minlenght="5" name="sender_email" id="sender_email" value="<?php echo $getsetting->sender_email; ?>">
						</div>
					</div>
          <div class="form-group row">
            <label class="col-form-label col-md-4">Sender password</label>
						<div class="col-md-8">
	            <input type="password"   class="form-control email_config" placeholder="Sender password" minlenght="5" name="password" id="password">
						</div>
					</div>
					<div class="form-group row">
            <label class="col-form-label col-md-4">Email subscription</label>
						<div class="col-md-8">
							<select class="form-control" id="email_subscr" name="email_subscr">
								<option value="1 DAY" <?php echo $getsetting->email_subscr == '1 DAY' ?"selected" : ''; ?>>Daily</option>
								<option value="7 DAY" <?php echo $getsetting->email_subscr == '1 DAY' ?"selected" : ''; ?>>Weekly</option>
								<option value="30 DAY" <?php echo $getsetting->email_subscr == '1 DAY' ?"selected" : ''; ?>>Monthly</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-md-4">Logo URL</label>
						<div class="col-md-8">
							<input type="url" class="form-control" placeholder="Logo URL" id="logoUrl" name="logoUrl" value="<?php echo $getsetting->logoUrl; ?>">
							<small id="logoUrlHelpBlock" class="form-text text-muted">
							  Max width 400px
							</small>
						</div>
					</div>
          <hr></hr>
					<div><h5 class="mt-3">Email configuration for wishlisted remainders</h5></div>
					<div class="form-group row">
            <label class="col-form-label col-md-4">Since added to wishlist</label>
						<div class="col-md-8">
							<select class="form-control" id="since_date"  name="since_date">
								<option value="1 DAY" <?php echo $getsetting->since_date == '1 DAY' ?"selected" : ''; ?>>Daily</option>
								<option value="7 DAY" <?php echo $getsetting->since_date == '7 DAY' ?"selected" : ''; ?>>Weekly</option>
								<option value="30 DAY" <?php echo $getsetting->since_date == '30 DAY' ?"selected" : ''; ?>>Monthly</option>
							</select>
						</div>
					</div>
					<div class="form-group row ">
						<label class="col-form-label col-md-4">Email template</label>
						<div class="col-md-8">
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="email_template_remainder" id="email_template_remainder1" value="1" <?php echo $getsetting->email_template_remainder == '1'? "checked" : ""; ?>>
								<label class="form-check-label" for="email_template_remainder1">Template 1</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="email_template_remainder" id="email_template_remainder2" value="2" <?php echo $getsetting->email_template_remainder == '2'? "checked": ''; ?>>
								<label class="form-check-label" for="email_template_remainder2">Template 2</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="email_template_remainder" id="email_template_remainder3" value="3" <?php echo $getsetting->email_template_remainder == '3'? "checked" : ''; ?>>
								<label class="form-check-label" for="email_subscr3">Template 3</label>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-md-4">Subject</label>
						<div class="col-md-8">
							<input type="text" class="form-control" placeholder="Email subject for remainders for wishlisted items" minlenght="5" name="sub_remainder" id="sub_remainder" value="<?php echo $getsetting->sub_remainder; ?>">
						</div>
					</div>

					<div class="form-group row">
            <label class="col-form-label col-md-4">Greeting Text</label>
						<div class="col-md-8">
            	<input type="text" class="form-control" placeholder="Gretting for remainders for wishlisted items"  name="greetingText_remainder" id="greetingText_remainder" value="<?php echo $getsetting->greetingText_remainder; ?>">
              <small id="greetingText_remainderHelpBlock" class="form-text text-muted">
							  Use [Name] for user's first name
							</small>
            </div>
					</div>

					<div class="form-group">
                  <div class="row">
                     <div class="col-6">
                        <label class="col-form-label">Email Text</label>
                     </div>
                     <div class="col-6 text-right">
                        <button type="button" class="btn btn-link text-danger reset_remainder_body" id="reset_remainder_body">RESET EMAIL TEXT</button>
                     </div>
                  </div>
						<!-- <div class="col-md-8"> -->
	          	<textarea class="form-control"  name="emailText_remainder" id="emailText_remainder" ><?php    echo $getsetting->emailText_remainder; ?>

                  </textarea>
							<script type="text/javascript">
				         CKEDITOR.replace( 'emailText_remainder' );
				       </script>
              <small id="emailText_remainderHelpBlock2" class="form-text text-muted">
                Use [since_date] to display oldest date product added to wishlist<br>
							  Use [products] to display products list that added to wishlist.
							</small>
						<!-- </div> -->
					</div>
          <hr></hr>
					<div><h5 class="mt-3">Email configuration for wishlisted item on sale or discount</h5></div>
					<div class="form-group row ">
						<label class="col-form-label col-md-4">Email template</label>
						<div class="col-md-8">
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="email_template_sale" id="email_template_sale1" value="1" <?php echo $getsetting->email_template_sale == '1'? "checked" : ""; ?>>
								<label class="form-check-label" for="email_template_sale1">Template 1</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="email_template_sale" id="email_template_sale2" value="2" <?php echo $getsetting->email_template_sale == '2'? "checked": ''; ?>>
								<label class="form-check-label" for="email_template_sale2">Template 2</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="email_template_sale" id="email_template_sale3" value="3" <?php echo $getsetting->email_template_sale == '3'? "checked" : ''; ?>>
								<label class="form-check-label" for="email_template_sale">Template 3</label>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-md-4">Subject</label>
						<div class="col-md-8">
							<input type="text" class="form-control" placeholder="Email subject for remainders for wishlisted items" minlenght="5" name="sub_sale" id="sub_sale" value="<?php echo $getsetting->sub_sale; ?>">
						</div>
					</div>

					<div class="form-group row">
            <label class="col-form-label col-md-4">Greeting Text</label>
						<div class="col-md-8">
            	<input type="text" class="form-control" placeholder="Gretting for remainders for wishlisted items"  name="greetingText_sale" id="greetingText_sale" value="<?php echo $getsetting->greetingText_sale; ?>">
              <small class="form-text text-muted">
                Use [Name] for user's first name.
              </small>
            </div>
					</div>

					<div class="form-group">
                  <div class="row">
                     <div class="col-6">
                        <label class="col-form-label">Email Text</label>
                     </div>
                     <div class="col-6 text-right">
                        <button type="button" class="btn btn-link text-danger reset_sale_body" id="reset_sale_body">RESET EMAIL TEXT</button>
                     </div>
                  </div>
						<!-- <div class="col-md-8"> -->
	          	<textarea class="form-control" placeholder="Email text for remainders for wishlisted items"  name="emailText_sale" id="emailText_sale" ><?php echo $getsetting->emailText_sale; ?>
               </textarea>
						<!-- </div> -->
						<script type="text/javascript">
							 CKEDITOR.replace( 'emailText_sale' );
						 </script>
             <small class="form-text text-muted">
               Use [product] to display product which is on sale.
             </small>
					</div>
          <hr></hr>
				</div>
			</form>

			<!-- //third collapse -->
			<a class="collapsible text-white">Test email configuration   <?php echo $plan_id==0 ? '<i class="fa fa-star" style="color:yellow"></i> Go To Premium': ''?></a>
			<div class="content bg-white" <?php echo $plan_id==0 ? 'style="pointer-events:none;opacity:0.4"': ''?>>
				<form class="" id="testEmailForm" >
					<div class="form-group row pt-3 " id="testEmailDiv">
						<label class="col-form-label col-md-12">Test your email for</label>
						<div class="col-md-4">
							<select name="emailTestFor"  class="form-control" <?php echo $plan_id==1 ? 'required': ''?>>
								<option value="1">Email Remainders</option>
								<option value="2">Email for notify the wishlisted item is on sale</option>
							</select>
						</div>
						<div class="col-md-4">
							<input type="email"  name="emailTo" id="emailTo" placeholder="enter the email" class="form-control" <?php echo $plan_id==1 ? 'required': ''?>>
						</div>
						<div class="col-md-4">
							<button type="submit" class="btn btn-success">Test</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		 <!-- //col-md-10 -->
</div>

  </div>
	<!--container-->
</section>
<?php } ?>
