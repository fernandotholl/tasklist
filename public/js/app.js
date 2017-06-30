jQuery(document).ready(function($) {

	// Para testes
	var debug = true;
	var username = 'test';
	var password = '123'; // capture token

    var tasklistObj = $("#tasklist");

    function itemFormat (id, title, description, created_at, updated_at = '', status) {
    	varUpdate = updated_at != '' ? ' (Updated: '+updated_at+')' : '';
    	
    	var html = '';
    	if(status == 1){
    		status_label = 'on';
    		str_ck = '<button class="btn btn-xs btn-success btnCk" data-id="'+id+'" data-status="2" title="Conclude"><i class="glyphicon glyphicon-ok"></i></button> | ';
    	}else{
    		status_label = 'off';
    		str_ck = '<button class="btn btn-xs btn-inverse btnCk" data-id="'+id+'" data-status="1" title="Redo"><i class="glyphicon glyphicon-check"></i></button> | ';
    	}

    	html += '<li data-id="'+id+'" id="task_'+id+'" class="row task_'+status_label+'">';
    	html += '<div class="col-lg-8 lb"><span>#'+id+' '+title+'</span> <small><i>Date: '+created_at+' '+varUpdate+'</i></small></div>'
              +  '<div class="col-lg-4 act"> '+str_ck+' <a href="#" class="btn btn-info btn-xs btnEdit" data-id="'+id+'">Edit</a> | <a href="#" class="btn btn-danger btn-xs btnRem" data-id="'+id+'">Remove</a> </div>'
    	html += '</li>';

        return html;
    }

    function inputFormat (id = '', value = '') {
    	id = id == '' ? 'new' : id;
    	return '<div class="input-group" id="add-form">'
		       +'    <input type="text" class="form-control input-sm inputTask" name="task_'+id+'" value="'+value+'">'
		       +'    <span class="input-group-btn">'
		       +'        <button class="btn btn-success btn-sm btnUpdate" data-id="'+id+'" type="button">OK</button>'
		       +'    </span>'
		       +'</div>';
    }    

    var fnListTasks = (function() {
		
		$.ajax({
		    type: "GET",
		    url: "api/tasks",
		    dataType: 'json',
		    async: false,
		    beforeSend: function (xhr) {
			    xhr.setRequestHeader("Authorization", "Basic " + btoa(username + ":" + password));
			}
		}).done(function(data) {

			if(data.length > 0){
				$.each(data, function(k, v) {
					$("#tasklist").append(itemFormat(
						v.id, 
						v.title, 
						v.description, 
						v.created_at, 
						v.updated_at,
						v.status
					));
				});
			}else{
				$("#tasklist").append('<li>Empty</li>');
			}
  			
		});

	})();

	$(document).on('click', ".btnAdd", function(){

		var title = $('.newTitle').val();

		$.ajax({
		    type: "POST",
		    url: "api/tasks",
		    dataType: 'json',
		    async: false,
		    data: {
		    	"title" : title
		   	},
		    beforeSend: function (xhr) {
			    xhr.setRequestHeader("Authorization", "Basic " + btoa(username + ":" + password));
			}
		}).done(function(data) {

			$("#tasklist li:first").before(itemFormat(
				data.id, 
				data.title, 
				data.description, 
				data.created_at, 
				data.updated_at,
				1
			));

			$('.newTitle').val('');
  			
		});

	});

	$(document).on('click', ".btnEdit", function(){

		var id = $(this).attr('data-id');
		var old_value = $(this).parent().parent().find('span').text();
			old_value = old_value.replace(/^#[0-9]+ /i, '');

		$(this).parent()
			   .parent()
			   .find('.lb')
			   .html(inputFormat(id, old_value));

	});

	$(document).on('click', ".btnCk", function(){

		var id = $(this).attr('data-id');
		var status = $(this).attr('data-status');

		$.ajax({
		    type: "POST",
		    url: "api/tasks/complete/"+id,
		    dataType: 'json',
		    async: false,
		    data: {
		    	"status" : status
		   	},
		    beforeSend: function (xhr) {
			    xhr.setRequestHeader("Authorization", "Basic " + btoa(username + ":" + password));
			}
		}).done(function(data) {

			var replace = itemFormat(
				data.id, 
				data.title, 
				data.description, 
				data.created_at, 
				data.updated_at,
				data.status
			);

			$('#task_'+id).replaceWith(replace);
  			
		});


	});
	
	$(document).on('click', ".btnUpdate", function(){

		var id = $(this).attr('data-id');
		var title = $(this).parent().parent().find('.inputTask').val();

		$.ajax({
		    type: "PUT",
		    url: "api/tasks/"+id,
		    dataType: 'json',
		    async: false,
		    data: {
		    	"title" : title
		   	},
		    beforeSend: function (xhr) {
			    xhr.setRequestHeader("Authorization", "Basic " + btoa(username + ":" + password));
			}
		}).done(function(data) {

			var replace = itemFormat(
				data.id, 
				data.title, 
				data.description, 
				data.created_at, 
				data.updated_at,
				data.status
			);

			$('#task_'+id).replaceWith(replace);
  			
		});

	});

	$(document).on('click', ".btnRem", function(){
		
		var id = $(this).attr('data-id');

		$.ajax({
		    type: "DELETE",
		    url: "api/tasks/"+id,
		    dataType: 'json',
		    async: false,
		    beforeSend: function (xhr) {
			    xhr.setRequestHeader("Authorization", "Basic " + btoa(username + ":" + password));
			}
		}).done(function(data) {
			$('#task_'+id).remove();
		});

	});
	

    // List initial tasks
    fnListTasks;

    tasklistObj.sortable({
    	update: function( event, ui ) {
    		var serial = tasklistObj.sortable("serialize");
    		var dataArray = tasklistObj.sortable("toArray");

    		$.ajax({
			    type: "POST",
			    url: "api/tasks/order",
			    dataType: 'json',
			    async: false,
			    data: {
			    	orders: dataArray
			    },
			    beforeSend: function (xhr) {
				    xhr.setRequestHeader("Authorization", "Basic " + btoa(username + ":" + password));
				}
			}).done(function(data) {


			});

			if(debug){
    			$('#output').show('fast').val(serial);
    		}

    	}
    });
	
});