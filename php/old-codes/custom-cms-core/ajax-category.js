<!--   /////////////////////////   ajax part start  -->



			$(function (){

					$("select#category").change(function(){
						if($(this).val()==0)
						{
							option = '<option value="0">Home Page</option>';
							$('#sub_category').html(option);
						}
						else
						{
							$.ajax({

		  					 		type:'get',

		   						   url:'includes/ajax_handler.php',

		   						   data: { cat_id: $(this).val()},

								   dataType:"xml",

								   timeout:10000,

								   success: function(data){

									   /*var sub_cats = data.split('|');

									   var option = '';

									   $.each(sub_cats,function(){

																option += '<option value="'+this+'">'+this+'</option>';

																});

									   $('#city').html(option);*/

									   var option = '';

									   $(data)

									   .find('answer')

									   .children()

									   .each(function(){

													  var node = $(this);

													  var name = node.find('name').text();

													  var id = node.find('id').text();

													  option += '<option value="'+id+'">'+name+'</option>';

													  });

									   $('#sub_category').html(option);

													  

								   },

								   error: function(){

									   alert("There is connection problem with server.\nPlease try again later.");

								   }
								   

		   						});
						}

						})

		   })

            <!--   /////////////////////////   ajax part end  -->