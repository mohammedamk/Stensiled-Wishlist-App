<?php
$shop = $_GET['shop'];
if ($shop !='') {
  $shop_id = $this->Home_model->GetShopId($shop);
  if($shop_id != ''){
      $setting = $this->Home_model->getallSetting($shop_id);
      $emailText_remainder = $setting->emailText_remainder;
      $emailText_sale      = $setting->emailText_sale;
   }
}
?>
<div class="contact" >
  <a href="<?=base_url().'Home/feedback?shop='.$_GET['shop'] ;?>"><i class="fas fa-comment" style="color:white;font-size:25px"></i></a>
</div>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
   <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
   <script src="https://kit.fontawesome.com/784056f904.js" crossorigin="anonymous"></script>
   <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
   <script src="<?php echo base_url()?>assets/js/custome.js"></script>
   <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
   <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
   <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script>
        $(document).ready(function() {
          $(function() {
            $('#wishlistbtn').bootstrapToggle({
              on: 'Enabled',
              off: 'Disabled'
            });
          });

          $(function() {
            $('#removeItemFromWishlistToggle').bootstrapToggle({
              on: 'Yes',
              off: 'No'
            });
          });
          $(function() {
              $('#removeItemFromWishlistToggle').on('change',function() {
                var enable;
                enable = $(this).prop('checked') == true ? 1 : 0;
                $('#removeItemFromWishlist').val(enable);
              });
            });
          // getWishlistSetting();
          function getWishlistSetting()
          {
            var wishlistInput = $('#enable_wishlist');
            var wishbtn = $('#wishlistbtn');
            $.ajax({
              url: '<?php echo base_url().'Home/getWishlistSetting?shop='.$_GET['shop']; ?>',
              method: 'GET',
              beforeSend: function() { $.LoadingOverlay("show"); },
              complete: function() { $.LoadingOverlay("hide"); },
              success: function(data) {
                // console.log(data);
                var value = data;
                if(typeof value != "undefined" )
                {
                  wishlistInput.val(value);
                  if(value == 1)
                  {
                    wishbtn.bootstrapToggle('on');
                  }
                }
              },
              error: function(data) {
                  alert('Failed to load setting for enabling wishlist. Please try again.')
              },
            });
          }
          $(function() {
              $('#wishlistbtn').on('change',function() {
                var enable;
                enable = $(this).prop('checked') == true ? 1 : 0;
                $.ajax({
                  url: '<?php echo base_url().'Home/enableWishlist?shop='.$_GET['shop']; ?>',
                  method: 'POST',
                  data: {enable: enable},
                  beforeSend: function() { $.LoadingOverlay("show"); },
                  complete: function() { $.LoadingOverlay("hide"); },
                  success: function(data) {
                    $('#enable_wishlist').val(enable);
                  },
                  error: function(data) {
                    alert('Failed to load setting for enabling wishlist. Please try again.');
                  },
                });
              });
            });

            $("#dashboardpicker").flatpickr({
                  mode: "range",
                  maxDate: "today",
                  altInput: false,
                  dateFormat: "Y-m-d",
                  onClose: function(selectedDates, dateStr, instance){
                        dashboardpicker();
                      },
                 onReady: function(selectedDates, dateStr, instance){
                            dashboardpicker();
                          }
              });

          //dashbord date sattitics
          // serching data in date range
          function dashboardpicker(){
             var dateRange = $('#dashboardpicker').val();
             dateRangeArray = dateRange.split(" ");
             var start_date = dateRangeArray[0];
             var end_date = dateRangeArray[2];
             // console.log(start_date);
             // console.log(end_date);
             if(start_date != '' && end_date !='')
             {
               $.ajax({
                 url: '<?php echo base_url().'Home/getListInRange?shop='.$_GET['shop']; ?>',
                 method: 'post',
                 dataType: 'json',
                 data: { start_date:start_date, end_date:end_date },
                 beforeSend: function() { $.LoadingOverlay("show"); },
                 complete: function() { $.LoadingOverlay("hide"); },
                 success: function(data) {
                   // console.log(data);
                   var avg=data.customers/data.products;
                   if(isNaN(avg) == true){ var avg1 = 0; } else { var avg1 = avg.toFixed(2) }
                   $('.dashbordWishlist').text(data.wishitems);
                   $('.dashbordAverage').text(avg1);
                   $('.dashbordCustomers').text(data.customers);
                   $('.dashbordProducts').text(data.products);
                 },
                 error: function(error) {
                   $.LoadingOverlay("hide");
                   // console.log(error);
                   toastr.error('Error' , 'Sever error');
                 },
               });
             }
             else
             {
               toastr.warning('Warning', 'Date is Required');
             }
           }
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                               // activity
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            $("#activitypicker").flatpickr({
                  mode: "range",
                  maxDate: "today",
                  altInput: false,
                  dateFormat: "Y-m-d",
              });

              var urlactivity = "<?php echo base_url().'Home/fetchactivitydata?shop='.$_GET['shop']; ?>";

              //chech the table has filter or not
              fetch_Activitydata('no');

               function fetch_Activitydata(is_date_search, start_date='', end_date='', added_to_list='', removed_from_list='', new_list='', added_to_cart='')
               {
                 var dataTableActivity = $('#activity-table').DataTable({
                   "bSort"      : false,
                   "searching"  : true,
                   "processing" : true,
                   "paging"     : true,
                   "pageLength" : 10,
                   "serverSide" : true,
                   "order"      : [[0, "desc" ]],
                   "ajax": {
                           url :  urlactivity,
                           type : 'POST',
                           data:{
                             is_date_search:is_date_search,
                             start_date:start_date,
                             end_date:end_date,
                             added_to_list:added_to_list,
                             removed_from_list:removed_from_list,
                             new_list:new_list,
                             added_to_cart:added_to_cart
                           },
                           beforeSend: function() { $.LoadingOverlay("show"); },
                           complete: function() { $.LoadingOverlay("hide"); },
                         }
                 });
               }

               // serching data in date range
                $('#submitActivityfilter').click(function(){
                  var dateRange = $('#activitypicker').val();
                  var added_to_list = $('#added_to_list:checked').val();
                  var removed_from_list = $('#removed_from_list:checked').val();
                  var new_list = $('#new_list:checked').val();
                  var added_to_cart = $('#added_to_cart:checked').val();
                  dateRangeArray = dateRange.split(" ");
                  var start_date = dateRangeArray[0];
                  var end_date = dateRangeArray[2];
                  // alert(start_date);
                  // alert(end_date);
                  // alert(added_to_list);
                  // alert(removed_from_list);
                  // alert(added_to_cart);
                  // console.log(end_date);
                  if((start_date != '' && end_date !='') ||  added_to_list !='' || removed_from_list !='' || new_list !='' || added_to_cart !='')
                  {
                   $('#activity-table').DataTable().destroy();
                   fetch_Activitydata('yes', start_date, end_date, added_to_list, removed_from_list, new_list, added_to_cart);
                   $('#myModal2').modal('hide');
                  }
                });

               $('#resetActivity').click(function(){
                 $('#activity-table').DataTable().destroy();
                 $('#activitypicker').val('');
                 $('.checkbox').prop("checked", false);
                 $('#myModal2').modal('hide');
                 fetch_Activitydata('no');
               });
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                                // customer
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        $("#customerpicker").flatpickr({
              mode: "range",
              maxDate: "today",
              altInput: false,
              dateFormat: "Y-m-d",
          });
      var urlcustomer = "<?php echo base_url().'Home/fetchCustomersdata?shop='.$_GET['shop']; ?>";

      //chech the table has filter or not
      fetch_Customersdata('no');

       function fetch_Customersdata(is_date_search, start_date='', end_date='')
       {
         var dataTableCustomers = $('#customers-table').DataTable({
           "bSort" : false,
           "searching": true,
           "processing": true,
           "pageLength" : 10,
           "serverSide": true,
           "order": [[0, "desc" ]],
           "ajax": {
                   url :  urlcustomer,
                   type : 'POST',
                   data:{
                     is_date_search:is_date_search, start_date:start_date, end_date:end_date
                   },
                   beforeSend: function() { $.LoadingOverlay("show"); },
                   complete: function() { $.LoadingOverlay("hide"); },

                 }
         });
       }

       // serching data in date range
        $('#submitCustomerDate').click(function(){
          var dateRange = $('#customerpicker').val();
          dateRangeArray = dateRange.split(" ");
          var start_date = dateRangeArray[0];
          var end_date = dateRangeArray[2];
          // console.log(start_date);
          // console.log(end_date);
          if(start_date != '' && end_date !='')
          {
           $('#customers-table').DataTable().destroy();
           fetch_Customersdata('yes', start_date, end_date);
           $('#myModal2').modal('hide');
          }
        });

        $('#resetCustomers').click(function(){
          $('#customers-table').DataTable().destroy();
          $('#customerpicker').val('');
          $('#myModal2').modal('hide');
          fetch_Customersdata('no');
        });
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        // product table starts
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        $("#productspicker").flatpickr({
              mode: "range",
              maxDate: "today",
              altInput: false,
              dateFormat: "Y-m-d",
          });
        var urlproducts = "<?php echo base_url().'Home/fetchProductsdata?shop='.$_GET['shop']; ?>";

        //chech the table has filter or not
        fetch_Productsdata('no');

        function fetch_Productsdata(is_date_searchP, start_dateP='', end_dateP='')
        {
         var dataTableProducts = $('#products-table').DataTable({
           "bSort" : false,
           "searching": true,
           "processing": true,
           "pageLength" : 10,
           "serverSide": true,
           "order": [[0, "desc" ]],
           "ajax": {
                   url :  urlproducts,
                   type : 'POST',
                   data:{
                     is_date_search:is_date_searchP, start_date:start_dateP, end_date:end_dateP
                    },
                    beforeSend: function() { $.LoadingOverlay("show"); },
                    complete: function() { $.LoadingOverlay("hide"); },
                 }
         });
        }

        // serching data in date range
        $('#submitProductDate').click(function(){
          var dateRangeP = $('#productspicker').val();
          dateRangeArrayP = dateRangeP.split(" ");
          var start_dateP = dateRangeArrayP[0];
          var end_dateP = dateRangeArrayP[2];
          // console.log(start_dateP);
          // console.log(end_dateP);
          if(start_dateP != '' && end_dateP !='')
          {
           $('#products-table').DataTable().destroy();
           fetch_Productsdata('yes', start_dateP, end_dateP);
           $('#myModal2').modal('hide');
          }
        });

        $('#resetProducts').click(function(){
          $('#products-table').DataTable().destroy();
          $('#productspicker').val('');
          $('#myModal2').modal('hide');
          fetch_Productsdata('no');
        });

        $('.email_config').on('keyup',function(){
          // console.log($('.email_config'));
          var isValid = true;
          $('.email_config').each(function() {
            if ( $(this).val() === '' )
               { isValid = false; }
          });
          if( isValid ) {
            // console.log(isValid);
             $('#enable_email').prop('disabled', false);
          } else {
            // console.log(isValid);
             $('#enable_email').prop('disabled', true);
          };
        });

        $('#testEmailForm').submit( function(e){
          e.preventDefault();
          var myForm = document.getElementById('testEmailForm');
          var form_data = new FormData(myForm);
          // var url = $('#settingform').attr('action');
          var url = '<?php echo base_url('Home/testEmail').'?shop='.$_GET['shop'] ?>';
          $.ajax({
              url: url,
              type: "POST",
              dataType: 'json',
              processData:false,
              contentType:false,
              cache:false,
              data: form_data,
              beforeSend: function() { $.LoadingOverlay("show"); },
              complete: function() { $.LoadingOverlay("hide"); },
              success: function(data) {
                if(data.code == 100)
                {
                    toastr.error(data.msg);
                    $.LoadingOverlay("hide");
                }
                else if(data.code == 200)
                {
                    toastr.success('Success','Mail send');
                    $.LoadingOverlay("hide");
                }
              },
              error: function() {
                $.LoadingOverlay("hide");
              },
          });
        });

        $('#settingform').submit( function(e){
          e.preventDefault();
          var myForm = document.getElementById('settingform');
          var form_data = new FormData(myForm);
          // var url = $('#settingform').attr('action');
          var url = '<?php echo base_url('Home/savesetting').'?shop='.$_GET['shop'] ?>';
          $.ajax({
              url: url,
              type: "POST",
              dataType: 'json',
              processData:false,
              contentType:false,
              cache:false,
              data: form_data,
              beforeSend: function() { $.LoadingOverlay("show"); },
              complete: function() { $.LoadingOverlay("hide"); },
              success: function(ok) {
                if(ok.code == 100)
                {
                    toastr.error('Failed',ok.msg);
                    $.LoadingOverlay("hide");

                }else{
                    toastr.success('Success', 'Setting Saved!');
                    $.LoadingOverlay("hide");
                }
              },
              error: function() {
                $.LoadingOverlay("hide");
                toastr.error('Failed', 'Please try again');
              },
          })
        });
        // set global variable to save $emailText_sale and $emailText_reminder
        var emailText_sale = "<?= preg_replace("/[\r\n]*/","",$emailText_sale) ?>"; //convert code to single line from multiline
        $("input[name='email_template_sale']").on('change',function(e){
           // alert(e.type);
           var radioValue = $("input[name='email_template_sale']:checked").val();
           var emailText_sale_textarea = $("#emailText_sale");
           if(emailText_sale == "")
           {
             if(radioValue == 1)
            {
              value = "<p>Remember the awesome item below ? you saved it in our wishlist , and guess what? it's on sale!</p><p>[product]</p><p>Pieces priced like this don't stick around long, so hurry in to snag your most-coveted ModCloth merch! If you arrive at the site, and the item is already sold out, we're sorry.But, at least you Know you have great taste.</p><p>Happy shopping!</p>";
              emailText_sale_textarea.text(value);
              CKEDITOR.instances['emailText_sale'].setData(value);
            }
            else if (radioValue == 2) {
              value = "<h2>Your wishlist item(s) is on sale!</h2><p>You saved it in our wishlist</p><p>[products]</p><p>Hurry in to make sure you sang your most-coveted</p><p>Happy shopping!</p>";
              emailText_sale_textarea.text(value);
              CKEDITOR.instances['emailText_sale'].setData(value);
            }
            else if(radioValue == 3)
            {
              value = "<h2><strong>The product of your wishlist items is on sale!</strong></h2><p>[products]</p><p>We only have a limited amount, and this is not a guarantee you will get one, so hurry to be one of the lucky shoppers who do. </p><p>Thank you.</p> ";
              emailText_sale_textarea.text(value);
              CKEDITOR.instances['emailText_sale'].setData(value);
            }
            else{
              alert('invalid template');
            }
          }
        });

        var emailText_remainder = "<?= preg_replace("/[\r\n]*/","",$emailText_remainder) ?>"; //convert code to single line from multiline
        $("input[name='email_template_remainder']").on('change',function(e){
           // alert(e.type);
           var radioValue = $("input[name='email_template_remainder']:checked").val();
           var emailText_remainder_textarea = $("#emailText_remainder");
           if(emailText_remainder == "")
           {
             if(radioValue == 1)
            {
              value = "<p>Remember the awesome item below ? you saved it in our wishlist , and guess what? it's on sale!</p><p>[product]</p><p>Pieces priced like this don't stick around long, so hurry in to snag your most-coveted ModCloth merch! If you arrive at the site, and the item is already sold out, we're sorry.But, at least you Know you have great taste.</p><p>Happy shopping!</p>";
              emailText_remainder_textarea.text(value);
              CKEDITOR.instances['emailText_remainder'].setData(value);
            }
            else if (radioValue == 2) {
              value = "<h2>Your wishlist item(s) is on sale!</h2><p>You saved it in our wishlist</p><p>[products]</p><p>Hurry in to make sure you sang your most-coveted</p><p>Happy shopping!</p>";
              emailText_remainder_textarea.text(value);
              CKEDITOR.instances['emailText_remainder'].setData(value);
            }
            else if(radioValue == 3)
            {
              value = "<h2><strong>The product of your wishlist items is on sale!</strong></h2><p>[products]</p><p>We only have a limited amount, and this is not a guarantee you will get one, so hurry to be one of the lucky shoppers who do. </p><p>Thank you.</p> ";
              emailText_remainder_textarea.text(value);
              CKEDITOR.instances['emailText_remainder'].setData(value);
            }
            else{
              alert('invalid template');
            }
          }
        });

// ------------------------------------------------------------------------------
        $('#reset_remainder_body').click(function(e){
          var radioValue = $("input[name='email_template_remainder']:checked").val();
          var emailText_remainder = $("#emailText_remainder");
          // console.log(radioValue);
          if(confirm('Are sure you want to reset the email body?'))
          {
             if(radioValue == 1)
             {
                value = "<p>You saved these item(s) in our wishlist since [since_date]. Please feel free to shop them.</p><p>[products]</p><p>Hurry in to make sure you sang your most-coveted</p><p>Happy shopping!</p>";
                emailText_remainder.text(value);
                saveForResetEmailTextRemainder(value);
             }
             else if (radioValue == 2) {
                value = "<h2>Your wishlist item(s) are missing you!</h2><p>Remember the awesome item(s) below? you saved it in our wishlist since [since_date].</p><p>[products]</p><p>Hurry in to make sure you sang your most-coveted</p><p>Happy shopping!</p>";
                emailText_remainder.text(value);
                saveForResetEmailTextRemainder(value);
             }
             else if(radioValue == 3)
             {
                value = "<h2><strong>&nbsp;Your loved items are waiting for you!</strong></h2><p>Thank you for visiting our shop. We saved your loved item(s) since [since_date], so please feel free to continue your shopping</p><p>[products]</p><p>Thank you.</p> ";
                emailText_remainder.text(value);
                saveForResetEmailTextRemainder(value)
             }
             else{
                alert('invalid template');
             }

          }
        });


        $('#reset_sale_body').click(function(e){
          var radioValue = $("input[name='email_template_sale']:checked").val();
          var emailText_remainder = $("#emailText_sale");
          // console.log(radioValue);
          if(confirm('Are sure you want to reset the email body?'))
          {
             if(radioValue == 1)
             {
                value = "<p>Remember the awesome item below ? you saved it in our wishlist , and guess what? it's on sale!</p><p>[product]</p><p>Pieces priced like this don't stick around long, so hurry in to snag your most-coveted ModCloth merch! If you arrive at the site, and the item is already sold out, we're sorry.But, at least you Know you have great taste.</p><p>Happy shopping!</p>";
                emailText_remainder.text(value);
                saveForResetEmailTextSale(value);
             }
             else if (radioValue == 2) {
                value = "<h2>Your wishlist item(s) is on sale!</h2><p>You saved it in our wishlist</p><p>[products]</p><p>Hurry in to make sure you sang your most-coveted</p><p>Happy shopping!</p>";
                emailText_remainder.text(value);
                saveForResetEmailTextSale(value);
             }
             else if(radioValue == 3)
             {
                value = "<h2><strong>The product of your wishlist items is on sale!</strong></h2><p>[products]</p><p>We only have a limited amount, and this is not a guarantee you will get one, so hurry to be one of the lucky shoppers who do. </p><p>Thank you.</p> ";
                emailText_remainder.text(value);
                saveForResetEmailTextSale(value)
             }
             else{
                alert('invalid template');
             }

          }
        });
        function saveForResetEmailTextRemainder(value)
        {
          var myForm = document.getElementById('settingform');
          var form_data = new FormData(myForm);
          var url = '<?php echo base_url('Home/savesetting').'?shop='.$_GET['shop'] ?>';
          $.ajax({
              url: url,
              type: "POST",
              dataType: 'json',
              processData:false,
              contentType:false,
              cache:false,
              data: form_data,
              beforeSend: function() { $.LoadingOverlay("show"); },
              complete: function() { $.LoadingOverlay("hide"); },
              success: function(ok) {
                if(ok.code == 100)
                {
                   toastr.error('Failed',ok.msg);
                   $.LoadingOverlay("hide");

                }else{
                   CKEDITOR.instances['emailText_remainder'].setData(value);
                   toastr.success('Success', 'Email body is set default!');
                   $.LoadingOverlay("hide");
                }
              },
              error: function() {
                $.LoadingOverlay("hide");
                toastr.error('Failed', 'Please try again');
              },
          })
        }
        function saveForResetEmailTextSale(value)
        {
          var myForm = document.getElementById('settingform');
          var form_data = new FormData(myForm);
          var url = '<?php echo base_url('Home/savesetting').'?shop='.$_GET['shop'] ?>';
          $.ajax({
              url: url,
              type: "POST",
              dataType: 'json',
              processData:false,
              contentType:false,
              cache:false,
              data: form_data,
              beforeSend: function() { $.LoadingOverlay("show"); },
              complete: function() { $.LoadingOverlay("hide"); },
              success: function(ok) {
                if(ok.code == 100)
                {
                   toastr.error('Failed',ok.msg);
                   $.LoadingOverlay("hide");

                }else{
                   CKEDITOR.instances['emailText_sale'].setData(value);
                   toastr.success('Success', 'Email body is set default!');
                   $.LoadingOverlay("hide");
                }
              },
              error: function() {
                $.LoadingOverlay("hide");
                toastr.error('Failed', 'Please try again');
              },
          })
        }
    $('#feedbackForm').submit( function(e){
      e.preventDefault();
      var myForm = document.getElementById('feedbackForm');
      var form_data = new FormData(myForm);
      // var url = $('#settingform').attr('action');
      var url = '<?php echo base_url('Home/saveFeedback').'?shop='.$_GET['shop'] ?>';
      $.ajax({
          url: url,
          type: "POST",
          dataType: 'json',
          processData:false,
          contentType:false,
          cache:false,
          data: form_data,
          beforeSend: function() { $.LoadingOverlay("show"); },
          complete: function() { $.LoadingOverlay("hide"); },
          success: function(data) {
            if(data.code == 403)
            {
              toastr.error('Error', data.mgs);
                $.LoadingOverlay("hide");
            }else{
              $( '#feedbackForm' ).each(function(){
                  this.reset();
              });
              toastr.success('Success', 'Your message has been sent!');
                $.LoadingOverlay("hide");
              }
          },
          error: function(data) {
            $.LoadingOverlay("hide");
            toastr.error('Failed', 'Please try again');
          },
      })
    });
    $("#collapseBtn1").click(function(){
        $("#collapse1").slideToggle("slow");
      });
    $("#collapseBtn2").click(function(){
      $("#collapse2").slideToggle("slow");
    });
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
<script>
    baguetteBox.run('.grid-gallery', { animation: 'slideIn'});
</script>
<script>
    toastr.options = {
      "closeButton": true,
       "debug": false,
       "positionClass": "toast-bottom-full-width",
       "onclick": null,
       "fadeIn": 300,
       "fadeOut": 1000,
       "timeOut": 5000,
       "extendedTimeOut": 1000
     }
</script>
<script>
$(document).ready(function(){
$.ajax({
    url: "<?php echo base_url().'Home/wishgraph?shop='.$_GET['shop'] ?>",
    method: "GET",
    success: function(data) {
      var data = JSON.parse(data);
      // console.log('data', data);
      var additions = [];
      var total = [];

      for(var i=0; i<data.length; i++) {
        additions.push(data[i].datecreated);
        total.push(data[i].total);
        if(i == 7)
        {
          break;
        }
      }
      // console.log(additions);
      // console.log(total);
      var chartdata = {
        labels: additions,
        datasets : [
          {
            label: 'Wishlist Additions',
            backgroundColor: 'rgba(255, 99, 132, 0.75)',
            borderColor: 'rgba(255, 99, 132, 0.75)',
            hoverBackgroundColor: 'rgba(255, 99, 132, 1)',
            hoverBorderColor: 'rgba(255, 99, 132, 1)',
            data: total
          }
        ]
      };

      var ctx = $("#myChart");

      var barGraph = new Chart(ctx, {
        type: 'bar',
        data: chartdata
      });
    },
    error: function(data) {
      // console.log(data);
    }
  });
  });

</script>
<script>
var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active1");
    var content = this.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
    }
  });
}
</script>
</body>
</html>
