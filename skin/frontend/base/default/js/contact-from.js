(function( $ ) {
  $(function() {
   
    if($('select[name="subject"]').val() == 1){
        $('#otherSubject').show();
    }else{
        $('#otherSubject').hide();
    }
    $('select[name="subject"]').click(function(){
        if($(this).val() == 1){
            $('#otherSubject').show();
        }else{
            $('#otherSubject').hide();
        }
    })
  });
})(jQuery);


