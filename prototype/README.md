### There are several helper function you can use in the main.css file

- `<body>` : this element is defined by `padding-top: 60px` because the menu bar is fixed at top. In addition the overall color is set to '#666'
- `.glyphicon` : this class is from `bootstrap` css and we override it as `margin-right:5px` because we always use put a icon in front of text.
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
- `.mid_hrLight` : `hr` with margin to 12px and light color
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