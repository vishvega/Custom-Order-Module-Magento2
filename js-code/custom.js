require(['jquery'], function ($) {
    jQuery(document).ready(function(){
    
    // AJAX FORM


      $("#qoSubmitBtn").click(function(){
        
      var qostockno = $("#qoStockNo").val();
      var qoqty = $("#qoQty").val();

      //alert(qostockno+qoqty);

      var dataString = {'qostockno1':qostockno, 'qoqty1':qoqty};
      if(qostockno==''||qoqty=='')
      {
      alert("Please Fill All Necessary Fields");
      }
      else
      {
      // AJAX Code To Submit Form.
      $.ajax({
      type: "POST",
      url: "http://YOURSITE.COM/quikorder/index/index",
      data: dataString,
      cache: false,
      success: function(result){
      alert(result);


      // Update minicart after product add
      require([
              'Magento_Customer/js/customer-data'
          ], function (customerData) {
              var sections = ['cart'];
              customerData.invalidate(sections);
              customerData.reload(sections, true);
          });
      // end update minicart
      }
      });
      }
      return false;
      });

// end AJAX Code To Submit Form.
    
    
 });
});
