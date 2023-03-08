$(document).ready(function() {

  console.log(ajaxUrls);
  // $.LoadingOverlay("show");
  var interval = setInterval(doStuff, 2000);
  function doStuff(){
    if(window.sessionToken){
      clearInterval(interval);
      window.DoActions();
    }
  }

  function dashboardpicker() {
      var dateRange = $('#dashboardpicker').val();
      dateRangeArray = dateRange.split(" ");
      var start_date = dateRangeArray[0];
      var end_date = dateRangeArray[2];
      if (start_date != '' && end_date != '') {
          $.ajax({
              url: ajaxUrls.getListInRange,
              method: 'post',
              dataType: 'json',
              data: {start_date: start_date,end_date: end_date},
              beforeSend: function() {
                  $.LoadingOverlay("show");
              },
              complete: function() {
                  $.LoadingOverlay("hide");
              },
              success: function(data) {
                if(data.code){
                  window.ShowErrorToast(data.msg);
                  window.GenerateSessionToken();
                  dashboardpicker();
                }else{
                  var avg = data.customers / data.products;
                  var avg1 = isNaN(avg)  ? 0 : avg.toFixed(2);
                  $('.dashbordWishlist').text(data.wishitems);
                  $('.dashbordAverage').text(avg1);
                  $('.dashbordCustomers').text(data.customers);
                  $('.dashbordProducts').text(data.products);
                }
              },
              error: function(error) {
                  $.LoadingOverlay("hide");
                  toastr.error('Error', 'Sever error');
              },
          });
      } else {
          toastr.warning('Warning', 'Date is Required');
      }
  }

  function fetch_Activitydata(is_date_search, start_date = '', end_date = '', added_to_list = '', removed_from_list = '', new_list = '', added_to_cart = '') {
    $activityTable = $('#activity-table').DataTable({
          "bSort": false,
          "searching": true,
          "processing": true,
          "paging": true,
          "pageLength": 10,
          "serverSide": true,
          "order": [
              [0, "desc"]
          ],
          "ajax": {
              url: ajaxUrls.fetchactivitydata,
              type: 'POST',
              data: {is_date_search: is_date_search,start_date: start_date,end_date: end_date,added_to_list: added_to_list,removed_from_list: removed_from_list,new_list: new_list,added_to_cart: added_to_cart},
              beforeSend: function() {
                  $.LoadingOverlay("show");
              },
              complete: function(data) {
                var response = JSON.parse(data.responseText);

                if(response.code && response.code == 100){
                  window.ShowErrorToast(response.msg);
                  window.GenerateSessionToken();
                  $activityTable.ajax.reload();
                }
                $.LoadingOverlay("hide");
              },
          }
      });
  }

  function LoadWishGraph(){
    $.ajax({
        url: ajaxUrls.wishgraph,
        method: "GET",
        dataType: "json",
        success: function(data) {
          if(data.code && data.code == 100){
            window.ShowErrorToast(data.msg);
            window.GenerateSessionToken();
            LoadWishGraph();
          }else{
            var additions = [];
            var total = [];
            for (var i = 0; i < data.length; i++) {
                additions.push(data[i].datecreated);
                total.push(data[i].total);
                if (i == 7) {
                    break;
                }
            }
            var chartdata = {
                labels: additions,
                datasets: [{
                    label: 'Wishlist Additions',
                    backgroundColor: 'rgba(255, 99, 132, 0.75)',
                    borderColor: 'rgba(255, 99, 132, 0.75)',
                    hoverBackgroundColor: 'rgba(255, 99, 132, 1)',
                    hoverBorderColor: 'rgba(255, 99, 132, 1)',
                    data: total
                }]
            };
            var ctx = $("#myChart");
            if($("#myChart").length > 0){
              var barGraph = new Chart(ctx, {
                    type: 'bar',
                    data: chartdata
                });
            }
          }
        },
        error: function(data){
        }
    });
  }
  function fetch_Customersdata(is_date_search, start_date = '', end_date = '') {
      var dataTableCustomers = $('#customers-table').DataTable({
          "bSort": false,
          "searching": true,
          "processing": true,
          "pageLength": 10,
          "serverSide": true,
          "order": [
              [0, "desc"]
          ],
          "ajax": {
              url: ajaxUrls.fetchCustomersdata,
              type: 'POST',
              data: {is_date_search: is_date_search,start_date: start_date,end_date: end_date},
              beforeSend: function() {
                  $.LoadingOverlay("show");
              },
              complete: function(data) {
                var response = JSON.parse(data.responseText);
                if(response.code && response.code == 100){
                  window.ShowErrorToast(response.msg);
                  window.GenerateSessionToken();
                  dataTableCustomers.ajax.reload();
                }
                $.LoadingOverlay("hide");
              },
          }
      });
  }

  function fetch_Productsdata(is_date_searchP, start_dateP = '', end_dateP = '') {
      var dataTableProducts = $('#products-table').DataTable({
          "bSort": false,
          "searching": true,
          "processing": true,
          "pageLength": 10,
          "serverSide": true,
          "order": [
              [0, "desc"]
          ],
          "ajax": {
              url: ajaxUrls.fetchProductsdata,
              type: 'POST',
              data: {is_date_search: is_date_searchP,start_date: start_dateP,end_date: end_dateP},
              beforeSend: function() {
                  $.LoadingOverlay("show");
              },
              complete: function(data) {
                var response = JSON.parse(data.responseText);
                if(response.code && response.code == 100){
                  window.ShowErrorToast(response.msg);
                  window.GenerateSessionToken();
                  dataTableProducts.ajax.reload();
                }
                $.LoadingOverlay("hide");
              },
          }
      });
  }

  function saveForResetEmailTextRemainder(value) {
      var myForm = document.getElementById('settingform');
      var form_data = new FormData(myForm);
      $.ajax({
          url: ajaxUrls.savesetting,
          type: "POST",
          dataType: 'json',
          processData: false,
          contentType: false,
          cache: false,
          data: form_data,
          beforeSend: function() {
              $.LoadingOverlay("show");
          },
          complete: function() {
              $.LoadingOverlay("hide");
          },
          success: function(ok) {
              if (ok.code == 100) {
                  toastr.error('Failed', ok.msg);
                  $.LoadingOverlay("hide");
                  window.GenerateSessionToken();
              } else {
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



  function saveForResetEmailTextSale(value) {
      var myForm = document.getElementById('settingform');
      var form_data = new FormData(myForm);
      $.ajax({
          url: ajaxUrls.savesetting,
          type: "POST",
          dataType: 'json',
          processData: false,
          contentType: false,
          cache: false,
          data: form_data,
          beforeSend: function() {
              $.LoadingOverlay("show");
          },
          complete: function() {
              $.LoadingOverlay("hide");
          },
          success: function(ok) {
              if (ok.code == 100) {
                  toastr.error('Failed', ok.msg);
                  $.LoadingOverlay("hide");
                  window.GenerateSessionToken();
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

  window.DoActions=function(){
    $('#wishlistbtn').bootstrapToggle({
        on: 'Enabled',
        off: 'Disabled'
    });
    $('#removeItemFromWishlistToggle').bootstrapToggle({
        on: 'Yes',
        off: 'No'
    });
    $('#removeItemFromWishlistToggle').on('change', function() {
        var enable = $(this).prop('checked') == true ? 1 : 0;
        $('#removeItemFromWishlist').val(enable);
    });

    $('#wishlistbtn').on('change', function() {
        var enable = $(this).prop('checked') == true ? 1 : 0;
        $.ajax({
            url: ajaxUrls.enableWishlist,
            method: 'POST',
            data: {enable: enable},
            dataType: 'json',
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                $.LoadingOverlay("hide");
            },
            success: function(data) {
                console.log(data)
                if (data.code == 100) {
                    toastr.error('Failed', data.msg);
                    $.LoadingOverlay("hide");
                     window.GenerateSessionToken();
                } else {
                    toastr.success('Success', data.msg);
                    $.LoadingOverlay("hide");
                }
                $('#enable_wishlist').val(enable);
            },
            error: function(data) {
                window.ShowErrorToast('Failed to load setting for enabling wishlist. Please try again.');
            },
        });
    });
    $("#dashboardpicker").flatpickr({
        mode: "range",
        maxDate: "today",
        altInput: false,
        dateFormat: "Y-m-d",
        onClose: function(selectedDates, dateStr, instance) {
            dashboardpicker();
        },
        onReady: function(selectedDates, dateStr, instance) {
            dashboardpicker();
        }
    });



    $("#activitypicker").flatpickr({
        mode: "range",
        maxDate: "today",
        altInput: false,
        dateFormat: "Y-m-d",
    });

    fetch_Activitydata('no');

    $('#submitActivityfilter').click(function() {
        var dateRange = $('#activitypicker').val();
        var added_to_list = $('#added_to_list:checked').val();
        var removed_from_list = $('#removed_from_list:checked').val();
        var new_list = $('#new_list:checked').val();
        var added_to_cart = $('#added_to_cart:checked').val();
        dateRangeArray = dateRange.split(" ");
        var start_date = dateRangeArray[0];
        var end_date = dateRangeArray[2];
        if ((start_date != '' && end_date != '') || added_to_list != '' || removed_from_list != '' || new_list != '' || added_to_cart != '') {
            $('#activity-table').DataTable().destroy();
            fetch_Activitydata('yes', start_date, end_date, added_to_list, removed_from_list, new_list, added_to_cart);
            $('#myModal2').modal('hide');
        }
    });
    $('#resetActivity').click(function() {
        $('#activity-table').DataTable().destroy();
        $('#activitypicker').val('');
        $('.checkbox').prop("checked", false);
        $('#myModal2').modal('hide');
        fetch_Activitydata('no');
    });



    $("#customerpicker").flatpickr({
        mode: "range",
        maxDate: "today",
        altInput: false,
        dateFormat: "Y-m-d",
    });

    fetch_Customersdata('no');

    $('#submitCustomerDate').click(function() {
        var dateRange = $('#customerpicker').val();
        dateRangeArray = dateRange.split(" ");
        var start_date = dateRangeArray[0];
        var end_date = dateRangeArray[2];
        if (start_date != '' && end_date != '') {
            $('#customers-table').DataTable().destroy();
            fetch_Customersdata('yes', start_date, end_date);
            $('#myModal2').modal('hide');
        }
    });
    $('#resetCustomers').click(function() {
        $('#customers-table').DataTable().destroy();
        $('#customerpicker').val('');
        $('#myModal2').modal('hide');
        fetch_Customersdata('no');
    });

    $("#productspicker").flatpickr({
        mode: "range",
        maxDate: "today",
        altInput: false,
        dateFormat: "Y-m-d",
    });
    fetch_Productsdata('no');


    $('#submitProductDate').click(function() {
        var dateRangeP = $('#productspicker').val();
        dateRangeArrayP = dateRangeP.split(" ");
        var start_dateP = dateRangeArrayP[0];
        var end_dateP = dateRangeArrayP[2];
        if (start_dateP != '' && end_dateP != '') {
            $('#products-table').DataTable().destroy();
            fetch_Productsdata('yes', start_dateP, end_dateP);
            $('#myModal2').modal('hide');
        }
    });
    $('#resetProducts').click(function() {
        $('#products-table').DataTable().destroy();
        $('#productspicker').val('');
        $('#myModal2').modal('hide');
        fetch_Productsdata('no');
    });
    $('.email_config').on('keyup', function() {
        var isValid = true;
        $('.email_config').each(function() {
            if ($(this).val() === '') {
                isValid = false;
            }
        });
        if (isValid) {
            $('#enable_email').prop('disabled', false);
        } else {
            $('#enable_email').prop('disabled', true);
        };
    });
    $('#testEmailForm').submit(function(e) {
        e.preventDefault();
        var myForm = document.getElementById('testEmailForm');
        var form_data = new FormData(myForm);
        $.ajax({
            url: ajaxUrls.testEmail,
            type: "POST",
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            data: form_data,
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                $.LoadingOverlay("hide");
            },
            success: function(data) {
                if (data.code == 100) {
                    toastr.error(data.msg);
                    $.LoadingOverlay("hide");
                    window.GenerateSessionToken();
                } else if (data.code == 200) {
                    toastr.success('Success', 'Mail send');
                    $.LoadingOverlay("hide");
                }
            },
            error: function() {
                $.LoadingOverlay("hide");
            },
        });
    });
    $('#settingform').submit(function(e) {
        e.preventDefault();
        var myForm = document.getElementById('settingform');
        var form_data = new FormData(myForm);
        $.ajax({
            url: ajaxUrls.savesetting,
            type: "POST",
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            data: form_data,
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                $.LoadingOverlay("hide");
            },
            success: function(ok) {
                if (ok.code == 100) {
                    window.GenerateSessionToken();
                    toastr.error('Failed', ok.msg);
                    $.LoadingOverlay("hide");
                } else {
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

      $("input[name='email_template_sale']").on('change', function(e) {
        var radioValue = $("input[name='email_template_sale']:checked").val();
        var emailText_sale_textarea = $("#emailText_sale");
        if (emailText_sale == "") {
            if (radioValue == 1) {
                value = "<p>Remember the awesome item below ? you saved it in our wishlist , and guess what? it's on sale!</p><p>[product]</p><p>Pieces priced like this don't stick around long, so hurry in to snag your most-coveted ModCloth merch! If you arrive at the site, and the item is already sold out, we're sorry.But, at least you Know you have great taste.</p><p>Happy shopping!</p>";
                emailText_sale_textarea.text(value);
                CKEDITOR.instances['emailText_sale'].setData(value);
            } else if (radioValue == 2) {
                value = "<h2>Your wishlist item(s) is on sale!</h2><p>You saved it in our wishlist</p><p>[products]</p><p>Hurry in to make sure you sang your most-coveted</p><p>Happy shopping!</p>";
                emailText_sale_textarea.text(value);
                CKEDITOR.instances['emailText_sale'].setData(value);
            } else if (radioValue == 3) {
                value = "<h2><strong>The product of your wishlist items is on sale!</strong></h2><p>[products]</p><p>We only have a limited amount, and this is not a guarantee you will get one, so hurry to be one of the lucky shoppers who do. </p><p>Thank you.</p> ";
                emailText_sale_textarea.text(value);
                CKEDITOR.instances['emailText_sale'].setData(value);
            } else {
                alert('invalid template');
            }
        }
    });


      $("input[name='email_template_remainder']").on('change', function(e) {
        var radioValue = $("input[name='email_template_remainder']:checked").val();
        var emailText_remainder_textarea = $("#emailText_remainder");
        if (emailText_remainder == "") {
            if (radioValue == 1) {
                value = "<p>Remember the awesome item below ? you saved it in our wishlist , and guess what? it's on sale!</p><p>[product]</p><p>Pieces priced like this don't stick around long, so hurry in to snag your most-coveted ModCloth merch! If you arrive at the site, and the item is already sold out, we're sorry.But, at least you Know you have great taste.</p><p>Happy shopping!</p>";
                emailText_remainder_textarea.text(value);
                CKEDITOR.instances['emailText_remainder'].setData(value);
            } else if (radioValue == 2) {
                value = "<h2>Your wishlist item(s) is on sale!</h2><p>You saved it in our wishlist</p><p>[products]</p><p>Hurry in to make sure you sang your most-coveted</p><p>Happy shopping!</p>";
                emailText_remainder_textarea.text(value);
                CKEDITOR.instances['emailText_remainder'].setData(value);
            } else if (radioValue == 3) {
                value = "<h2><strong>The product of your wishlist items is on sale!</strong></h2><p>[products]</p><p>We only have a limited amount, and this is not a guarantee you will get one, so hurry to be one of the lucky shoppers who do. </p><p>Thank you.</p> ";
                emailText_remainder_textarea.text(value);
                CKEDITOR.instances['emailText_remainder'].setData(value);
            } else {
                alert('invalid template');
            }
        }
    });

    $('#reset_remainder_body').click(function(e) {
        var radioValue = $("input[name='email_template_remainder']:checked").val();
        var emailText_remainder = $("#emailText_remainder");
        if (confirm('Are sure you want to reset the email body?')) {
            if (radioValue == 1) {
                value = "<p>You saved these item(s) in our wishlist since [since_date]. Please feel free to shop them.</p><p>[products]</p><p>Hurry in to make sure you sang your most-coveted</p><p>Happy shopping!</p>";
                emailText_remainder.text(value);
                saveForResetEmailTextRemainder(value);
            } else if (radioValue == 2) {
                value = "<h2>Your wishlist item(s) are missing you!</h2><p>Remember the awesome item(s) below? you saved it in our wishlist since [since_date].</p><p>[products]</p><p>Hurry in to make sure you sang your most-coveted</p><p>Happy shopping!</p>";
                emailText_remainder.text(value);
                saveForResetEmailTextRemainder(value);
            } else if (radioValue == 3) {
                value = "<h2><strong>&nbsp;Your loved items are waiting for you!</strong></h2><p>Thank you for visiting our shop. We saved your loved item(s) since [since_date], so please feel free to continue your shopping</p><p>[products]</p><p>Thank you.</p> ";
                emailText_remainder.text(value);
                saveForResetEmailTextRemainder(value)
            } else {
                alert('invalid template');
            }
        }
    });


    $('#reset_sale_body').click(function(e) {
        var radioValue = $("input[name='email_template_sale']:checked").val();
        var emailText_remainder = $("#emailText_sale");
        if (confirm('Are sure you want to reset the email body?')) {
            if (radioValue == 1) {
                value = "<p>Remember the awesome item below ? you saved it in our wishlist , and guess what? it's on sale!</p><p>[product]</p><p>Pieces priced like this don't stick around long, so hurry in to snag your most-coveted ModCloth merch! If you arrive at the site, and the item is already sold out, we're sorry.But, at least you Know you have great taste.</p><p>Happy shopping!</p>";
                emailText_remainder.text(value);
                saveForResetEmailTextSale(value);
            } else if (radioValue == 2) {
                value = "<h2>Your wishlist item(s) is on sale!</h2><p>You saved it in our wishlist</p><p>[products]</p><p>Hurry in to make sure you sang your most-coveted</p><p>Happy shopping!</p>";
                emailText_remainder.text(value);
                saveForResetEmailTextSale(value);
            } else if (radioValue == 3) {
                value = "<h2><strong>The product of your wishlist items is on sale!</strong></h2><p>[products]</p><p>We only have a limited amount, and this is not a guarantee you will get one, so hurry to be one of the lucky shoppers who do. </p><p>Thank you.</p> ";
                emailText_remainder.text(value);
                saveForResetEmailTextSale(value)
            } else {
                alert('invalid template');
            }
        }
    });


    $('#feedbackForm').submit(function(e) {
        e.preventDefault();
        var myForm = document.getElementById('feedbackForm');
        var form_data = new FormData(myForm);
        $.ajax({
            url: ajaxUrls.saveFeedback,
            type: "POST",
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            data: form_data,
            beforeSend: function() {
                $.LoadingOverlay("show");
            },
            complete: function() {
                $.LoadingOverlay("hide");
            },
            success: function(data) {
                if (data.code == 403 || data.code == 100) {
                    toastr.error('Error', data.mgs);
                    $.LoadingOverlay("hide");
                      window.GenerateSessionToken();
                } else {
                    $('#feedbackForm').each(function() {
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
    $("#collapseBtn1").click(function() {
        $("#collapse1").slideToggle("slow");
    });
    $("#collapseBtn2").click(function() {
        $("#collapse2").slideToggle("slow");
    });



baguetteBox.run('.grid-gallery', {
    animation: 'slideIn'
});
toastr.options = {"closeButton": true,"debug": false,"positionClass": "toast-bottom-full-width","onclick": null,"fadeIn": 300,"fadeOut": 1000,"timeOut": 5000,"extendedTimeOut": 1000};
LoadWishGraph();

var coll = document.getElementsByClassName("collapsible");
var i;
for (i = 0; i < coll.length; i++) {
    coll[i].addEventListener("click", function() {
        this.classList.toggle("active1");
        var content = this.nextElementSibling;
        if (content.style.maxHeight) {
            content.style.maxHeight = null;
        } else {
            content.style.maxHeight = content.scrollHeight + "px";
        }
    });
}

}


})
