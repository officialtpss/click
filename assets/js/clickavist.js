jQuery(document).ready( function($) {
	$("a.tweetTemp").click(function() {
		var tempID = $(this).attr("id");
		
		var dataMailTo = $(this).attr("data-mailto");
		
		var dataID = $(this).attr("data-id");
		var dataTemplateID = $(this).attr("data-templateID");
		var dataidcontactlist = $(this).attr("data-idcontactlist");
		var dataSource = $(this).attr("data-source");
		
		var ajaxurl = clv_clickavist_script.ajax_url;

		$.post(ajaxurl, {
			action: 'clickavist_getTemplateById',
			id: dataID,
			source:dataSource,
			idContactList: dataidcontactlist,
			dataType: "json",
		}, function (response) {
			var data = JSON.parse(response);
			
			var idTweetTemplate = data['idTweetTemplate'];
			var header = data['header'];
			var pretext = data['pretext'];
			var sampletweet = data['tweet'];
			var posttext = data['posttext'];
			
			var pretext_length = data['pretext'].length;
			var posttext_length = data['posttext'].length;
			
			var prepost_length = 140-(pretext_length+posttext_length);
			/* alert(pretext_length+'--'+posttext_length+'='+prepost_length); return false;*/						
			$(".clickavist_tweet_popup").html('<div class="clickavist_popup"><a class="clickavist_close">&times;</a><form action="" method="post" id="clv_form'+dataID+'"><div class="clickavist_form"><div class="clickavist_form_field"><p>'+header+'</p></div><div class="clickavist_form_field"><p class="tweet_pretext">'+pretext+'</p></div><div class="clickavist_form_field"><textarea onkeyup="countChar(this)" class="clvtw_sample tweetText" id="tweetText" name="tweetText" placeholder="'+sampletweet+'"></textarea><div class="tweetError"></div><div class="tweet_char"><span class="tweetCharNum">'+prepost_length+'</span> characters remaining</div></div><div class="clickavist_form_field"><p class="tweet_posttext">'+posttext+'</p></div><div class="prepost_length" data-prepostLength="'+prepost_length+'"></div><div class="clickavist_form_field clickavist_flex"><input type="hidden" name="idTweetTemplate" value="'+dataTemplateID+'"><input type="hidden" name="idContactList" value="'+dataidcontactlist+'"><a  class="clickavist_inner_cancel clickavist_formbtn">Cancel</a><input onClick="clvSendTweet();" type="button" class="clickavist_formbtn" value="Send Tweet"></div></div></form></div>');			
			
			$("body").css("overflow", "hidden");
			$(".clickavist_tweet_popup").css("display", "block");

		});
	}); 
	
	$("a.tweetTempWidget").click(function() {
		var tempID = $(this).attr("id");
		var dataMailTo = $(this).attr("data-mailto");
		var dataID = $(this).attr("data-id");
		var dataTemplateID = $(this).attr("data-templateID");
		var dataidcontactlist = $(this).attr("data-idcontactlist");
		
		var dataSource = $(this).attr("data-source");
		
		var ajaxurl = clv_clickavist_script.ajax_url;

		$.post(ajaxurl, {
			action: 'clickavist_getTemplateById',
			id: dataID,
			source:dataSource,
			dataType: "json",
		}, function (response) {
			var data = JSON.parse(response);
			
			var idTweetTemplate = data['idTweetTemplate'];
			var header = data['header'];
			var pretext = data['pretext'];
			var sampletweet = data['tweet'];
			var posttext = data['posttext'];
			
			var pretext_length = data['pretext'].length;
			var posttext_length = data['posttext'].length;
			
			var prepost_length = 140-(pretext_length+posttext_length);
			/* alert(pretext_length+'--'+posttext_length+'='+prepost_length); return false;*/			
			$(".clickavist_tweet_popup_widget").html('<div class="clickavist_popup"><a class="clickavist_close">&times;</a><form action="" method="post" id="clv_form'+dataID+'"><div class="clickavist_form"><div class="clickavist_form_field"><p>'+header+'</p></div><div class="clickavist_form_field"><p class="tweet_pretext">'+pretext+'</p></div><div class="clickavist_form_field"><textarea onkeyup="countChar(this)" class="clvtw_sample tweetText" id="tweetText" name="tweetText" placeholder="'+sampletweet+'"></textarea><div class="tweetError"></div><div class="tweet_char"><span class="tweetCharNum">'+prepost_length+'</span> characters remaining</div></div><div class="clickavist_form_field"><p class="tweet_posttext">'+posttext+'</p></div><div class="prepost_length" data-prepostLength="'+prepost_length+'"></div><div class="clickavist_form_field clickavist_flex"><input type="hidden" name="idTweetTemplate" value="'+dataTemplateID+'"><input type="hidden" name="idContactList" value="'+dataidcontactlist+'"><a  class="clickavist_inner_cancel clickavist_formbtn">Cancel</a><input onClick="clvSendTweet();" type="button" class="clickavist_formbtn" value="Send Tweet"></div></div></form></div>');
			
			$("body").css("overflow", "hidden");
			$(".clickavist_tweet_popup_widget").css("display", "block");

		});
	});
	

});


jQuery(document).ready( function($) {
	$("a.emailTemp").click(function() {
		var tempID = $(this).attr("id");
		var dataID = $(this).attr("data-id");

		var ajaxurl = clv_clickavist_script.ajax_url;
		var dataSource = $(this).attr("data-source");
		
		$.post(ajaxurl, {
			action: 'clickavist_getTemplateById',
			id: dataID,
			source:dataSource,
			dataType: "json",
		}, function (response) {
			var data = JSON.parse(response);

			var idEmailTemplate = data['idEmailTemplate'];
			var header = data['header'];
			var to = data['to'];
			var cc = data['cc'];
			var subjectPretext = data['subjectPretext'];			
			var subjectPretextEmpty = $.trim(subjectPretext);
			
			var subject = data['subject'];
			
			var subjectPostText = data['subjectPostText'];
			var subjectPostTextEmpty = $.trim(subjectPostText);
			
			var bodyPretext = data['bodyPretext'];
			var bodyPretextEmpty = $.trim(bodyPretext);
			
			var body = data['body'];
			
			var bodyPosttext = data['bodyPosttext'];
			var bodyPosttextEmpty = $.trim(bodyPosttext);
						
			$(".clickavist_email_popup").html('<div class="clickavist_popup clickavist_email_popup_inner"><a class="clickavist_close">&times;</a><form method="post" action="" id="clv_emailform'+dataID+'"><div class="clickavist_form clickavist_email_form"><div class="clickavist_form_field"><p>'+header+'</p></div><div class="clickavist_form_field clickavist_flex clickavist_form_field1"><label>To</label><p class="clv_email_to">'+to+'</p></div><div class="clickavist_form_field clickavist_flex clickavist_cc"><label>Cc</label><input type="email" placeholder="" value="'+cc+'" name="cc" class="clv_email_cc"></div><div class="clickavist_form_field clickavist_flex clickavist_col4 "><label>Subject</label><p class="clv_email_subpretext">'+subjectPretext+'</p><input name="subject" class="clv_email_subject" type="text" placeholder="'+subject+'" ><p class="clv_email_subposttext">'+subjectPostText+'</p></div><div class="emailSubError"></div><div class="clickavist_form_field"><p class="clv_email_bodypretext">'+bodyPretext+'</p></div><div class="clickavist_form_field"><textarea class="clv_email_body" name="body" placeholder="'+body+'"></textarea></div><div class="emailBodyError"></div><div class="clickavist_form_field"><p class="clv_email_bodyposttext">'+bodyPosttext+'</p></div><div class="clickavist_form_field clickavist_flex"><a class="clickavist_inner_cancel clickavist_formbtn">Cancel</a><input type="button" class="clickavist_formbtn clvSendEmail" value="Send Email"></div></div></form></div>');

			
			$("body").css("overflow", "hidden");
			$(".clickavist_email_popup").css("display", "block");
		
		});	
	});
	
	$("a.emailTempWidget").click(function() {
		var tempID = $(this).attr("id");
		var dataID = $(this).attr("data-id");
		
		var ajaxurl = clv_clickavist_script.ajax_url;
		var dataSource = $(this).attr("data-source");
		
		$.post(ajaxurl, {
			action: 'clickavist_getTemplateById',
			id: dataID,
			source: dataSource,
			dataType: "json", 
		}, function (response) {
			var data = JSON.parse(response);

			var idEmailTemplate = data['idEmailTemplate'];
			var header = data['header'];
			var to = data['to'];
			var cc = data['cc'];
			var subjectPretext = '';//data['subjectPretext'];			
			var subjectPretextEmpty = $.trim(subjectPretext);
			
			var subject = data['subject'];
			
			var subjectPostText = data['subjectPostText'];
			var subjectPostTextEmpty = $.trim(subjectPostText);
			
			var bodyPretext = data['bodyPretext'];
			var bodyPretextEmpty = $.trim(bodyPretext);
			
			var body = data['body'];
			
			var bodyPosttext = data['bodyPosttext'];
			var bodyPosttextEmpty = $.trim(bodyPosttext);
						
			$(".clickavist_email_popup_widget").html('<div class="clickavist_popup clickavist_email_popup_inner"><a class="clickavist_close">&times;</a><form method="post" action="" id="clv_emailform'+dataID+'"><div class="clickavist_form clickavist_email_form"><div class="clickavist_form_field"><p>'+header+'</p></div><div class="clickavist_form_field clickavist_flex clickavist_form_field1"><label>To</label><p class="clv_email_to">'+to+'</p></div><div class="clickavist_form_field clickavist_flex clickavist_cc"><label>Cc</label><input type="email" placeholder="" value="'+cc+'" name="cc" class="clv_email_cc"></div><div class="clickavist_form_field clickavist_flex clickavist_col4 "><label>Subject</label><p class="clv_email_subpretext">'+subjectPretext+'</p><input name="subject" class="clv_email_subject" type="text" placeholder="'+subject+'" ><p class="clv_email_subposttext">'+subjectPostText+'</p></div><div class="emailSubError"></div><div class="clickavist_form_field"><p class="clv_email_bodypretext">'+bodyPretext+'</p></div><div class="clickavist_form_field"><textarea class="clv_email_body" name="body" placeholder="'+body+'"></textarea><div class="emailBodyError"></div></div><div class="clickavist_form_field"><p class="clv_email_bodyposttext">'+bodyPosttext+'</p></div><div class="clickavist_form_field clickavist_flex"><a class="clickavist_inner_cancel clickavist_formbtn">Cancel</a><input type="button" class="clickavist_formbtn clvSendEmail" value="Send Email"></div></div></form></div>');
					
			
			$("body").css("overflow", "hidden");
			$(".clickavist_email_popup_widget").css("display", "block");
		
		});	
	});
});

jQuery(document).ready( function($) {
	$(document).on("click",".clickavist_close, .clickavist_inner_cancel",function() {
		$("body").css("overflow", "auto");
		$(".clickavist_tweet_popup").css("display", "none");
		$(".clickavist_tweet_popup_widget").css("display", "none");
		$(".clickavist_email_popup").css("display", "none");
		$(".clickavist_email_popup_widget").css("display", "none");
	});
});


function clvSendTweet(){
	var formid = jQuery('.clickavist_formbtn').closest('form').attr('id');
	var ajaxurl = clv_clickavist_script.ajax_url;
	
	var tweet_pretext = jQuery('.tweet_pretext').text();
	var tweet_posttext = jQuery('.tweet_posttext').text();
	
	var prepost_length = jQuery('.prepost_length').attr("data-prepostLength");
	/*alert(tweet_pretext+"=="+tweet_posttext+"==="+prepost_length); return false; */
	var formdata = jQuery( 'form#'+formid).serialize();
	/*console.log(formdata);*/
	jQuery.post(ajaxurl, {
		action: 'clickavist_sendTweet',
		method: 'POST',
		tweet_pretext:tweet_pretext,
		tweet_posttext:tweet_posttext,
		prepost_length: prepost_length,
		data: formdata,
	}, function (response) {
		var data = JSON.parse(response);
		var tweetEmpty = data['empty']; 
		var tweetMore = data['more']; 
		var tweetError = data['error']; 
		if(tweetEmpty){ 
			jQuery(".tweetError").text(tweetEmpty);
			/*console.log('---'+tweetEmpty);*/
		}else if(tweetMore){			
			jQuery(".tweetError").text(tweetMore);
			/*console.log('---'+tweetMore);*/
		}else if(tweetError){			
			/*console.log('---'+tweetError);*/
		}else{
			jQuery('.clickavist_light').hide();
			jQuery("body").css("overflow", "auto");
			jQuery("body").flashMessage({
			  status: "ok",
			  message: "Tweet Submitted!"
			});
				
			jQuery(".tweetError").text("");
			jQuery('.tweetCharNum').text(prepost_length);
			jQuery( 'form#'+formid)[0].reset();
		}

	});
}

function countChar(val) {	
	var len = val.value.length;	
	var prepost_length = jQuery('.prepost_length').attr("data-prepostLength");		
	if (len > prepost_length) {
		val.value = val.value.substring(0, prepost_length);
	} else {
		jQuery('.tweetCharNum').text(prepost_length - len);
	}
};

jQuery(document).ready( function($) {
	$(document).on("click",".clvSendEmail",function() {
		var formid = jQuery('.clickavist_formbtn').closest('form').attr('id');

		var dataTemplateID = $(".emailTemp").attr("data-templateID");
		var dataidcontactlist = $(".emailTemp").attr("data-idcontactlist");
		var ajaxurl = clv_clickavist_script.ajax_url;
	
		var clv_email_to = jQuery('.clv_email_to').text();
		var clv_email_cc = jQuery('.clv_email_cc').val();
		var clv_email_subpretext = jQuery('.clv_email_subpretext').text();
		var clv_email_subject = jQuery('.clv_email_subject').val();
		var clv_email_subposttext = jQuery('.clv_email_subposttext').text();
		var clv_email_bodypretext = jQuery('.clv_email_bodypretext').text();
		var clv_email_body = jQuery('.clv_email_body').val();
		var clv_email_bodyposttext = jQuery('.clv_email_bodyposttext').text();

		var formdata = jQuery( 'form#'+formid).serialize();

		jQuery.post(ajaxurl, {
			action: 'clickavist_sendEmail',
			method: 'POST',
			to:clv_email_to,
			dataTemplateID: dataTemplateID,
			dataidcontactlist:dataidcontactlist,
			subpretext:clv_email_subpretext,
			subposttext:clv_email_subposttext,
			bodypretext:clv_email_bodypretext,
			bodyposttext:clv_email_bodyposttext,
			data: formdata,
		}, function (response) {
		
			var data = JSON.parse(response);
			var subjectEmpty = data['subject_empty']; 
			var bodyEmpty= data['body_empty']; 
			if(subjectEmpty){ 
				$(".emailSubError").text(subjectEmpty);
			}else if(bodyEmpty){							
				$(".emailBodyError").text(bodyEmpty);
			}else{
				
				$('.clickavist_light').hide();
				$("body").css("overflow", "auto");
				$("body").flashMessage({
				  status: "ok",
				  message: "Email Submitted!"
				});
				
				$(".emailSubError").text("");
				$(".emailBodyError").text("");				
				$( 'form#'+formid).css("display", "none");
			}

		});

	});

});

(function($) {
    var _options = {};
    var showFlash = function($el) {
        var deferred = new $.Deferred(),
            $flash;

        $el.append('<div class="j-flash-message j-flash-message_type_' + _options.status + '"><div class="j-flash-message__in"></div>' + _options.message + '</div>');
        $flash = $('.j-flash-message');
        if (_options.styles) {
            $flash.css(_options.styles);
        }
        $flash.animate({right: 20}, 500, function() {
            (function(flash) {
                window.setTimeout(function() {
                    flash.animate({ right: -310 }, 1000, function() {
                        flash.remove();
                        deferred.resolve();
                    });
                }, 2500);
            })($flash);
        });
        return deferred;
    };

    $.fn.flashMessage = function(options) {
        _options = $.extend({
            message: '',
            status: 'ok',
            styles: {}
        }, options);

        $(this).each(function() {
            showFlash($(this));
        });
        return this;
    };
}(jQuery));
