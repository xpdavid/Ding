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


### There are several helper function you can use in the main.css file

- `<body>` : this element is defined `padding-top: 60px` because the menu bar is fixed at top. In addition the overall color is set to '#666'
- `.glyphicon` : this class is from `bootstrap` css and we override it as `margin-right:5px` because we always use put a icon in front of text.
- `hide` : this class is to set display to `none`, pretty useful together with jquery toggle function
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
- `.space-right` : set the right margin of an element to `4px`, for example you may want to use it to replace `&nbsp;`
- `.space-right-big` : same as above with `margin-right:10px` 

##### Please update this when you have other helper css class added