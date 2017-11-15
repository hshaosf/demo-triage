"use strict";
(function(_w, _d, $, _v){
	var interval=10, timer=interval, limit_item=10, index=0, max=0, tickets, token='4187f4e168db42f89351adbc0fe3d29a', api_url='https://api.dialogflow.com/v1/', recognition;
	var triage = {
		next : function(){ 
			if(interval >= timer){
				this.print_item();
			}else{
				console.log('skip');
				timer -= interval;
			}
		},
		print_item : function(text){
			if(!text && max){
				text = tickets.eq(index).find('title').text();
				index += 1;
				if(index >=max){
					index = 0;
				}
			}
			if(!text){
				return;
			}
			this.send(text);
			
		},
		reset_timer : function(){
			timer = interval;
		},
		delay_timer : function(){
			timer += interval;
		},
		get_item : function(text, legend){
			$('#input').attr('placeholder', text);
			var item = $('<div class="row"><div class="col-lg-12"><div class="alert alert-'+legend+'" role="alert">'+text+'</div></div></div>');
			$(item).prependTo($('#sr')).hide().slideDown();
			if($('#sr .row').length > limit_item){
				$('#sr .row').last().remove();
			}
			this.reset_timer();
		},
		load_tickets : function(){
			$.ajax({
			    type: "GET",
			    url: "sample/tickets.xml",
			    dataType: "xml",
			    success: function (xml) {
			        tickets = $(xml).find('item');
			        max=tickets.length;
			    }
			});
		},
		send : function(text){
				var _t = this;
        $.ajax({
            type: "POST",
            url: api_url + "query?v=20150910",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: {
                "Authorization": "Bearer " + token
            },
            data: JSON.stringify({ query: text, lang: "en", sessionId: "Ddbl7fhYCdusQE4ho6ethEgCl4aMkmvRuq" }),
            success: function(data) {
            		var text = data.result.resolvedQuery;
                var legend = data.result.fulfillment.speech;
                _t.get_item(text, _t.get_legend(legend));
            },
            error: function() {
                console.log("Error");
            }
        });
    },
    get_legend : function(cat){
    	var legend = 'light';
    	switch(cat){
    		case 'Content Posting & Accessibility' :
    			legend = 'primary';
    			break;
    		case 'WCM Administration' :
    			legend = 'secondary';
    			break;
    		case 'WCM Operations' :
    			legend = 'warning';
    			break;
    		case 'Website Enhancements' :
    			legend = 'success';
    			break;
    		case 'Website Fixes' :
    			legend = 'danger';
    			break;
    	}
    	return legend;
    },
    prep : function(){
    	var _t = this;
    	$("#input").keypress(function(event) {
            if (event.which == 13) {
                event.preventDefault();
                _t.input();
            }
        });
    	$('.input-group button').click(function(){ _t.input(); });
    },
    input : function(){
    	this.delay_timer();
    	this.send($('#input').val());
    	$('#input').val('');
    },
		ready : function(){	
			console.log('Welcome');
			this.prep();
			this.next();
			setInterval(this.next.bind(this), interval*1000);
			this.load_tickets();
		}
	}
	$(document).ready(function(){triage.ready()});
})(window, document, jQuery, {});