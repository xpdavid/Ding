## Porject Ding
#### NUS Orbital 2016

### Project Development Instruction

1. Please give every code appropriate comment. (for css file, just make it clear)
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

1. JQuery 1.12.3 (Javascript enhancement) [Index](http://jquery.com)
2. Bootstrap (Grid-system) [Index](http://getbootstrap.com)
3. Boostrap3-typeahead (UI for select tag in form) [Github](https://github.com/bassjobsen/Bootstrap-3-Typeahead)
4. sweetalert (Alternative for javascript 'alert()') [Github](http://t4t5.github.io/sweetalert/)
5. form validator [Github](https://github.com/1000hz/bootstrap-validator)
6. select2 (tag selector) [Github](https://github.com/select2/select2/releases)
7. handlebars (javascript template engine) [Index](http://handlebarsjs.com)
8. intervention\mage (laravel image system) [Index](http://image.intervention.io) (Please run `composer update` to get this plugin)
9. `cropper.js` (crop image jquery plugin) [Github](https://github.com/fengyuanchen/cropper/blob/master/README.md)
10. `tinyMCE` (wysiwyg html editor) [Index](https://www.tinymce.com/)
11. `MathJax` (Latex formula render) [Index] (https://www.mathjax.org/)
12. `PHP Html Parser` (PHP HTML Dom parse) [Github] (https://github.com/paquettg/php-html-parser)
13. `jsdiff` (JS text difference comparator) [Github] (https://github.com/kpdecker/jsdiff)


### There are several helper function you can use in the main.css file

- `<body>` :  the overall color is set to '#666', the fond size is set to `15px`
- `html: width: 100%; width: 100vw;` : to prevent scrollbar from repositioning web page, this element is defined `padding-top: 60px` because the menu bar is fixed at top.
- `body: font` : set font to `google font api font: 'Open Sans', sans-serif`
- `.glyphicon` : this class is from `bootstrap` css and we override it as `margin-right:5px` because we always use put a icon in front of text.
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

##### Please update this when you have other helper css class added


## Dynamics Image System Manual
The `images` table has 5 columns `id` `reference_id` `path` `width` `height`.

- To save an image.
	- move the image to the folder `images/` (not `public` folder). Create a image instance (Image::create()) and save. Please set the `reference_id` equal to `id` so that we could distinct it as original image.
	- Under the images folder, we have `topic` folder and `user` folder. `topic` folder will store all the avatar images of topics and `user` folder will store all the users' uploaded images. In `user` folder, each user will have their own folder `{their_id}`.
- To get an image.
	- using this `url` (put it in the `src` field) : `/image/{reference_id}/{width}/{height}`, the system will automatically detect if the original image fit to this resolution. If not, it will create a new one by using `Intervention\Image`. You don't have to worry about the new one. Next time, using same `url`, the system will automatically find the one that fit the provided resolution.
	- There are helper function in laravel help you generate this url. (Please run `composer dump-autoload`, the helper function is in file `app/Support/Dimage.php`) The function `Dimage($reference_id, $width, $height)`, which have global scope will, return the url.


## Milestone 1

### Project Name: Ding


### Brief Description (What it solves)

Project Ding is a forum-based web application that allows discussion of tutorial questions and past year papers under specific modules. 

 

### Our Motivation (The importance of the project)

Below are a few of our observations:

Every semester after final examinations, students often have the thought that they are now rid of the modules taken without acknowledging that these modules are important foundations for their future learning.

In the time of interviews / internships where consolidation of knowledge learned is necessary, relevant learning materials are likely to be unavaliable since IVLE is only open to students taking specific modules for the time span of one semester.

Although students have been doing past year papers over and over each year, there are no sustained platforms for discussion of questions and possible answers, causing their thoughts and intellectual products to be shared and passed on only within a small group or even gone with the wind!
As such, we strongly feel that current discussion platforms such as IVLE forum are inadequate to encourage sustained and effective learning. There is definitely the need for a more long-lasting platform where students are given the impetus to participate in discussion, review their learning outcome, consolidate their knowledge and learn more along the way.

 

### Scope of the Project

Our project is mainly for current and recently graduated NUS students.

Potentially, we could open up the portal to future NUS students to let them know more about the majors they want to take.

With proper permission, it is even possible to open the portal to the public. This is not easily achievable in the short term, but knowledge is for sharing isn't it? :P

 

### User Stories and Features*

1. As a registered student who is going to take certain modules, I would like to know more about the scope and expectation of these modules.

    Features to be included:

 * Categorization of users based on their majors.

 * Searching of modules and questions based on keywords.

 * Viewing of questions, answers and comments by other users.

 

2. As a registered student who is taking certain modules currently, I would like to stay tuned in and participate any discussions pertaining to these modules.

    Features to be included (in addition to features mentioned above):

 * Posting of answers and comments

 * Subscribing of modules and questions and receiving notification when there are new updates.

 * Suggesting of relevant questions based on what questions the user is currently viewing

 

3. As a registered student who has already taken certain modules, I would like to help students who are taking these modules in the future by offering my own and perhaps my cohort's insights into the modules.

    Features to be included (in addition to features mentioned above):

 * Invitation to friends who are able to answer certain questions and receiving of invitations.

 * Giving positive / negative votes to answers posted by others to reflect quality of answers.

 * Sharing of questions / answers on social media

 

4. As a normal registered user in general, I would like to view information and updates relevant to me conveniently and make connections with other users.

    Features to be included (in addition to features mentioned above):

 * Personalized user panels where user information, new status to modules and questions subscribed, new comments to answers posted are consolidated.

 * Consolidation and highlighting of questions based on popularity.

 * Private messaging between users.

 

5. As a site owner / moderator, I would like the platform to be safe, clean and active.

    Features to be included (in addition to features mentioned above):

 * Reporting of invalid / indecent answers by normal users and deletion of such answers by moderators

 * A level system of users' viewing permission based on users' contributions:
 		* Enable users to set viewing permission to answers posted by themselves (no higher than their own permissions).
		* Post valid answers to questions to receive credits.
		* Give more credits to the first answer to any questions to encourage user activity.
		* Give more credits to high quality answers (answers with more positive votes).
		* Severe deduction of credits to any invalid / indecent answers by moderators.
		* (above mentioned are inexhaustive)

 * Security features including encryption of data in transmission and storage, strict checking of user permission before running any functions.

 
We will try to implement Feature 1, 2, 3 and 4 in June and Feature 5 as well as other features suggested by other teams in July.

*For this section, we assume the audience to be future, current and recently graduated NUS students.

 

### Level of Achievement

As development of such a platform involving complicated relationships between models requires extensive testing and careful project management, our aimed level of achievement is `Apollo 11`.

