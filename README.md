Configuration
===============

Customise [`index.php`](https://github.com/u01jmg3/github-notifier/blob/master/index.php#L22-26) for:
* GitHub Client ID
* GitHub Client Secret
* GitHub Token
* GitHub Username
* Notify to Desktop with Text File (default Off)

(To get these credentials you will need to go to [https://github.com/settings/applications](https://github.com/settings/applications) to register a new application)

Customise [`notifier.php`](https://github.com/u01jmg3/github-notifier/blob/master/notify.php#L2) for:
* Windows Desktop Path

GitHub Notifier
===============
* Once setup correctly, on the first run of `index.php` the script will traverse your starred libraries and save a cookie for each with the current version of the repository; the tab will then close.
	* If you visit `index.php` again and one of your starred libraries has been updated with a new release then the page will remain open and it will list in a table the libraries that have been updated allowing you to track new versions of repositories based on the current version you are using.
* Some repositories do not use tags so the script will then revert to using the timestamp of the latest commit.
* Double clicking on a row in the table for a repository will track the latest version of the repository and remove the entry from the table - do this when you have updated to the latest version of a repository and want to track this new version.
	* To do the above for multiple repositories first check the checkboxes of the repositories you would like to remove and then double click on the row for any of the rows that you have checked - this will remove the repository for the selected row and those which have their checkbox checked.

## License

[MIT](http://opensource.org/licenses/MIT) © [Jonathan Goode](http://jonathangoode.co.uk)