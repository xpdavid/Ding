!function(){var n=Handlebars.template,a=Handlebars.templates=Handlebars.templates||{};a["_answer_item.html"]=n({1:function(n,a,l,e,t){var i,s=null!=a?a:{},o=l.helperMissing,c="function",r=n.escapeExpression;return'<div class="answer_overall">\n    <div class="answer_voting">\n        <button type="button" id="vote_answer_'+r((i=null!=(i=l.id||(null!=a?a.id:a))?i:o,typeof i===c?i.call(s,{name:"id",hash:{},data:t}):i))+'_up" class="btn btn-primary '+r((i=null!=(i=l.vote_up_class||(null!=a?a.vote_up_class:a))?i:o,typeof i===c?i.call(s,{name:"vote_up_class",hash:{},data:t}):i))+'" onclick="vote_answer(\''+r((i=null!=(i=l.id||(null!=a?a.id:a))?i:o,typeof i===c?i.call(s,{name:"id",hash:{},data:t}):i))+'\', \'up\')">\n            <div>\n                <span class="glyphicon glyphicon-triangle-top clear_margin"></span>\n            </div>\n            <span class="vote_answer_'+r((i=null!=(i=l.id||(null!=a?a.id:a))?i:o,typeof i===c?i.call(s,{name:"id",hash:{},data:t}):i))+'_count">'+r((i=null!=(i=l.votes||(null!=a?a.votes:a))?i:o,typeof i===c?i.call(s,{name:"votes",hash:{},data:t}):i))+'</span>\n        </button>\n        <button type="button" id="vote_answer_'+r((i=null!=(i=l.id||(null!=a?a.id:a))?i:o,typeof i===c?i.call(s,{name:"id",hash:{},data:t}):i))+'_down" class="btn btn-primary '+r((i=null!=(i=l.vote_down_class||(null!=a?a.vote_down_class:a))?i:o,typeof i===c?i.call(s,{name:"vote_down_class",hash:{},data:t}):i))+'" onclick="vote_answer(\''+r((i=null!=(i=l.id||(null!=a?a.id:a))?i:o,typeof i===c?i.call(s,{name:"id",hash:{},data:t}):i))+'\', \'down\')">\n            <span class="glyphicon glyphicon-triangle-bottom clear_margin"></span>\n        </button>\n    </div>\n\n\n    <div class="clearfix">\n        <div class="float-left"><strong>'+r((i=null!=(i=l.user_name||(null!=a?a.user_name:a))?i:o,typeof i===c?i.call(s,{name:"user_name",hash:{},data:t}):i))+'</strong>, <span class="font-black">'+r((i=null!=(i=l.user_bio||(null!=a?a.user_bio:a))?i:o,typeof i===c?i.call(s,{name:"user_bio",hash:{},data:t}):i))+'</span> </div>\n        <img class="float-right" src="image/sample_icon.png" alt="">\n    </div>\n\n    <div>\n        <span class="answer_vote "><span class="vote_answer_'+r((i=null!=(i=l.id||(null!=a?a.id:a))?i:o,typeof i===c?i.call(s,{name:"id",hash:{},data:t}):i))+'_count">'+r((i=null!=(i=l.votes||(null!=a?a.votes:a))?i:o,typeof i===c?i.call(s,{name:"votes",hash:{},data:t}):i))+'</span> Vote(s)</span>\n    </div>\n\n    <div class="answer_content font-black">\n        '+r((i=null!=(i=l.answer||(null!=a?a.answer:a))?i:o,typeof i===c?i.call(s,{name:"answer",hash:{},data:t}):i))+'\n    </div>\n\n    <div class="horizontal_item">\n        <a href="#">Posted on '+r((i=null!=(i=l.created_at||(null!=a?a.created_at:a))?i:o,typeof i===c?i.call(s,{name:"created_at",hash:{},data:t}):i))+"</a>\n        <a href=\"#\" onclick=\"showComment(event, this, 'answer', '"+r((i=null!=(i=l.id||(null!=a?a.id:a))?i:o,typeof i===c?i.call(s,{name:"id",hash:{},data:t}):i))+"', 'answer_comment_"+r((i=null!=(i=l.id||(null!=a?a.id:a))?i:o,typeof i===c?i.call(s,{name:"id",hash:{},data:t}):i))+'\');">\n            <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>\n            Comment (<span id="answer_comment_'+r((i=null!=(i=l.id||(null!=a?a.id:a))?i:o,typeof i===c?i.call(s,{name:"id",hash:{},data:t}):i))+'_replies_count">'+r((i=null!=(i=l.numComment||(null!=a?a.numComment:a))?i:o,typeof i===c?i.call(s,{name:"numComment",hash:{},data:t}):i))+'</span>)\n        </a>\n        <a href="#"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>Bookmark</a>\n        <a href="#"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>Not helpful</a>\n        <a href="#"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>Report</a>\n    </div>\n\n\n    <div class="comment_box" id="answer_comment_'+r((i=null!=(i=l.id||(null!=a?a.id:a))?i:o,typeof i===c?i.call(s,{name:"id",hash:{},data:t}):i))+'">\n        <div class="comment_spike"></div>\n        <div class="comment_list">\n            <div class="comment_content" id="answer_comment_'+r((i=null!=(i=l.id||(null!=a?a.id:a))?i:o,typeof i===c?i.call(s,{name:"id",hash:{},data:t}):i))+'_content">\n\n            </div>\n\n            <div class="text-center" id="answer_comment_'+r((i=null!=(i=l.id||(null!=a?a.id:a))?i:o,typeof i===c?i.call(s,{name:"id",hash:{},data:t}):i))+'_nav">\n\n            </div>\n\n            <div class="comment_form clearfix">\n                <div class="form-group">\n                    <input type="email"\n                           class="form-control" id="answer_comment_'+r((i=null!=(i=l.id||(null!=a?a.id:a))?i:o,typeof i===c?i.call(s,{name:"id",hash:{},data:t}):i))+'_input"\n                           placeholder="Write Your Comment Here",\n                           id="answer_comment_'+r((i=null!=(i=l.id||(null!=a?a.id:a))?i:o,typeof i===c?i.call(s,{name:"id",hash:{},data:t}):i))+'_input",\n                           onfocus="show_form(event, \'answer_comment_'+r((i=null!=(i=l.id||(null!=a?a.id:a))?i:o,typeof i===c?i.call(s,{name:"id",hash:{},data:t}):i))+'_buttons\')">\n                </div>\n                <div class="float-right form-group noneDisplay" id="answer_comment_'+r((i=null!=(i=l.id||(null!=a?a.id:a))?i:o,typeof i===c?i.call(s,{name:"id",hash:{},data:t}):i))+'_buttons">\n                    <a href="#" role="button" class="space-right-big"\n                       onclick="cancel_from(event, \'answer_comment_'+r((i=null!=(i=l.id||(null!=a?a.id:a))?i:o,typeof i===c?i.call(s,{name:"id",hash:{},data:t}):i))+'_buttons\')">Cancel</a>\n                    <button class="btn btn-primary" type="submit" onclick="saveComment(\'answer_comment_'+r((i=null!=(i=l.id||(null!=a?a.id:a))?i:o,typeof i===c?i.call(s,{name:"id",hash:{},data:t}):i))+"', '"+r((i=null!=(i=l.id||(null!=a?a.id:a))?i:o,typeof i===c?i.call(s,{name:"id",hash:{},data:t}):i))+"', 'answer')\">Submit</button>\n                </div>\n            </div>\n\n        </div>\n    </div>\n\n</div>\n<hr>\n"},compiler:[7,">= 4.0.0"],main:function(n,a,l,e,t){var i;return"<!--this is the template for answer item-->\n<!--the template will rendered by javascript template engine `handlebars.js`-->\n"+(null!=(i=l.each.call(null!=a?a:{},null!=a?a.answers:a,{name:"each",hash:{},fn:n.program(1,t,0),inverse:n.noop,data:t}))?i:"")},useData:!0}),a["_comment_conversation_item.html"]=n({1:function(n,a,l,e,t){var i,s,o=n.escapeExpression,c=null!=a?a:{},r=l.helperMissing,d="function";return'<div class="media comment_item">\n    <div class="media-left">\n        <a href="#">\n            <img class="media-object" src="..." alt="...">\n        </a>\n    </div>\n    <div class="media-body">\n        <div class="clearfix font-black">\n            <div class="clearfix">\n                <div class="media-heading float-left">\n                    <a href=""><strong>'+o(n.lambda(null!=(i=null!=a?a.from:a)?i.user_name:i,a))+"</strong></a>\n"+(null!=(i=l["if"].call(c,null!=a?a.to:a,{name:"if",hash:{},fn:n.program(2,t,0),inverse:n.noop,data:t}))?i:"")+'                </div>\n            </div>\n            <div class="comment_item_content">\n                '+o((s=null!=(s=l.reply||(null!=a?a.reply:a))?s:r,typeof s===d?s.call(c,{name:"reply",hash:{},data:t}):s))+'\n            </div>\n        </div>\n        <div class="float-left horizontal_item">\n            <a href="#">'+o((s=null!=(s=l.created_at||(null!=a?a.created_at:a))?s:r,typeof s===d?s.call(c,{name:"created_at",hash:{},data:t}):s))+'</a>\n            <span class="reply_op">\n                <a href="#" class="'+o((s=null!=(s=l.vote_up_class||(null!=a?a.vote_up_class:a))?s:r,typeof s===d?s.call(c,{name:"vote_up_class",hash:{},data:t}):s))+'" id="vote_reply_'+o((s=null!=(s=l.id||(null!=a?a.id:a))?s:r,typeof s===d?s.call(c,{name:"id",hash:{},data:t}):s))+'_c_up" onclick="vote_reply(event, \''+o((s=null!=(s=l.id||(null!=a?a.id:a))?s:r,typeof s===d?s.call(c,{name:"id",hash:{},data:t}):s))+"_c');vote_reply(event, '"+o((s=null!=(s=l.id||(null!=a?a.id:a))?s:r,typeof s===d?s.call(c,{name:"id",hash:{},data:t}):s))+'\')"><span class="glyphicon glyphicon-heart" aira-hidden="true"></span>Like</a>\n                <a href="#"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>Report</a>\n            </span>\n        </div>\n        <div class="float-right"><span id="vote_reply_'+o((s=null!=(s=l.id||(null!=a?a.id:a))?s:r,typeof s===d?s.call(c,{name:"id",hash:{},data:t}):s))+'_c_count">'+o((s=null!=(s=l.votes||(null!=a?a.votes:a))?s:r,typeof s===d?s.call(c,{name:"votes",hash:{},data:t}):s))+'</span> vote(s)</div>\n        <span class="clearfix"></span>\n\n    </div>\n</div>\n<hr class="small_hrLight">\n'},2:function(n,a,l,e,t){var i;return'                    reply <a href=""><strong>'+n.escapeExpression(n.lambda(null!=(i=null!=a?a.to:a)?i.user_name:i,a))+"</strong></a>\n"},compiler:[7,">= 4.0.0"],main:function(n,a,l,e,t){var i;return"<!--this is the template for show conversation reply item-->\n<!--the template will rendered by javascript template engine `handlebars.js`-->\n"+(null!=(i=l.each.call(null!=a?a:{},null!=a?a.replies:a,{name:"each",hash:{},fn:n.program(1,t,0),inverse:n.noop,data:t}))?i:"")},useData:!0}),a["_page_nav.html"]=n({1:function(n,a,l,e,t){var i,s=null!=a?a:{},o=l.helperMissing,c="function",r=n.escapeExpression;return'            <li class="'+r((i=null!=(i=l["class"]||(null!=a?a["class"]:a))?i:o,typeof i===c?i.call(s,{name:"class",hash:{},data:t}):i))+'"><span href="#" onclick="'+r((i=null!=(i=l.onclick||(null!=a?a.onclick:a))?i:o,typeof i===c?i.call(s,{name:"onclick",hash:{},data:t}):i))+'">'+r((i=null!=(i=l.name||(null!=a?a.name:a))?i:o,typeof i===c?i.call(s,{name:"name",hash:{},data:t}):i))+"</span></li>\n"},compiler:[7,">= 4.0.0"],main:function(n,a,l,e,t){var i,s=n.lambda,o=n.escapeExpression;return'<!--this is the template for nav page-->\n<!--the template will rendered by javascript template engine `handlebars.js`-->\n<nav>\n    <ul class="pagination">\n        <li class="'+o(s(null!=(i=null!=a?a.prev:a)?i["class"]:i,a))+'">\n            <span aria-label="Previous" onclick="'+o(s(null!=(i=null!=a?a.prev:a)?i.onclick:i,a))+'">\n                <span aria-hidden="true">&laquo;</span>\n            </span>\n        </li>\n'+(null!=(i=l.each.call(null!=a?a:{},null!=a?a.pages:a,{name:"each",hash:{},fn:n.program(1,t,0),inverse:n.noop,data:t}))?i:"")+'        <li class="'+o(s(null!=(i=null!=a?a.next:a)?i["class"]:i,a))+'">\n            <span href="#" aria-label="Next">\n                <span aria-hidden="true" onclick="'+o(s(null!=(i=null!=a?a.next:a)?i.onclick:i,a))+'">&raquo;</span>\n            </span>\n        </li>\n    </ul>\n</nav>'},useData:!0}),a["_reply_item.html"]=n({1:function(n,a,l,e,t){var i,s,o=n.escapeExpression,c=null!=a?a:{},r=l.helperMissing,d="function";return'<div class="media comment_item">\n    <div class="media-left">\n        <a href="#">\n            <img class="media-object" src="..." alt="...">\n        </a>\n    </div>\n    <div class="media-body">\n        <div class="clearfix font-black">\n            <div class="clearfix">\n                <div class="media-heading float-left">\n                    <a href=""><strong>'+o(n.lambda(null!=(i=null!=a?a.from:a)?i.user_name:i,a))+"</strong></a>\n"+(null!=(i=l["if"].call(c,null!=a?a.to:a,{name:"if",hash:{},fn:n.program(2,t,0),inverse:n.noop,data:t}))?i:"")+"                </div>\n"+(null!=(i=l["if"].call(c,null!=a?a.to:a,{name:"if",hash:{},fn:n.program(4,t,0),inverse:n.noop,data:t}))?i:"")+'\n            </div>\n            <div class="comment_item_content">\n                '+o((s=null!=(s=l.reply||(null!=a?a.reply:a))?s:r,typeof s===d?s.call(c,{name:"reply",hash:{},data:t}):s))+'\n            </div>\n        </div>\n        <div class="float-left horizontal_item">\n            <a href="#">'+o((s=null!=(s=l.created_at||(null!=a?a.created_at:a))?s:r,typeof s===d?s.call(c,{name:"created_at",hash:{},data:t}):s))+'</a>\n            <span class="reply_op">\n                <a href="#" onclick="reply_comment(event, \''+o((s=null!=(s=l.id||(null!=a?a.id:a))?s:r,typeof s===d?s.call(c,{name:"id",hash:{},data:t}):s))+'\')"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span>Reply</a>\n                <a href="#" class="'+o((s=null!=(s=l.vote_up_class||(null!=a?a.vote_up_class:a))?s:r,typeof s===d?s.call(c,{name:"vote_up_class",hash:{},data:t}):s))+'" id="vote_reply_'+o((s=null!=(s=l.id||(null!=a?a.id:a))?s:r,typeof s===d?s.call(c,{name:"id",hash:{},data:t}):s))+'_up" onclick="vote_reply(event, \''+o((s=null!=(s=l.id||(null!=a?a.id:a))?s:r,typeof s===d?s.call(c,{name:"id",hash:{},data:t}):s))+'\')"><span class="glyphicon glyphicon-heart" aira-hidden="true"></span>Like</a>\n                <a href="#"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>Report</a>\n            </span>\n        </div>\n        <div class="float-right"><span id="vote_reply_'+o((s=null!=(s=l.id||(null!=a?a.id:a))?s:r,typeof s===d?s.call(c,{name:"id",hash:{},data:t}):s))+'_count">'+o((s=null!=(s=l.votes||(null!=a?a.votes:a))?s:r,typeof s===d?s.call(c,{name:"votes",hash:{},data:t}):s))+'</span> vote(s)</div>\n        <span class="clearfix"></span>\n\n        <div class="comment_for_user clearfix" id="reply_comment_'+o((s=null!=(s=l.id||(null!=a?a.id:a))?s:r,typeof s===d?s.call(c,{name:"id",hash:{},data:t}):s))+'">\n            <div class="form-group">\n                <input type="email" class="form-control" id="'+o((s=null!=(s=l.for_item||(null!=a?a.for_item:a))?s:r,typeof s===d?s.call(c,{name:"for_item",hash:{},data:t}):s))+"_comment_"+o((s=null!=(s=l.for_item_id||(null!=a?a.for_item_id:a))?s:r,typeof s===d?s.call(c,{name:"for_item_id",hash:{},data:t}):s))+"_reply_"+o((s=null!=(s=l.id||(null!=a?a.id:a))?s:r,typeof s===d?s.call(c,{name:"id",hash:{},data:t}):s))+'" placeholder="Write Your Comment Here">\n            </div>\n            <div class="float-right form-group">\n                <a href="#" role="button" class="space-right-big" onclick="cancel_from(event, \'reply_comment_'+o((s=null!=(s=l.id||(null!=a?a.id:a))?s:r,typeof s===d?s.call(c,{name:"id",hash:{},data:t}):s))+'\')">Cancel</a>\n                <button class="btn btn-primary" type="submit" onclick="saveComment(\''+o((s=null!=(s=l.for_item||(null!=a?a.for_item:a))?s:r,typeof s===d?s.call(c,{name:"for_item",hash:{},data:t}):s))+"_comment_"+o((s=null!=(s=l.for_item_id||(null!=a?a.for_item_id:a))?s:r,typeof s===d?s.call(c,{name:"for_item_id",hash:{},data:t}):s))+"', '"+o((s=null!=(s=l.for_item_id||(null!=a?a.for_item_id:a))?s:r,typeof s===d?s.call(c,{name:"for_item_id",hash:{},data:t}):s))+"' ,'"+o((s=null!=(s=l.for_item||(null!=a?a.for_item:a))?s:r,typeof s===d?s.call(c,{name:"for_item",hash:{},data:t}):s))+"', '"+o((s=null!=(s=l.id||(null!=a?a.id:a))?s:r,typeof s===d?s.call(c,{name:"id",hash:{},data:t}):s))+'\')">Comment</button>\n            </div>\n        </div>\n\n    </div>\n</div>\n<hr class="small_hrLight">\n'},2:function(n,a,l,e,t){var i;return'                        reply <a href=""><strong>'+n.escapeExpression(n.lambda(null!=(i=null!=a?a.to:a)?i.user_name:i,a))+"</strong></a>\n"},4:function(n,a,l,e,t){var i;return'                    <div class="float-right link_normal">\n                        <a href="#" onclick="showConversation(event, '+n.escapeExpression((i=null!=(i=l.id||(null!=a?a.id:a))?i:l.helperMissing,"function"==typeof i?i.call(null!=a?a:{},{name:"id",hash:{},data:t}):i))+')"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span>Show Conversation</a>\n                    </div>\n'},compiler:[7,">= 4.0.0"],main:function(n,a,l,e,t){var i;return"<!--this is the template for reply item-->\n<!--the template will rendered by javascript template engine `handlebars.js`-->\n"+(null!=(i=l.each.call(null!=a?a:{},null!=a?a.replies:a,{name:"each",hash:{},fn:n.program(1,t,0),inverse:n.noop,data:t}))?i:"")},useData:!0}),a["_topic_question_item.html"]=n({1:function(n,a,l,e,t){var i,s=null!=a?a:{},o=l.helperMissing,c="function",r=n.escapeExpression;return'<div id="topic_question_'+r((i=null!=(i=l.id||(null!=a?a.id:a))?i:o,typeof i===c?i.call(s,{name:"id",hash:{},data:t}):i))+'">\n    <div id="topic_question_'+r((i=null!=(i=l.id||(null!=a?a.id:a))?i:o,typeof i===c?i.call(s,{name:"id",hash:{},data:t}):i))+'_title" class="topic_question_title">\n        <strong><a href="#">'+r((i=null!=(i=l.title||(null!=a?a.title:a))?i:o,typeof i===c?i.call(s,{name:"title",hash:{},data:t}):i))+'</a></strong>\n    </div>\n    <div id="topic_question_'+r((i=null!=(i=l.id||(null!=a?a.id:a))?i:o,typeof i===c?i.call(s,{name:"id",hash:{},data:t}):i))+'_content">\n\n    </div>\n</div>\n\n'},compiler:[7,">= 4.0.0"],main:function(n,a,l,e,t){var i;return"<!--this is the template for topic question item-->\n<!--the template will rendered by javascript template engine `handlebars.js`-->\n"+(null!=(i=l.each.call(null!=a?a:{},null!=a?a.questions:a,{name:"each",hash:{},fn:n.program(1,t,0),inverse:n.noop,data:t}))?i:"")},useData:!0}),a["_topics_item.html"]=n({1:function(n,a,l,e,t){var i,s=n.lambda,o=n.escapeExpression;return'<div class="row">\n    <div class="col-md-6">\n        <div class="media topics_item">\n            <div class="media-left">\n                <a href="#">\n                    <img class="media-object topics_item_image" data-src="..." alt="Generic placeholder image" src="image/sample_icon.png">\n                </a>\n            </div>\n            <div class="media-body topics_more">\n                <div class="clearfix">\n                    <div class="float-left topics_name"><a href="/topic/'+o(s(null!=(i=null!=a?a.left:a)?i.id:i,a))+'">'+o(s(null!=(i=null!=a?a.left:a)?i.name:i,a))+'</a></div>\n                    <a class="float-right topics_subscribe" href="#"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Subscribe</a>\n                </div>\n                <p class="font-black">'+o(s(null!=(i=null!=a?a.left:a)?i.description:i,a))+"</p>\n            </div>\n        </div>\n    </div>\n\n"+(null!=(i=l["if"].call(null!=a?a:{},null!=(i=null!=a?a.right:a)?i.id:i,{name:"if",hash:{},fn:n.program(2,t,0),inverse:n.noop,data:t}))?i:"")+'</div>\n<hr class="small_hrLight">\n'},2:function(n,a,l,e,t){var i,s=n.lambda,o=n.escapeExpression;return'        <div class="col-md-6">\n            <div class="media topics_item">\n                <div class="media-left">\n                    <a href="#">\n                        <img class="media-object topics_item_image" data-src="..." alt="Generic placeholder image" src="image/sample_icon.png">\n                    </a>\n                </div>\n                <div class="media-body topics_more">\n                    <div class="clearfix">\n                        <div class="float-left topics_name"><a href="#">/topic/'+o(s(null!=(i=null!=a?a.right:a)?i.id:i,a))+'</a></div>\n                        <a class="float-right topics_subscribe" href="#"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Subscribe</a>\n                    </div>\n                    <p class="font-black">'+o(s(null!=(i=null!=a?a.right:a)?i.description:i,a))+"</p>\n                </div>\n            </div>\n        </div>\n"},compiler:[7,">= 4.0.0"],main:function(n,a,l,e,t){var i;return"<!--this is the template for reply item-->\n<!--the template will rendered by javascript template engine `handlebars.js`-->\n"+(null!=(i=l.each.call(null!=a?a:{},null!=a?a.group2Topics:a,{name:"each",hash:{},fn:n.program(1,t,0),inverse:n.noop,data:t}))?i:"")},useData:!0})}();