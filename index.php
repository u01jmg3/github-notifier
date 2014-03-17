<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="Description" content="" />
        <meta name="Keywords" content="" />
        <meta name="Author" content="Jonathan Goode" />
		<meta name="copyright" content="&copy; Jonathan Goode, <?php echo date('Y'); ?>" />
        <title>GitHub Repos</title>
		<link rel="stylesheet" type="text/css" media="screen, handheld, print" href="style/screen.css" />
		<style>
			td:not(.disabled){ color: red }
		</style>
		<script type="text/javascript" charset="utf-8" src="scripts/jquery-1.11.0.js"></script>
		<script type="text/javascript" charset="utf-8" src="scripts/custom.js"></script>
		<script type="text/javascript" charset="utf-8" src="scripts/php.js"></script>
		<script type="text/javascript" charset="utf-8" src="scripts/jquery.cookie-1.4.0.js"></script>
		<script type="text/javascript">
			//---------------------------------------------
			// Customise here
			//---------------------------------------------
			var clientId = '[change me]';
			var clientSecret = '[change me]';
			var token = '[change me]';
			var githubUsername = 'u01jmg3';
			var notifyOnDesktop = 0; //change to 1 to enable
			//---------------------------------------------

			Object.size = function(obj){
				var size = 0, key;
				for(key in obj){
					if(obj.hasOwnProperty(key))
						size++;
				}
				return size;
			};

			$.fn.sort = function(){
				return this.pushStack([].sort.apply( this, arguments ), []);
			};

			$.fn.center = function(element){
				return this.each(function(i){
					element = $(element);
					var aw = element.width();
					var mw = Math.ceil((aw) / 2);

					var ah = element.height();
					var mh = Math.ceil((ah) / 2);

					if(mw < 0)
						mw = 0;

					if(mh < 0)
						mh = 0;

					$(this).css('margin-left', mw * -1);
					$(this).css('margin-top', mh * -1);
				});
			};

			//---------------------------------------------

			function getParameterByName(name, url){
				name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
				var regexS = "[\\?&]" + name + "=([^&#]*)";
				var regex = new RegExp(regexS);
				var results = regex.exec(url);
				if(results == null)
					return "";
				else
					return decodeURIComponent(results[1].replace(/\+/g, " "));
			}

			function sortByName(a, b){
				return a.name.localeCompare(b.name);
			}

			function sortByVersion(a, b){
				return formatIntoVersion(a.name, true) < formatIntoVersion(b.name, true) ? 1 : -1;
			}

			function pad(string, max){
				var array = string.split('.');

				for(var i = 0; i < array.length; i++){
					array[i] = str_pad(array[i], max, '0', 'STR_PAD_LEFT');
				}

				return array.join('.');
			}

			function formatIntoVersion(string, doPad){
				if(doPad)
					return pad(string.replace(/(m|rc|a|b){1,1}/g, '.').replace(/[^\d|\.]/g, '').replace(/\.+$/, ''), 5);

				return string.replace(/[^\d|\.|b|m]/g, '').replace(/\.+$/, '');
			}

			function evalVersion(version1, version2){
				version1 = version1.split('.');
				version2 = version2.split('.');

				var output = [];

				var version1ArrayLength = version1.length;
				var version2ArrayLength = version2.length;
				var maxArrayLength = Math.max(version1ArrayLength, version2ArrayLength);

				version1 = array_pad(version1, maxArrayLength, '0');
				version2 = array_pad(version2, maxArrayLength, '0');

				for(var i = 0; i < version1.length; i++){
					var version1Length = version1[i].length;
					var version2Length = version2[i].length;
					var maxLength = Math.max(version1Length, version2Length);

					if(version1Length != version2Length){
						version1[i] = str_pad(version1[i], maxLength, '0', 'STR_PAD_LEFT');
						version2[i] = str_pad(version2[i], maxLength, '0', 'STR_PAD_LEFT');
					}

					if(version1[i] - version2[i] > -1)
						output[i] = version1[i] - version2[i];
					else
						break;
				}

				return output.join('.').replace(/[\.]/, '*').replace(/[\.]/g, '').replace(/[\*]/g, '.');
			}

			function evalTimestamp(timestamp1, timestamp2){
				var date1 = new Date(timestamp1);
				var date2 = new Date(timestamp2);
				var timeDiff = Math.abs(date2.getTime() - date1.getTime());
				var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
				return diffDays;
			}

			function getTotalStarredRepos(response){
				var meta = response.meta;
				numberOfRepos = getParameterByName('page', meta.Link[1][0]);
			}

			//---------------------------------------------

			var configQueryString = '?client_id=' + clientId + '&client_secret=' + clientSecret + '&token=' + token;
			var configQueryStringSuffix = '&client_id=' + clientId + '&client_secret=' + clientSecret + '&token=' + token;

			var script = document.createElement('script');
			script.src = 'https://api.github.com/users/' + githubUsername + '/starred?per_page=1&callback=getTotalStarredRepos' + configQueryStringSuffix;
			document.getElementsByTagName('head')[0].appendChild(script);

			var numberOfRepos = 0;
			var foundUpdate, foundUnseenUpdate = false;
			var cookies = $.cookie();
			var numberOfCookies = Object.size(cookies);
			var perPage = 100;
			var seenRepos = [];
			var originalTitle = document.title;

			//---------------------------------------------

			$(document).ready(function(){
				document.ondblclick = function(event){
					if (window.getSelection && $.trim(window.getSelection()) == '')
						window.getSelection().removeAllRanges();
				}

				$('img.loader').center('.vcenter');
				$.removeCookie('PHPSESSID', { path: '/' });
			});

			//---------------------------------------------

			$(window).load(function(){
				var pages = Math.ceil(numberOfRepos / perPage);

				for(var p = 1; p <= pages; p++){
					$.getJSON('https://api.github.com/users/' + githubUsername + '/starred?page=' + p + '&per_page=' + perPage + configQueryStringSuffix, function(data1){
						if(data1[0] !== undefined){
							data1 = $(data1).sort(sortByName);

							var savedRepos = $.cookie('SAVED_REPOS') !== undefined ? $.cookie('SAVED_REPOS').split('|') : [];
							document.title = originalTitle + ' (' + numberOfRepos + ')';

							$.each(data1, function(i, item){
								var value = data1[i].full_name;

								$.getJSON('https://api.github.com/repos/' + value + '/tags' + configQueryString, function(data2){
									if(data2[0] !== undefined){
										var version = $(data2).sort(sortByVersion);
										version = formatIntoVersion(version[0].name, false);
										var repoName = value.replace(/.*\//, '');

										if($.cookie(repoName) === undefined)
											$.cookie(repoName, version, { expires: 365, path: '/' });
										else if($.cookie(repoName) != version){
											$.ajax({
												url: 'notify.php',
												type: 'POST',
												data: {filename: repoName + '-' + version, save_file: notifyOnDesktop}
											}).done(function(data){
												if(data == 1){
													var versionDifference = evalVersion(version, $.cookie(repoName));
													var barColor = versionDifference > 0.2 ? 'red' : 'orange';
													var fontClass = savedRepos.length > 0 && in_array(repoName + '-' + version, savedRepos) ? 'disabled' : '';

													$('table.format').append($('<tr/>', {'class': 'repos', html:
													'<td><a target="_blank" href="https://github.com/' + value + '/tags/" title="' + repoName + '">' + repoName + '</a>&nbsp;<input class="checkbox" type="checkbox"></td>'
													+ '<td class="' + fontClass + '">' + $.cookie(repoName) + '</td>'
													+ '<td class="' + fontClass + '">' + '<strong>' + version + '</strong></td>'
													+ '<td><div class="bar" title="' + versionDifference + '"><div class="' + barColor + ' full sub_bar"></div></div></td>'}));
												}
											});

											if(getParameterByName('reset', window.location.search))
												$.cookie(repoName, version, { expires: 365, path: '/' });
											else {
												foundUpdate = !foundUpdate ? true : foundUpdate;
												foundUnseenUpdate = !foundUnseenUpdate && !in_array(repoName + '-' + version, savedRepos) ? true : foundUnseenUpdate;
											}

											seenRepos.push(repoName + '-' + version);
										}
									} else {
										$.getJSON('https://api.github.com/repos/' + value + '/commits' + configQueryString, function(data3){
											if(data3[0] !== undefined){
												var timestamp = data3[0].commit.author.date;
												var repoName = value.replace(/.*\//, '');

												if($.cookie(repoName) === undefined)
													$.cookie(repoName, timestamp, { expires: 365, path: '/' });
												else if($.cookie(repoName) != timestamp){
													$.ajax({
														url: 'notify.php',
														type: 'POST',
														data: {filename: repoName + '-' + timestamp, save_file: notifyOnDesktop}
													}).done(function(data){
														if(data == 1){
															var timestampDifference = evalTimestamp(timestamp, $.cookie(repoName));
															var barColor = timestampDifference > 60 ? 'red' : 'orange';
															var fontClass = savedRepos.length > 0 && in_array(repoName + '-' + timestamp, savedRepos) ? 'disabled' : '';

															$('table.format').append($('<tr/>', {'class': 'repos', html:
															'<td><a target="_blank" href="https://github.com/' + value + '/commits/" title="' + repoName + '">' + repoName + '</a>&nbsp;<input class="checkbox" type="checkbox"></td>'
															+ '<td class="' + fontClass + '">' + $.cookie(repoName) + '</td>'
															+ '<td class="' + fontClass + '">' + '<strong>' + timestamp + '</strong></td>'
															+ '<td><div class="bar" title="' + timestampDifference + ' days"><div class="' + barColor + ' full sub_bar"></div></div></td>'}));
														}
													});

													if(getParameterByName('reset', window.location.search))
														$.cookie(repoName, timestamp, { expires: 365, path: '/' });
													else {
														foundUpdate = !foundUpdate ? true : foundUpdate;
														foundUnseenUpdate = !foundUnseenUpdate && !in_array(repoName + '-' + timestamp, savedRepos) ? true : foundUnseenUpdate;
													}

													seenRepos.push(repoName + '-' + timestamp);
												}
											}
										});
									}

									if(i == (data1.length - 1)){
										delay(function(){
											if(getParameterByName('reset', window.location.search))
												$.removeCookie('SAVED_REPOS', { path: '/' });

											if((!foundUpdate) || (getParameterByName('scheduler', window.location.search) && !foundUnseenUpdate)){
												window.open('', '_self', '');
												window.close();
											} else if(foundUpdate){
												$('table.format').removeClass('hidden');
												$('img.loader').addClass('hidden');

												$.cookie('SAVED_REPOS', seenRepos.join('|'), { expires: 365, path: '/' });
											}

											$('td').on('dblclick', function(event){
												$(this).find('.checkbox').prop('checked', true);

												$('.checkbox:checked').each(function(){
													$.removeCookie(($(this).prev('a').text()), { path: '/' });
												});

												window.location.href = window.location.href; //refresh
											});

											if(savedRepos.length > 0 && (numberOfCookies - 1) > numberOfRepos)
												console.log('Redundant cookie(s) found (' + (numberOfCookies - 1) + ' vs. ' + numberOfRepos + ')');
											else if(savedRepos.length == 0 && numberOfCookies > numberOfRepos)
												console.log('Redundant cookie(s) found (' + numberOfCookies + ' vs. ' + numberOfRepos + ')');
										}, 750);
									}

								});
							});
						}
					});
				}
			});
		</script>
    </head>
    <body>
		<table class="format hidden">
			<tbody></tbody>
		</table>
		<img class="loader vcenter pulsating" width="128" height="128" src="github-logo.png" alt="" title="" />
	</body>
</html>