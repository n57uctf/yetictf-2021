$(document).ready(function() {
     $('#type1').change(function(){

            var type1 = $('#type1').val();
            var type2 = $('#type2').val();
            var amount2 = $('#amount2').val();
      
            $.ajax({
              url:baseURL+'management/exchange_prep',
              method:"POST",
              data:{'type1':type1, 'type2':type2, 'amount2':amount2},
              success:function(result)
              {
               document.getElementById('amount1').value = result;
              }
             });
    });
     $('#type2').change(function(){

            var type1 = $('#type1').val();
            var type2 = $('#type2').val();
            var amount2 = $('#amount2').val();
      
            $.ajax({
              url:baseURL+'management/exchange_prep',
              method:"POST",
              data:{'type1':type1, 'type2':type2, 'amount2':amount2},
              success:function(result)
              {
               document.getElementById('amount1').value = result;
              }
             });
    });
     $('#amount2').change(function(){

            var type1 = $('#type1').val();
            var type2 = $('#type2').val();
            var amount2 = $('#amount2').val();
      
            $.ajax({
              url:baseURL+'management/exchange_prep',
              method:"POST",
              data:{'type1':type1, 'type2':type2, 'amount2':amount2},
              success:function(result)
              {
               document.getElementById('amount1').value = result;
              }
             });
    });
});