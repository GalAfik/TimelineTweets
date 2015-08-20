$(function(){
	$.ajax({
		url: 'scripts/get_tweets.php',
		type: 'GET',
		data: {
			// Forward query paramaters to the PHP script.
			user_id : $.url('?user_id'),
			count : $.url('?count') || 5
		},
		success: function(response) {
			console.log(response);

			if (typeof response.errors === 'undefined' || response.errors.length < 1) {
				var $tweets = $('<div>').addClass('tweet-list');
				
				$.each(response, function(idx, item) {
					$tweets.append(formatTweet(this));
				});

				$('.tweets-container').html($tweets);
			} else {
				$('.tweets-container p:first').text('Response error');
			}
		},
		error: function(errors) {
			$('.tweets-container p:first').text('Request error');
		}
	});
	
	function parseDate(dateStr) {
		return moment(dateStr, 'ddd MMM DD HH:mm:ss ZZ YYYY');
	}
	
	function formatDate(date) {
		return date.format('dddd, Do MMMM YYYY');
	}
	
	function formatTime(date) {
		return date.format('hh:mm:ss a Z');
	}
	
	function processText(rawText) {
		return Autolinker.link(rawText);
	}
	
	function formatTweet(tweetObj) {
		var date = parseDate(tweetObj.created_at);
				
		return $('<div>')
			.addClass('tweet-item')
			.append($('<div>')
				.addClass('tweet-meta')
				.append($('<div>', {
					class : 'tweet-date',
					html : formatDate(date)
				}))
				.append($('<div>', {
					class : 'tweet-time',
					html : formatTime(date)
				})))
			.append($('<div>')
				.addClass('tweet-content')
				.append($('<div>', {
					class : 'tweet-text',
					html : processText(tweetObj.text)
				})));
	}
});
