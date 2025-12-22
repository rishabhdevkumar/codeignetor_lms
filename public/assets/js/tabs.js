$(document).ready(function()
{
	$('.tabs-stage .main_div').hide();
	$('.tabs-stage .main_div:first').show();
	$('.tabs-nav li:first').addClass('tab-active');
    
	
	$('.tabs-nav a').on('click', function(event)
	{
	  event.preventDefault();
	  $('.tabs-nav li').removeClass('tab-active');
	  $(this).parent().addClass('tab-active');
	  $('.tabs-stage .main_div').hide();
	  var id=$(this).attr('href');	  
	  $(".tabs-stage").find("#"+id+"").show();
	 
	  
	});
	
	
});