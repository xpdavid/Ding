## Porject Ding
#### NUS Orbital 2016

### Project Development Instruction

1. Please give comment to your code. (for css file, just make it clear)
2. For database column, please use "_" to separate naming name. (e.g. can_reply)
3. For php, javascript method, please use Camel-Case naming.
4. There is no need to write comment for every line. Please follow the JAVA comment.

``` Migration
/**
 * Run the migrations.
 *
 * @return void
 */  
```

Javacript
``` javascript
/**
* Class: MyObject
* Description: a object that cound be 'draw' in the backgroup, technically, we append it to the parent div
*/
```

PHP：
``` php
/**
 * Remove a user from a conversation.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
```

### Project dependency
Please follow the asset management style. Basically, using the gulpfile to control the asset.

1. `JQuery 1.12.3` (Javascript enhancement) [Index](http://jquery.com)
2. `Bootstrap` (Grid-system) [Index](http://getbootstrap.com)
3. `Boostrap3-typeahead` (UI for select tag in form) [Github](https://github.com/bassjobsen/Bootstrap-3-Typeahead)
4. `sweetalert` (Alternative for javascript 'alert()') [Github](http://t4t5.github.io/sweetalert/)
5. form validator [Github](https://github.com/1000hz/bootstrap-validator)
6. `select2`  (tag selector) [Github](https://github.com/select2/select2/releases)
7. `handlebars` (javascript template engine) [Index](http://handlebarsjs.com)
8. `intervention\mage` (laravel image system) [Index](http://image.intervention.io)
9. `cropper.js` (crop image jquery plugin) [Github](https://github.com/fengyuanchen/cropper/blob/master/README.md)
10. `tinyMCE` (wysiwyg html editor) [Index](https://www.tinymce.com/)
11. `MathJax` (Latex formula render) [Index] (https://www.mathjax.org/)
13. `jsdiff` (JS text difference comparator) [Github] (https://github.com/kpdecker/jsdiff)
14. `yangqi/htmldom` (PHP HTML DOM) [Github] (https://github.com/yangqi/Htmldom)
15. `guzzlehttp/guzzle` (PHP request plugin) [Github] (https://github.com/guzzle/guzzle)
16. `mews/purifier` (HTML Element Purifier (to prevent XSS attach)) [Github] (https://github.com/mewebstudio/Purifier)
17. `greggilbert/recaptcha` (Google reCAPTCHA implementation) [Github](https://github.com/greggilbert/recaptcha)

The javascript plugin is managed using `gulpfile`, the `laravel` plugin is managed using composer.
Please run `composer update` to get the plugins when deploying.

### There are several helper class you can use in the main.css file. There are also container some fixes for external package.

#### Fixes
- `// fix bootstrap modal shifting`
- `// fix bootstarp modal scrolling`
- `// fix for tinyMCE fullscreen modal`
- `// fix for sweet alert`

#### Classes
- `<body>` :  the overall color is set to '#666', the font is set to `helvetica` the fond size is set to `15px`
- `<html>: overflow-y: scroll !important` : to prevent scrollbar from repositioning web page, this element is defined `padding-top: 60px` because the menu bar is fixed at top.
- `.glyphicon` : this class is from `bootstrap` css and we override it as `margin-right:5px` because we always use put an icon in front of text.
- `.noneDisplay ` : this class is to set display to `none`, pretty useful together with jquery toggle function
- `.clearfix` : this class is to expend parent element when you have floating `div`
- `.clear_margin` : clear all margin of an element using `!important`
- `.font-bold` : to bold a element content
- `.noborder` : to clear all border of a elemnt. This is useful when you have item level and level and define `.border-bottom: 1px solid #666` and you want the last level show no border
- `.topborder` : to show top border color `#eee`
- `.hide-text` : to hide content in an element
- `.float-left` : to set an element float left
- `.float-right` : to set an element float right
- `.font-normal` : to set normal set of font `14px`
- `.samll_hr` : the original bootstrap `hr` is margin to much, this one override it to `7px margin-top and margin down`
- `.small_hrLight` : same as above but with light color `<hr>`
- `.font-black` : `set black` to the `font-color`
- `.font-grey` : `set greep` to the `font-color`
- `.sitesCopyright` : copyright information (refer to the sample in `user_home.html`)
- `.sideBar_section` : side bar implementation (refer to the sample in `notification.html`)
- `.sideBar_sectionItem` : side bar item implementation (refer to the sample in `highligths.html`) 
- `.questions_questionLayout` : show question div (refer to the sample in `question_homepage.html`)
- `.horizontal_item` : show a horizontal_item (refer to the sample in `question_homepage.html`)
- `.userProfile_card` : show user profile card (small) (refer to the sample in `question.answer.blade.php`)
- `.space-right` : set the right margin of an element to `4px`, for example you may want to use it to replace `&nbsp;`
- `.space-right-big` : same as above with `margin-right:10px` 
- `.link_normal` : all `<a>` under this class will display as grey colour. When hover, it will turn to blue.
- `.margin-top` : set `margin-top` to `8px`;
- `.marginAuto` : useful for centering element.
- `.a_tagBG` : set background of `<a>` to `blue` when hover
- `.avatar-img` : set `43px * 43px` image;
- `.highlight` : to hightlight an HTML element (set background to `yellow`)

##### Please update this when you have other helper css class added or fixes impelmented


## Dynamics Image System Manual
The `images` table has 5 columns `id` `reference_id` `path` `width` `height`.

- To save an image.
	- move the image to the folder `images/` (not `public` folder). Create a image instance (Image::create()) and save. Please set the `reference_id` equal to `id` so that we could distinct it as original image.
	- Under the images folder, we have `topic` folder and `user` folder. `topic` folder will store all the avatar images of topics and `user` folder will store all the users' uploaded images. In `user` folder, each user will have their own folder `{their_id}`.
- To get an image.
	- using this `url` (put it in the `src` field) : `/image/{reference_id}/{width}/{height}`, the system will automatically detect if the original image fit to this resolution. If not, it will create a new one by using `Intervention\Image`. You don't have to worry about the new one. Next time, using same `url`, the system will automatically find the one that fit the provided resolution.
	- There are helper function in laravel help you generate this url. (Please run `composer dump-autoload`, the helper function is in file `app/Support/Dimage.php`) The function `Dimage($reference_id, $width, $height)`, which have global scope will, return the url.


## Final Milestone

### Project Name: Ding

#### Brief Description

Project Ding is a forum-based web application that allows discussion of tutorial questions and past year papers under specific modules.

 

#### Full Features

* For general users whose main purpose of viewing this website is gain more valid and useful information:

	* Viewing of topics, questions, answers and comments on the forum is implemented
		* A database model is built where:
			* a topic may have many questions
			* a topic may belong to a parent topic and may have many subtopics
			* a question may have many answers and comments
			* an answer may have many comments
	* Searching of topics and questions by keywords is implemented
		* Typeahead is implemented with AJAX so that relevant results will show up while user is still typing keywords.
		* Typeahead reduces time for processing user requests and gives users better experiences
* For registered users who would like to have more discussions and interaction our site:
	* User authentication is implemented. 
		* Proper information validation is implemented upon registration:
			* Correct format and uniqueness of email address
			* Minimum length requirement for passwords
			* Correct matching of input password and confirmation password
			* (the validation is done using AJAX to avoid unnecessary refreshes to pages)
		* Password are encrypted using bcrypt hashing function before being stored in database.
		* reCAPTCHA is used to ensure that registration is easy for human and difficult for robots
		* Login function is properly tested.
		* Authentication of user is performed on every private page.
		* Logging in with IVLE is implemented to bring NUS students more convenience
	* Postings of questions, answers and comments are implemented
		* Before asking a question, a search is automatically done based on keywords input to ensure that no similar questions have been asked in the past. User will be prompt to enter their own question if their question is not present in Ding. 
		* Designated edit area is implemented to allow users to input content of different type
			* Plain text
			* Equations using Tex formulae
			* Images
			* Short videos
		* Users can modify questions posted when necessary
		* Users are able to view question edit history to know more about the context of the question from past information.
		* Users can post their answers under each question using the same edit area mentioned above.
		* Users can modify answers when necessary.
		* Edit history of each answer can be seen under answer status so that people can have better idea regarding how the answer is come out
		* Users are able to reply to each other's answers and comments, and form a meaningful conversation regarding the question.
	* Votings for answers and comments are implemented
		* In consideration to the fact that people might have similar opinion towards a question, votings are implemented to show agreement / disagreement towards an answer or a comment. This would help other users to see the opinion of the mass.
		* Each user can only cast one vote for each answer / comment.
	* Private messaging between users is implemented
		* private conversation is enabled in the view of encouraging more focused discussion as well as creating bonds between users.
* For registered users who would like to have more personalized information from our site:

	* A personalized user panel is implemented
		* Basic information of the user e.g. education experience and specializations are displayed so that users can each other's profile to find people of similar interests.
		* A summary of user activity e.g. number of question answered and number of votes received are displayed as a reference to a user's ability and activity.
		* A recent history of user activity is displayed as well for the ease of re-accessing recently viewed information.
	* Suggesting of relevant questions based on what user is viewing and hot questions under the same topic is implemented
		* These information is displayed in the sidebar to ensure that they are in the view but not protrusive.
	*  Subscription of topics, questions, answers and users is implemented.
		* The subscription system provides great flexibility of the information user wants to obtain.
		* A notification system is implemented so that user can receive notification when there is update to information subscribed
		* An emailing system is implemented as well to allow users to receive various updates even when they are not on Ding.
		* In the settings page, user may choose the categories of notification/emails he/she wants to receive among all.
* For registered users who would like to find out more interesting topics and discussions:
	* A topics page is implemented to show all existing topics
		* Under each topic tab, a summary of the topic and some popular questions will be displayed.
	*  Highlights of popular topics and questions are implemented.
		* Topics and questions are ranked based on user activity.
Rankings in two time span are enabled: by week and by month.
* For site owner who would like more users to join our site and be active:
	* Sharing of question on social media
Sharing of question is enabled on Facebook, Twitter, Instagram, Pinterest and via email.
	* Inviting of users to answer certain questions
		* An autocomplete search function is done for users to select other users with ease.
		* Users who are invited to answer certain question will receive a notification.
	* A point system based on user contribution 
		* A user group table is set up. Users under each user group has different privileges.
		* A set of user actions such as posting answers and voting are credited different points.
* For site owner who would like the site to be safe and the information is useful:
	* Editing and deletion of invalid / indecent answers and comments and reduction of contribution points by administrators
		* A set of user actions such as answer deleted and question deleted are assigned different point deduction.
	* Patching up of any potential security loopholes
		* Php data object (PDO) parameter binding is used to prevent SQL injection. With PDO parameter binding, user input is binded into one value instead of a few fields of selection criteria.

			Consider for instance a form field used to supply an email address which might be used for searching a user table.

			`SELECT * FROM users WHERE email = ___________`

			But instead of supplying an email address the user searches for 'ding@example.com' or 1=1. The corresponding SQL query is thus

			`SELECT * FROM users WHERE email = ‘ding@example.com' or 1=1`

			As 1=1 is always true, the query will return the whole user table, which would lead to data leakage.
With PDO parameter binding, ‘ding@example.com’ or 1=1 will be binded into ‘ding@example.com or 1=1’.

			`SELECT * FROM users WHERE email = ‘ding@example.com or 1=1’`

			As there is no email address ‘ding@example.com or 1=1’, the query will safely return no results.

		* To prevent any third-party from initiating malicious requests that affect users e.g. changing the personal information of a user, a Cross-Site Request Forgery (CSRF) token is passed along whenever a form is submitted.
			This token is compared with a value additionally saved to the user session. If it matches, the request is deemed valid, otherwise it is deemed invalid.
			
		* To avoid malicious users from entering scripts in form field (Cross-Site Scripting), a refined list of HTML entities are chosen and only these selected HTML entities are parsed.
	 
			`'HTML.Allowed' => 'iframe[width|height|src],img[src|alt|width|height],pre[class],code,p,strong,em,span,blockquote,li,ul[style],ol[style],a[href]'`
			
			Consider the following script
			
			`<script>alert(“malicious script”)</script>`
			
			If such field is entered and parsed as an HTML entity, the pop-up window with information “malicious script” will be shown.
			With the <script> entity escaped, the code will be removed.
			Which will not be parsed as a script any more.
* In order to guide users on how to use Ding, a blog that includes the documentation of Ding is set up [Blog](http://blog.nusding.info/). 
* User testing is deployed to improve quality of Ding.
	* Alpha testing is used to detect and clear bugs
		* Correct crediting of contribution points
		* Correct sending of notification / emails according to user settings
		* Correct sequence of topics / question based on sorting criteria
	* Beta testing is used to improve user experience
		* As some questions / answers are too long when unfolded, users need to scroll down the page in order to collapse the entry. Hence, a button that is positioned absolutely on the page is added so that users can collapse any long entry without scrolling.
		* As the vote button is small and can be hard to click, vote up button is enlarged by including the vote number into the button as well.
 

#### Level of Achievement

With the number of details and hence the complexity of our project, our aimed level of achievement is Apollo 11.

 

#### Thank you for being with us along this memorable and fruitful journey.

