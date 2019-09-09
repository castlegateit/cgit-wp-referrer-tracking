# Castlegate IT WP Referrer Tracking #

This is a conditional referral plugin for WordPress. It provides a framework for setting, validating and reading referral cookies. 
It supports restriction based on referee and target page, and user-defined cookie lifetime. 

## Referral ##

The `Cgit\Referral` class is used to initialise the plugin. Each instance will individually read the configuration and access
set cookies, so you can selectively implement behaviour around rendering of output. The plugin watches for a query parameter,
`referredBy`.

### Configuration ###

Configure the plugin using the provided filters:

~~~ php

// Sets the third party websites allowed to be referrers.
function configReferrerSources($referrerSources) {
    $referrerSources = ['example.com'];
    return $referrerSources;
}

// Sets the landing page on which referrals are tracked.
function configReferrerPages($referrerPages) {
    $referrerPages = ['example-page'];
    return $referrerPages;
}

// Sets the length of time to store cookies. Defaults to 3 months.
function configReferrerPages($timeLimit) {
    $timeLimit = strtotime( '+3 months' );
    return $timeLimit;
}

add_action('init', function() {
    add_filter('cgit_referral_tracker_set_sources', 'configReferrerSources');
    add_filter('cgit_referral_tracker_set_pages', 'configReferrerPages');
    add_filter('cgit_referral_tracker_set_timeLimit', 'configReferrerPages');
});
~~~

### Methods ###

The plugin only provides two public methods:

`checkReferralDone()` looks for and, if valid, alters an existing referral cookie to note that it has now converted. 
Intended to be used for conditional behaviour, for example after a successful form submission.

`getSaneCookie()` returns the contents of the referral cookie, provided that it matches any validation conditions. Note that
cookies set by the plugin are sanitised via htmlspecialchars. 

## License

Copyright (c) 2019 Castlegate IT. All rights reserved.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License along with this program. If not, see <https://www.gnu.org/licenses/>.