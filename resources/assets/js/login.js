
/**
* Class: MyObject
* Description: a object that cound be 'draw' in the backgroup, technically, we append it to the parent div
*/
function MyObject(clientWidth, clientHeight) { // not collde with javascript Object
	this.x = Math.random() * clientWidth;
	this.y = Math.random() * clientHeight;
	this.id = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 5); // assign a random id for the object
	this.fontSize = Math.random() * 8 + 16;
	this.generateSpeed();
}

/*
* method: generateSpeed
* Description: different obejct will have different speed
*/
MyObject.prototype.generateSpeed = function() {
	this.speedX = (Math.random() - 0.5 < 0) ? 1 : -1; // speed could be negative (reverse direction)
	this.speedY = (Math.random() - 0.5 < 0) ? 1 : -1;
	this.speedX = this.speedX * Math.random();
	this.speedY = this.speedY * Math.random();
}


/*
* method: setDisplayText
* Description: detemine what text the object will show in the background
*/
MyObject.prototype.setDisplayText = function(text) {
	this.text = text; 
}

/*
* method: toDiv
* Description: change the object to div element in order to display it.
*/
MyObject.prototype.toDiv = function() {
	return "<div style='top:" + this.y + "px;left:" + this.x + "px;font-size:" + this.fontSize + "px;' id='" + this.id + "'>" + this.text + "<div>";
}


/**
* Class: Line
* Description: a line that connect the object
*/
function Line(objectID_1, objectID_2) {
	this.point1 = objectID_1;
	this.point2 = objectID_2;
	this.x = 0;
	this.y = 0;
	this.angle = 0;
	this.length = 0;
	this.id = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 5); // assign a random id for the line
}

/*
* method: calculatePoistion
* Description: determine the angle and width of the line between too object
*/
Line.prototype.calculatePoistion = function() {
	// assume using point1 as the orgin
	// get the position of the two object
	var point1_X = parseFloat($('#' + this.point1).css('left')) + $('#' + this.point1).width() / 2;
	var point1_Y = parseFloat($('#' + this.point1).css('top')) + $('#' + this.point1).height() / 2;
	var point2_X = parseFloat($('#' + this.point2).css('left')) + $('#' + this.point2).width() / 2;
	var point2_Y = parseFloat($('#' + this.point2).css('top')) + $('#' + this.point2).height() / 2;
	// the Pythagorean theorem
	this.length = Math.sqrt(Math.pow(point1_X - point2_X, 2) + Math.pow(point1_Y - point2_Y, 2));

	// the angle atan()
	this.angle = Math.asin((point2_Y - point1_Y) / this.length) * 180 / Math.PI;

	// samll bugs fix (Math fix)
	if (point1_Y < point2_Y && point1_X > point2_X) {
		this.angle = 180 - this.angle;
	}
	if (point1_Y > point2_Y && point1_X > point2_X) {
		this.angle = Math.abs(this.angle) + 180;
	}

	this.x = point1_X;
	this.y = point1_Y;
}

/*
* method: setRotateAndLength
* Description: Change the css of the line div in order to rotate it. (broswer compatibility)
*/
Line.prototype.setRotateAndLength = function() {
	$('#' + this.id).css({'-webkit-transform' : 'rotate('+ this.angle +'deg)',
                 '-moz-transform' : 'rotate('+ this.angle +'deg)',
                 '-ms-transform' : 'rotate('+ this.angle +'deg)',
                 'transform' : 'rotate('+ this.angle +'deg)',
                 '-o-transform' : 'rotate('+ this.angle +'deg)',
             	 'width' : this.length + 'px'});
}

/*
* method: toDiv
* Description: change the line to div element in order to display it.
*/
Line.prototype.toDiv = function() {
	return "<div style='top:" + this.y + "px;left:" + this.x + "px;' id='" + this.id + "'><div>";
}




/**
* Class: MyBackground
* Description: a object that control the drawing of object in background
*/
function MyBackground(drawDivText, drawDivLine) {
	this.areaText = drawDivText; // a JQuery object
	this.areaLine = drawDivLine; // a JQuery object
	this.MyObejcts = [];
	this.MyLines = [];
    var self = this;
    // local storage to get cache
    try {
        var storage = window.localStorage;
        if (storage.getItem('NUS-DING-HOT-TOPICS') == null) {
            $.post('/hot-topics', {
                max : 30
            }, function(results) {
                var process = [];
                $.each(results, function(index, item) {
                    process.push(item.name);
                });
                MyBackground.prototype.textSource = process;
                storage.setItem('NUS-DING-HOT-TOPICS', JSON.stringify(process));
                self.init();
            });
        } else {
            MyBackground.prototype.textSource = JSON.parse(storage.getItem('NUS-DING-HOT-TOPICS'));
            self.init();
        }
    } catch(e) {
        self.init();
    }


}

/*
* Attribute: textSource
* Description: several candidate text source
*/
MyBackground.prototype.textSource = [
	'(x + y)^2 = x^2 + 2xy + y^2',
	'sin(x)^2 + cos(x)^2 = 1',
	'Computing is the future',
	'Travelling Salesman Problem',
	'Newton\'s First Law',
	'Scientific Method',
	'Gay-Lussac\'s Law',
	'Microbiology',
	'Molecule',
	'Neutrino',
	'Antipodal Points',
	'Cofactor Matrix',
	'De Moivre’s Theorem',
	'Relative Maximum',
	'Attributive Adjectives',
	'Objects',
	'Accommodation',
	'inorganic compound',
	'IUPAC',
	'National University of Singapore',
	'Nanyang Technological University',
	'Singapore University of Technology and Design',
	'Research',
	'Innovation',
	'Creative',
	'Eureka',
	'Least Common Multiple',
	'Maclaurin Series',
	'Simple Closed Curve',
];

/*
* Method: init
* Description: generate obejct and line according to the textSource
*/
MyBackground.prototype.init = function() {
	for(var i = 0; i < this.textSource.length; i++) {
		var myObject = new MyObject(this.areaText.width(), this.areaText.height());
		myObject.setDisplayText(this.textSource[i]);
		this.MyObejcts.push(myObject);
	}

	

	for(var i = 0; i < this.textSource.length - 1; i++) {
		for(var j = i + 1; j < this.textSource.length; j++) {
			// randomly connect two terms
			if (Math.random() < 0.9) {
				continue;
			}
			var myLine = new Line(this.MyObejcts[i].id, this.MyObejcts[j].id);
			this.MyLines.push(myLine);
		}
	}

	this.drawObject();
	this.drawLine();
}

/*
* Method: drawObject
* Description: draw (append) the object in the background
*/
MyBackground.prototype.drawObject = function() {
	var outerThis = this;
	$.each(this.MyObejcts, function(index, object) {
		outerThis.areaText.append(object.toDiv());
	});
}

/*
* Method: drawLine
* Description: draw (append) the line in the background
*/
MyBackground.prototype.drawLine = function() {
	var outerThis = this;
	$.each(this.MyLines, function(index, line) {
		line.calculatePoistion();
		outerThis.areaLine.append(line.toDiv());
		line.setRotateAndLength();
	});
}

/*
* Method: update
* Description: update (redraw) the position of the object
*/
MyBackground.prototype.update = function() {
	$.each(this.MyObejcts, function(index, object) {
		$('#' + object.id).css({ 'left' : object.x ,
						'top'  : object.y});
	});

	$.each(this.MyLines, function(index, line) {
		line.calculatePoistion();
		$('#' + line.id).css({ 'left' : line.x ,
								'top'  : line.y});
		line.setRotateAndLength();
	});
}

/*
* Method: nextState
* Description: determine next position of each objects
*/
MyBackground.prototype.nextState = function() {
	var outerThis = this;
	$.each(this.MyObejcts, function(index, object) {
		// update each object position according to their speed
		object.x = object.x + object.speedX;
		object.y = object.y + object.speedY;
		// if one of them exceed the area, negate the speed
		if (object.x > outerThis.areaText.width() || object.x < 0) {
			object.speedX = -object.speedX;
		}
		if (object.y > outerThis.areaText.height() || object.y < 0) {
			object.speedY = -object.speedY;
		}
	});
}

/*
* Variable: myBG
* Description: create new MyBackground object
*/
var myBG = new MyBackground($('#login_bg_text'), $('#login_bg_line'));

/*
* function: draw
* Description: draw the object to the background and update the next state
*/
function draw() {
	myBG.nextState();
	myBG.update();
}

/*
* excute this function after load the page
*/
$(function() {

    setInterval(draw, 50);
});

/*
* function: moveSlideBar
* Description: make the slide bar slide in the login page
*/
function moveSlideBar(offset) {
	$('.login_nav_slidebar').css('left', offset + 'em');
}


/*
* function: userRegister()
* Description: validate form and submit
 */
function userRegister() {
    var flag = true;

    if ($('#register_name').val() == "") {
        $('#register_name').next().css('right', '8px');
        flag = false;
    }

    if ($('#register_email').val() == "") {
        $('#register_email').next().css('right', '8px');
        flag = false;
    }

    if ($('#register_password').val() == "") {
        $('#register_password').next().css('right', '8px');
        flag = false;
    }

    if ($('#register_password_confirmation').val() == "") {
        $('#register_password_confirmation').next().html('Please enter your password again');
        $('#register_password_confirmation').next().css('right', '8px');
        flag = false;
    }

    if ($('#register_password_confirmation').val() != $('#register_password').val()) {
        $('#register_password_confirmation').next().html('Please enter the same password');
        $('#register_password_confirmation').next().css('right', '8px');
        flag = false;
    }

    return flag;
}

/*
* function: inputOnFocusHideHint
* description: hide the error message when on focus
 */
function inputOnFocusHideHint(input) {
    $(input).next().css('right', '-300px');
}

/*
* procedure: hideErrorMessageWhenClick
 */
$(function() {
    $('.login_input_error').each(function(index, item) {
        $(item).click(function() {
            $(item).css('right', '-300px');
            $(item).prev().focus();
        });
    });
})

/*
* function: userLogin
* description: validate login form
 */
function userLogin() {
    var flag = true;

    if ($('#login_email').val() == "") {
        $('#login_email').next().css('right', '8px');
        flag = false;
    }

    if ($('#login_password').val() == "") {
        $('#login_password').next().css('right', '8px');
        flag = false;
    }

    return flag;
}





