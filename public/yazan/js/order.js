// 添加车型级联菜单js
$(document).ready(function(){

	//用户代理等级ajax
	$('#user_id')[0].selectedIndex = 0; 
	//获得一级代理列表
	$('#user_id').change(function(){

		var user_id      = $(this).val();
		var token        = $("input[name='_token']").val();
		var request_url  = $("input[name='user_ajax_request_url']").val();
		
		// alert(user_id);return false;
		//获得该总代理的子代理
        $.ajax({
			type: 'POST',		
			url: request_url,		
			data: { user_id : user_id},		
			dataType: 'json',		
			headers: {		
				'X-CSRF-TOKEN': token		
			},		
			success: function(data){		
				if(data.status == 1){
					console.log(data.data.parent);
					return false;
					var content = '<option  value="0">--一级代理--</option>';
					$.each(data.data, function(index, value){
						content += '<option value="';
						content += value.id;
						content += '">';
						content += value.nick_name;
						content += '</option>';
					});
					// $('#user_id').append(content);
					// console.log($('#agents_frist'));
					$('#agents_frist').empty();
					$('#agents_frist').append(content);
					// console.log(content);
					$('#agents_frist').css('display', 'inline-block');
				}else{
					alert(data.message);
					$('#agents_frist').empty();
					$('#agents_frist').append('<option  value="0">--一级代理--</option>');
					$('#agents_frist').hide();
					$('#agents_secend').hide();
					return false;
				}
			},		
			error: function(xhr, type){

				alert('Ajax error!');
			}
		});
	});     
});