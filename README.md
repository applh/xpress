# XPRESS

## WORDPRESS PLUGIN

* XPress is a powerful toolkit
* Create an api to allow your WordPress to be used as a backend

### API FEATURES

* Easy start project with basics setup done
  * pages creation
  * menus creation
  * options setup
    * home page
    * blog page
    * comments off
    * ...
* Send emails (by user api_key with expiration date)
* Store files
* Upgrade plugin directly from the github.com repository
* Update the plugin from a zip file
* ...
 
## APPLICATION STREAMING

* Just use the code needed
* XPress uses modern web technologies to boost code performance
  
### VUEJS ASYNC COMPONENTS

* VueJS can load components only when templates are using them
* VueJS relies on JS modules import to load components
* No NodeJS: VueJS components are direct JS modules import in the browser

### PHP CLASSES WITH AUTOLADER

* PHP classes are loaded only when needed
* PHP has the powerful feature of spl_autoload_register 
* to load classes only when needed

### DEVELOPER LOCAL HELPERS

* When developing locally, there's no mail server 
* Trying to send emails by PHP function mail() will fail
* To help developers, XPress provides an API mail server
* You can open your online WP site as an API mail server 
* and then send emails from your local machine by sending easy POST requests
  * from
  * to
  * subject
  * body

* API security is provided by POST parameters
  * c=user
  * m=email
  * api_key=MD5_KEY/EXPIRATION_TIME

* There's an easy form to create these User API keys
  * Choose the validity period (in days)
