var math_count = 1;
var mathSymbol = ['\\alpha', '\\beta', '\\gamma', '\\delta', '\\epsilon', '\\varepsilon', '\\zeta', '\\eta', '\\theta', '\\vartheta', '\\iota', '\\kappa', '\\lambda', '\\mu', '\\nu', '\\xi', '\\pi', '\\varpi', '\\rho', '\\varrho', '\\sigma', '\\varsigma', '\\tau', '\\upsilon', '\\phi', '\\varphi', '\\chi', '\\psi', '\\omega', '\\Gamma', '\\Delta', '\\Theta', '\\Lambda', '\\Xi', '\\Pi', '\\Sigma', '\\Upsilon', '\\Phi', '\\Psi', '\\Omega', '\\times', '\\div', '\\cdot', '\\pm', '\\mp', '\\ast', '\\star', '\\circ', '\\bullet', '\\oplus', '\\ominus', '\\oslash', '\\otimes', '\\odot', '\\dagger', '\\ddagger', '\\vee', '\\wedge', '\\cap', '\\cup', '\\aleph', '\\Re', '\\Im', '\\top', '\\bot', '\\infty', '\\partial', '\\forall', '\\exists', '\\neg', '\\angle', '\\triangle', '\\diamond', '\\leq', '\\geq', '\\prec', '\\succ', '\\preceq', '\\succeq', '\\ll', '\\gg', '\\equiv', '\\sim', '\\simeq', '\\asymp', '\\approx', '\\ne', '\\subset', '\\supset', '\\subseteq', '\\supseteq', '\\in', '\\ni', '\\notin', 'x_{a}', 'x^{b}', 'x_{a}^{b}', '\\bar{x}', '\\tilde{x}', '\\frac{a}{b}', '\\sqrt{x}', '\\sqrt[n]{x}', '\\bigcap_{a}^{b}', '\\bigcup_{a}^{b}', '\\prod_{a}^{b}', '\\coprod_{a}^{b}', '\\left( x \\right)', '\\left[ x \\right]', '\\left\\{ x \\right\\}', '\\left| x \\right|', '\\int_{a}^{b}', '\\oint_{a}^{b}', '\\sum_{a}^{b}{x}', '\\lim_{a \\rightarrow b}{x}', '\\leftarrow', '\\rightarrow', '\\leftrightarrow', '\\Leftarrow', '\\Rightarrow', '\\Leftrightarrow', '\\uparrow', '\\downarrow', '\\updownarrow', '\\Uparrow', '\\Downarrow', '\\Updownarrow'];

/**
 * Generate math symbols panel.
 *
 * @param tableID
 * @param rows
 * @param colums
 * @param bound
 * @param width
 * @param height
 * @param increaseX
 * @param increaseY
 * @param baseX
 * @param baseY
 */
function generateMathPanel(tableID, rows, colums, bound, width, height, increaseX, increaseY, baseX, baseY) {
    var $tbody = $('#' + tableID).find('tbody');
    var position_x = baseX;
    var position_y = baseY;
    var count = 1;
    for(var i = 1; i <= rows; i++) {
        var  $tr = $('<tr></tr>').addClass("math-table-tr");
        for (var j = 1; j <= colums; j++) {
            var $cell = $('<td></td>').addClass("math-table-cell");
            var $div = $('<div></div>').addClass("math-table-item");
            if (count > bound) {
                continue;
            }

            $div.css('background-position', position_x + 'px ' + position_y + 'px');
            $div.css('width', width + 'px');
            $div.css('height', height + 'px');

            $div.attr('id', 'math-symbol-' + math_count);
            $cell.attr('id', 'math-cell-' + math_count);
            $cell.attr('data-symbol', math_count);

            position_x += increaseX;
            position_y += increaseY;

            math_count++;
            count++;

            // append div into cell
            $cell.html($div[0]);
            // append cell into tr
            $tr.append($cell[0]);
        }
        // append tr to tbody
        $tbody.append($tr[0]);
    }
}

/**
 * For math menu popover
 *
 * @param num
 */
function math_menuPopover(num) {
    for(var i = 1; i <= num; i++) {
        $('#math-cell-' + i).popover({
            trigger : 'click',
            html : true,
            container : 'body',
            content : $('#math-equation-' + i)[0],
            placement : 'bottom'
        });

        $('#math-equation-' + i).remove();

        function generateShowEvent(id) {
            return function() {
                for(var k = 1; k<= 5; k++) {
                    if (k != id) {
                        $('#math-cell-' + k).popover('hide');
                    }
                }
                bind_MathCellHover(1, 135);
                bind_MathCellClick('tex-formula', 6, 135);
            }
        }

        $('#math-cell-' + i).on('shown.bs.popover', generateShowEvent(i));
    }
}

/**
 * Bind math cell hover event
 *
 * @param start
 * @param end
 */
function bind_MathCellHover(start, end) {
    for (var i = start; i <= end; i++) {
        $('#math-cell-' + i).unbind('hover');
        $('#math-cell-' + i).hover(function() {
            $(this).addClass("math-table-cell-hover");
        }, function() {
            $(this).removeClass("math-table-cell-hover");
        });
    }
}

/**
 * Bind math cell click event
 *
 * @param appendTo
 * @param start
 * @param end
 */
function bind_MathCellClick(appendTo, start, end) {
    for (var i = start; i <= end; i++) {
        $('#math-cell-' + i).unbind('click');
        $('#math-cell-' + i).click(appendFormula(i));
    }

    function appendFormula(k) {
        return function() {
            var $txt = $('#' + appendTo);
            var caretPos = $txt[0].selectionStart;
            var textAreaTxt = $txt.val();
            var txtToAdd = mathSymbol[k - 6] + ' ';
            $txt.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos));
            renderEquation();
        }
    }

}

/**
 * Render the #tex-preview
 */
function renderEquation() {
    $('#tex-preview').html('$$' + $('#tex-formula').val() + '$$');
    MathJax.Hub.Queue(["Typeset",MathJax.Hub,"tex-preview"]);
}

/**
 * Necessary javascript for math_editor
 */
function math_editor() {
    generateMathPanel('math-equation-menu', 1, 5, 5, 48, 20, -46, 0, 0, 0);
    generateMathPanel('math-equation-1', 6, 7, 40, 20, 20, -18, 0, 0, -30);
    generateMathPanel('math-equation-2', 5, 7, 33, 20, 20, -18, 0, 0, -50);
    generateMathPanel('math-equation-3', 3, 7, 21, 20, 20, -18, 0, 0, -70);
    generateMathPanel('math-equation-4', 2, 10, 20, 30, 56, -30, 0, 0, -90);
    generateMathPanel('math-equation-5', 1, 12, 12, 20, 20, -18, 0, 0, -150);

    math_menuPopover(5);
    bind_MathCellHover(1, 135);

    // dynamic rendering tex formular
    var texTimer = null;
    $('#tex-formula').on('change keyup paste', function() {
        clearTimeout(texTimer);
        texTimer = setTimeout(function() {
            renderEquation();
        }, 800);
    });

    // popover click outside auto hide
    $('html').on('click', function (e) {
        if (!$(e.target).parents().hasClass('math-equation-menu')
            && $(e.target).parents('.popover.in').length === 0) {
            $('#math-equation-menu').find('td[id^=math-cell]').popover('hide');
        }
    });

    // bootstrap v3.3.6 #16732 bug fixed
    $('body').on('hidden.bs.popover', function (e) {
        $(e.target).data("bs.popover").inState.click = false;
    });
}


/**
 * Insert the formulat into the tinyMCE
 */
function insertMathFormula() {
    var $tex = $('#tex-formula');
    if (tinymce.activeEditor.selection.getContent({format: 'html'}).length == 0) {
        //curl 'https://latex.codecogs.com/gif.latex?'
        tinyMCE.activeEditor
            .insertContent('<img src="https://latex.codecogs.com/gif.latex?' +
                encodeURIComponent($tex.val())
                + '" data-type="tex" data-value="' + encodeURIComponent($tex.val()) + '">');
    } else {
        tinymce.activeEditor.selection.setContent('<img src="https://latex.codecogs.com/gif.latex?' +
            encodeURIComponent($tex.val())
            + '" data-type="tex" data-value="' + encodeURIComponent($tex.val()) + '">');
    }
    $('#latex_equation').modal('hide');
}

/**
 * Show the tex editor
 * @param value
 */
function callTexEditor(value) {
    if (value != null) {
        $('#tex-formula').val(value);
        renderEquation();
    }
    $('#latex_equation').modal('show');
}

/**
 * Bring the equation modal to the front
 */
$(function() {
    $('#latex_equation').on('show.bs.modal', function (e) {
        $(this).css('z-index', 100003);
    });
});

/**
 * Let mathjax rerender a element.
 * @param id
 */
function rerenderMath(id) {
    MathJax.Hub.Queue(["Typeset", MathJax.Hub, id]);
}
