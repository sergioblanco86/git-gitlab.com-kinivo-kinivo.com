(function($){$(document).ready(function(){$("body").on("change","#shipping_alt",function(){$.post(WCMA_Ajax.ajaxurl,{action:'alt_change',id:$(this).val(),wc_multiple_addresses:WCMA_Ajax.wc_multiple_addresses},function(response){$('#shipping_address_1').val(response.shipping_address_1);$('#shipping_address_2').val(response.shipping_address_2);$('#shipping_city').val(response.shipping_city);$('#shipping_company').val(response.shipping_company);$('#shipping_country').val(response.shipping_country);$("#shipping_country_chosen").find('span').html(response.shipping_country_text);$('#shipping_first_name').val(response.shipping_first_name);$('#shipping_last_name').val(response.shipping_last_name);$('#shipping_postcode').val(response.shipping_postcode);$('#shipping_state').val(response.shipping_state);$('select').trigger("chosen:updated");});});$("body").on("change","#billing_alt",function(){$.post(WCMA_Ajax.ajaxurl,{action:'alt_change',id:$(this).val(),wc_multiple_addresses:WCMA_Ajax.wc_multiple_addresses},function(response){$('#billing_address_1').val(response.shipping_address_1);$('#billing_address_2').val(response.shipping_address_2);$('#billing_city').val(response.shipping_city);$('#billing_company').val(response.shipping_company);$('#billing_country').val(response.shipping_country);$("#billing_country_chosen").find('span').html(response.shipping_country_text);$('#billing_first_name').val(response.shipping_first_name);$('#billing_last_name').val(response.shipping_last_name);$('#billing_postcode').val(response.shipping_postcode);$('#billing_state').val(response.shipping_state);$('select').trigger("chosen:updated");});});});})(jQuery);;
/* 
 * jQuery Steps v1.0.7 - 05/07/2014
 * Copyright (c) 2014 Rafael Staib (http://www.jquery-steps.com)
 * Licensed under MIT http://www.opensource.org/licenses/MIT
 */
;(function($,undefined)
{$.fn.extend({_aria:function(name,value)
{return this.attr("aria-"+name,value);},_removeAria:function(name)
{return this.removeAttr("aria-"+name);},_enableAria:function(enable)
{return(enable==null||enable)?this.removeClass("disabled")._aria("disabled","false"):this.addClass("disabled")._aria("disabled","true");},_showAria:function(show)
{return(show==null||show)?this.show()._aria("hidden","false"):this.hide()._aria("hidden","true");},_selectAria:function(select)
{return(select==null||select)?this.addClass("current")._aria("selected","true"):this.removeClass("current")._aria("selected","false");},_id:function(id)
{return(id)?this.attr("id",id):this.attr("id");}});if(!String.prototype.format)
{String.prototype.format=function()
{var args=(arguments.length===1&&$.isArray(arguments[0]))?arguments[0]:arguments;var formattedString=this;for(var i=0;i<args.length;i++)
{var pattern=new RegExp("\\{"+i+"\\}","gm");formattedString=formattedString.replace(pattern,args[i]);}
return formattedString;};}
var _uniqueId=0;var _cookiePrefix="jQu3ry_5teps_St@te_";var _tabSuffix="-t-";var _tabpanelSuffix="-p-";var _titleSuffix="-h-";var _indexOutOfRangeErrorMessage="Index out of range.";var _missingCorrespondingElementErrorMessage="One or more corresponding step {0} are missing.";function addStepToCache(wizard,step)
{getSteps(wizard).push(step);}
function analyzeData(wizard,options,state)
{var stepTitles=wizard.children(options.headerTag),stepContents=wizard.children(options.bodyTag);if(stepTitles.length>stepContents.length)
{throwError(_missingCorrespondingElementErrorMessage,"contents");}
else if(stepTitles.length<stepContents.length)
{throwError(_missingCorrespondingElementErrorMessage,"titles");}
var startIndex=options.startIndex;state.stepCount=stepTitles.length;if(options.saveState&&$.cookie)
{var savedState=$.cookie(_cookiePrefix+getUniqueId(wizard));var savedIndex=parseInt(savedState,0);if(!isNaN(savedIndex)&&savedIndex<state.stepCount)
{startIndex=savedIndex;}}
state.currentIndex=startIndex;stepTitles.each(function(index)
{var item=$(this),content=stepContents.eq(index),modeData=content.data("mode"),mode=(modeData==null)?contentMode.html:getValidEnumValue(contentMode,(/^\s*$/.test(modeData)||isNaN(modeData))?modeData:parseInt(modeData,0)),contentUrl=(mode===contentMode.html||content.data("url")===undefined)?"":content.data("url"),contentLoaded=(mode!==contentMode.html&&content.data("loaded")==="1"),step=$.extend({},stepModel,{title:item.html(),content:(mode===contentMode.html)?content.html():"",contentUrl:contentUrl,contentMode:mode,contentLoaded:contentLoaded});addStepToCache(wizard,step);});}
function cancel(wizard)
{wizard.triggerHandler("canceled");}
function decreaseCurrentIndexBy(state,decreaseBy)
{return state.currentIndex-decreaseBy;}
function destroy(wizard,options)
{var eventNamespace=getEventNamespace(wizard);wizard.unbind(eventNamespace).removeData("uid").removeData("options").removeData("state").removeData("steps").removeData("eventNamespace").find(".actions a").unbind(eventNamespace);wizard.removeClass(options.clearFixCssClass+" vertical");var contents=wizard.find(".content > *");contents.removeData("loaded").removeData("mode").removeData("url");contents.removeAttr("id").removeAttr("role").removeAttr("tabindex").removeAttr("class").removeAttr("style")._removeAria("labelledby")._removeAria("hidden");wizard.find(".content > [data-mode='async'],.content > [data-mode='iframe']").empty();var wizardSubstitute=$("<{0} class=\"{1}\"></{0}>".format(wizard.get(0).tagName,wizard.attr("class")));var wizardId=wizard._id();if(wizardId!=null&&wizardId!=="")
{wizardSubstitute._id(wizardId);}
wizardSubstitute.html(wizard.find(".content").html());wizard.after(wizardSubstitute);wizard.remove();return wizardSubstitute;}
function finishStep(wizard,state)
{var currentStep=wizard.find(".steps li").eq(state.currentIndex);if(wizard.triggerHandler("finishing",[state.currentIndex]))
{currentStep.addClass("done").removeClass("error");wizard.triggerHandler("finished",[state.currentIndex]);}
else
{currentStep.addClass("error");}}
function getEventNamespace(wizard)
{var eventNamespace=wizard.data("eventNamespace");if(eventNamespace==null)
{eventNamespace="."+getUniqueId(wizard);wizard.data("eventNamespace",eventNamespace);}
return eventNamespace;}
function getStepAnchor(wizard,index)
{var uniqueId=getUniqueId(wizard);return wizard.find("#"+uniqueId+_tabSuffix+index);}
function getStepPanel(wizard,index)
{var uniqueId=getUniqueId(wizard);return wizard.find("#"+uniqueId+_tabpanelSuffix+index);}
function getStepTitle(wizard,index)
{var uniqueId=getUniqueId(wizard);return wizard.find("#"+uniqueId+_titleSuffix+index);}
function getOptions(wizard)
{return wizard.data("options");}
function getState(wizard)
{return wizard.data("state");}
function getSteps(wizard)
{return wizard.data("steps");}
function getStep(wizard,index)
{var steps=getSteps(wizard);if(index<0||index>=steps.length)
{throwError(_indexOutOfRangeErrorMessage);}
return steps[index];}
function getUniqueId(wizard)
{var uniqueId=wizard.data("uid");if(uniqueId==null)
{uniqueId=wizard._id();if(uniqueId==null)
{uniqueId="steps-uid-".concat(_uniqueId);wizard._id(uniqueId);}
_uniqueId++;wizard.data("uid",uniqueId);}
return uniqueId;}
function getValidEnumValue(enumType,keyOrValue)
{validateArgument("enumType",enumType);validateArgument("keyOrValue",keyOrValue);if(typeof keyOrValue==="string")
{var value=enumType[keyOrValue];if(value===undefined)
{throwError("The enum key '{0}' does not exist.",keyOrValue);}
return value;}
else if(typeof keyOrValue==="number")
{for(var key in enumType)
{if(enumType[key]===keyOrValue)
{return keyOrValue;}}
throwError("Invalid enum value '{0}'.",keyOrValue);}
else
{throwError("Invalid key or value type.");}}
function goToNextStep(wizard,options,state)
{return paginationClick(wizard,options,state,increaseCurrentIndexBy(state,1));}
function goToPreviousStep(wizard,options,state)
{return paginationClick(wizard,options,state,decreaseCurrentIndexBy(state,1));}
function goToStep(wizard,options,state,index)
{if(index<0||index>=state.stepCount)
{throwError(_indexOutOfRangeErrorMessage);}
if(options.forceMoveForward&&index<state.currentIndex)
{return;}
var oldIndex=state.currentIndex;if(wizard.triggerHandler("stepChanging",[state.currentIndex,index]))
{state.currentIndex=index;saveCurrentStateToCookie(wizard,options,state);refreshStepNavigation(wizard,options,state,oldIndex);refreshPagination(wizard,options,state);loadAsyncContent(wizard,options,state);startTransitionEffect(wizard,options,state,index,oldIndex);wizard.triggerHandler("stepChanged",[index,oldIndex]);}
else
{wizard.find(".steps li").eq(oldIndex).addClass("error");}
return true;}
function increaseCurrentIndexBy(state,increaseBy)
{return state.currentIndex+increaseBy;}
function initialize(options)
{var opts=$.extend(true,{},defaults,options);return this.each(function()
{var wizard=$(this);var state={currentIndex:opts.startIndex,currentStep:null,stepCount:0,transitionElement:null};wizard.data("options",opts);wizard.data("state",state);wizard.data("steps",[]);analyzeData(wizard,opts,state);render(wizard,opts,state);registerEvents(wizard,opts);if(opts.autoFocus&&_uniqueId===0)
{getStepAnchor(wizard,opts.startIndex).focus();}});}
function insertStep(wizard,options,state,index,step)
{if(index<0||index>state.stepCount)
{throwError(_indexOutOfRangeErrorMessage);}
step=$.extend({},stepModel,step);insertStepToCache(wizard,index,step);if(state.currentIndex!==state.stepCount&&state.currentIndex>=index)
{state.currentIndex++;saveCurrentStateToCookie(wizard,options,state);}
state.stepCount++;var contentContainer=wizard.find(".content"),header=$("<{0}>{1}</{0}>".format(options.headerTag,step.title)),body=$("<{0}></{0}>".format(options.bodyTag));if(step.contentMode==null||step.contentMode===contentMode.html)
{body.html(step.content);}
if(index===0)
{contentContainer.prepend(body).prepend(header);}
else
{getStepPanel(wizard,(index-1)).after(body).after(header);}
renderBody(wizard,state,body,index);renderTitle(wizard,options,state,header,index);refreshSteps(wizard,options,state,index);if(index===state.currentIndex)
{refreshStepNavigation(wizard,options,state);}
refreshPagination(wizard,options,state);return wizard;}
function insertStepToCache(wizard,index,step)
{getSteps(wizard).splice(index,0,step);}
function keyUpHandler(event)
{var wizard=$(this),options=getOptions(wizard),state=getState(wizard);if(options.suppressPaginationOnFocus&&wizard.find(":focus").is(":input"))
{event.preventDefault();return false;}
var keyCodes={left:37,right:39};if(event.keyCode===keyCodes.left)
{event.preventDefault();goToPreviousStep(wizard,options,state);}
else if(event.keyCode===keyCodes.right)
{event.preventDefault();goToNextStep(wizard,options,state);}}
function loadAsyncContent(wizard,options,state)
{if(state.stepCount>0)
{var currentStep=getStep(wizard,state.currentIndex);if(!options.enableContentCache||!currentStep.contentLoaded)
{switch(getValidEnumValue(contentMode,currentStep.contentMode))
{case contentMode.iframe:wizard.find(".content > .body").eq(state.currentIndex).empty().html("<iframe src=\""+currentStep.contentUrl+"\" frameborder=\"0\" scrolling=\"no\" />").data("loaded","1");break;case contentMode.async:var currentStepContent=getStepPanel(wizard,state.currentIndex)._aria("busy","true").empty().append(renderTemplate(options.loadingTemplate,{text:options.labels.loading}));$.ajax({url:currentStep.contentUrl,cache:false}).done(function(data)
{currentStepContent.empty().html(data)._aria("busy","false").data("loaded","1");});break;}}}}
function paginationClick(wizard,options,state,index)
{var oldIndex=state.currentIndex;if(index>=0&&index<state.stepCount&&!(options.forceMoveForward&&index<state.currentIndex))
{var anchor=getStepAnchor(wizard,index),parent=anchor.parent(),isDisabled=parent.hasClass("disabled");parent._enableAria();anchor.click();if(oldIndex===state.currentIndex&&isDisabled)
{parent._enableAria(false);return false;}
return true;}
return false;}
function paginationClickHandler(event)
{event.preventDefault();var anchor=$(this),wizard=anchor.parent().parent().parent().parent(),options=getOptions(wizard),state=getState(wizard),href=anchor.attr("href");switch(href.substring(href.lastIndexOf("#")+1))
{case"cancel":cancel(wizard);break;case"finish":finishStep(wizard,state);break;case"next":goToNextStep(wizard,options,state);break;case"previous":goToPreviousStep(wizard,options,state);break;}}
function refreshPagination(wizard,options,state)
{if(options.enablePagination)
{var finish=wizard.find(".actions a[href$='#finish']").parent(),next=wizard.find(".actions a[href$='#next']").parent();if(!options.forceMoveForward)
{var previous=wizard.find(".actions a[href$='#previous']").parent();previous._enableAria(state.currentIndex>0);}
if(options.enableFinishButton&&options.showFinishButtonAlways)
{finish._enableAria(state.stepCount>0);next._enableAria(state.stepCount>1&&state.stepCount>(state.currentIndex+1));}
else
{finish._showAria(options.enableFinishButton&&state.stepCount===(state.currentIndex+1));next._showAria(state.stepCount===0||state.stepCount>(state.currentIndex+1))._enableAria(state.stepCount>(state.currentIndex+1)||!options.enableFinishButton);}}}
function refreshStepNavigation(wizard,options,state,oldIndex)
{var currentOrNewStepAnchor=getStepAnchor(wizard,state.currentIndex),currentInfo=$("<span class=\"current-info audible\">"+options.labels.current+" </span>"),stepTitles=wizard.find(".content > .title");if(oldIndex!=null)
{var oldStepAnchor=getStepAnchor(wizard,oldIndex);oldStepAnchor.parent().addClass("done").removeClass("error")._selectAria(false);stepTitles.eq(oldIndex).removeClass("current").next(".body").removeClass("current");currentInfo=oldStepAnchor.find(".current-info");currentOrNewStepAnchor.focus();}
currentOrNewStepAnchor.prepend(currentInfo).parent()._selectAria().removeClass("done")._enableAria();stepTitles.eq(state.currentIndex).addClass("current").next(".body").addClass("current");}
function refreshSteps(wizard,options,state,index)
{var uniqueId=getUniqueId(wizard);for(var i=index;i<state.stepCount;i++)
{var uniqueStepId=uniqueId+_tabSuffix+i,uniqueBodyId=uniqueId+_tabpanelSuffix+i,uniqueHeaderId=uniqueId+_titleSuffix+i,title=wizard.find(".title").eq(i)._id(uniqueHeaderId);wizard.find(".steps a").eq(i)._id(uniqueStepId)._aria("controls",uniqueBodyId).attr("href","#"+uniqueHeaderId).html(renderTemplate(options.titleTemplate,{index:i+1,title:title.html()}));wizard.find(".body").eq(i)._id(uniqueBodyId)._aria("labelledby",uniqueHeaderId);}}
function registerEvents(wizard,options)
{var eventNamespace=getEventNamespace(wizard);wizard.bind("canceled"+eventNamespace,options.onCanceled);wizard.bind("finishing"+eventNamespace,options.onFinishing);wizard.bind("finished"+eventNamespace,options.onFinished);wizard.bind("stepChanging"+eventNamespace,options.onStepChanging);wizard.bind("stepChanged"+eventNamespace,options.onStepChanged);if(options.enableKeyNavigation)
{wizard.bind("keyup"+eventNamespace,keyUpHandler);}
wizard.find(".actions a").bind("click"+eventNamespace,paginationClickHandler);}
function removeStep(wizard,options,state,index)
{if(index<0||index>=state.stepCount||state.currentIndex===index)
{return false;}
removeStepFromCache(wizard,index);if(state.currentIndex>index)
{state.currentIndex--;saveCurrentStateToCookie(wizard,options,state);}
state.stepCount--;getStepTitle(wizard,index).remove();getStepPanel(wizard,index).remove();getStepAnchor(wizard,index).parent().remove();if(index===0)
{wizard.find(".steps li").first().addClass("first");}
if(index===state.stepCount)
{wizard.find(".steps li").eq(index).addClass("last");}
refreshSteps(wizard,options,state,index);refreshPagination(wizard,options,state);return true;}
function removeStepFromCache(wizard,index)
{getSteps(wizard).splice(index,1);}
function render(wizard,options,state)
{var wrapperTemplate="<{0} class=\"{1}\">{2}</{0}>",orientation=getValidEnumValue(stepsOrientation,options.stepsOrientation),verticalCssClass=(orientation===stepsOrientation.vertical)?" vertical":"",contentWrapper=$(wrapperTemplate.format(options.contentContainerTag,"content "+options.clearFixCssClass,wizard.html())),stepsWrapper=$(wrapperTemplate.format(options.stepsContainerTag,"steps "+options.clearFixCssClass,"<ul role=\"tablist\"></ul>")),stepTitles=contentWrapper.children(options.headerTag),stepContents=contentWrapper.children(options.bodyTag);wizard.attr("role","application").empty().append(stepsWrapper).append(contentWrapper).addClass(options.cssClass+" "+options.clearFixCssClass+verticalCssClass);stepContents.each(function(index)
{renderBody(wizard,state,$(this),index);});stepTitles.each(function(index)
{renderTitle(wizard,options,state,$(this),index);});refreshStepNavigation(wizard,options,state);renderPagination(wizard,options,state);}
function renderBody(wizard,state,body,index)
{var uniqueId=getUniqueId(wizard),uniqueBodyId=uniqueId+_tabpanelSuffix+index,uniqueHeaderId=uniqueId+_titleSuffix+index;body._id(uniqueBodyId).attr("role","tabpanel")._aria("labelledby",uniqueHeaderId).addClass("body")._showAria(state.currentIndex===index);}
function renderPagination(wizard,options,state)
{if(options.enablePagination)
{var pagination="<{0} class=\"actions {1}\"><ul role=\"menu\" aria-label=\"{2}\">{3}</ul></{0}>",buttonTemplate="<li><a href=\"#{0}\" role=\"menuitem\">{1}</a></li>",buttons="";if(!options.forceMoveForward)
{buttons+=buttonTemplate.format("previous",options.labels.previous);}
buttons+=buttonTemplate.format("next",options.labels.next);if(options.enableFinishButton)
{buttons+=buttonTemplate.format("finish",options.labels.finish);}
if(options.enableCancelButton)
{buttons+=buttonTemplate.format("cancel",options.labels.cancel);}
wizard.append(pagination.format(options.actionContainerTag,options.clearFixCssClass,options.labels.pagination,buttons));refreshPagination(wizard,options,state);loadAsyncContent(wizard,options,state);}}
function renderTemplate(template,substitutes)
{var matches=template.match(/#([a-z]*)#/gi);for(var i=0;i<matches.length;i++)
{var match=matches[i],key=match.substring(1,match.length-1);if(substitutes[key]===undefined)
{throwError("The key '{0}' does not exist in the substitute collection!",key);}
template=template.replace(match,substitutes[key]);}
return template;}
function renderTitle(wizard,options,state,header,index)
{var uniqueId=getUniqueId(wizard),uniqueStepId=uniqueId+_tabSuffix+index,uniqueBodyId=uniqueId+_tabpanelSuffix+index,uniqueHeaderId=uniqueId+_titleSuffix+index,stepCollection=wizard.find(".steps > ul"),title=renderTemplate(options.titleTemplate,{index:index+1,title:header.html()}),stepItem=$("<li role=\"tab\"><a id=\""+uniqueStepId+"\" href=\"#"+uniqueHeaderId+"\" aria-controls=\""+uniqueBodyId+"\">"+title+"</a></li>");stepItem._enableAria(options.enableAllSteps||state.currentIndex>index);if(state.currentIndex>index)
{stepItem.addClass("done");}
header._id(uniqueHeaderId).attr("tabindex","-1").addClass("title");if(index===0)
{stepCollection.prepend(stepItem);}
else
{stepCollection.find("li").eq(index-1).after(stepItem);}
if(index===0)
{stepCollection.find("li").removeClass("first").eq(index).addClass("first");}
if(index===(state.stepCount-1))
{stepCollection.find("li").removeClass("last").eq(index).addClass("last");}
stepItem.children("a").bind("click"+getEventNamespace(wizard),stepClickHandler);}
function saveCurrentStateToCookie(wizard,options,state)
{if(options.saveState&&$.cookie)
{$.cookie(_cookiePrefix+getUniqueId(wizard),state.currentIndex);}}
function startTransitionEffect(wizard,options,state,index,oldIndex)
{var stepContents=wizard.find(".content > .body"),effect=getValidEnumValue(transitionEffect,options.transitionEffect),effectSpeed=options.transitionEffectSpeed,newStep=stepContents.eq(index),currentStep=stepContents.eq(oldIndex);switch(effect)
{case transitionEffect.fade:case transitionEffect.slide:var hide=(effect===transitionEffect.fade)?"fadeOut":"slideUp",show=(effect===transitionEffect.fade)?"fadeIn":"slideDown";state.transitionElement=newStep;currentStep[hide](effectSpeed,function()
{var wizard=$(this)._showAria(false).parent().parent(),state=getState(wizard);if(state.transitionElement)
{state.transitionElement[show](effectSpeed,function()
{$(this)._showAria();});state.transitionElement=null;}}).promise();break;case transitionEffect.slideLeft:var outerWidth=currentStep.outerWidth(true),posFadeOut=(index>oldIndex)?-(outerWidth):outerWidth,posFadeIn=(index>oldIndex)?outerWidth:-(outerWidth);currentStep.animate({left:posFadeOut},effectSpeed,function(){$(this)._showAria(false);}).promise();newStep.css("left",posFadeIn+"px")._showAria().animate({left:0},effectSpeed).promise();break;default:currentStep._showAria(false);newStep._showAria();break;}}
function stepClickHandler(event)
{event.preventDefault();var anchor=$(this),wizard=anchor.parent().parent().parent().parent(),options=getOptions(wizard),state=getState(wizard),oldIndex=state.currentIndex;if(anchor.parent().is(":not(.disabled):not(.current)"))
{var href=anchor.attr("href"),position=parseInt(href.substring(href.lastIndexOf("-")+1),0);goToStep(wizard,options,state,position);}
if(oldIndex===state.currentIndex)
{getStepAnchor(wizard,oldIndex).focus();return false;}}
function throwError(message)
{if(arguments.length>1)
{message=message.format(Array.prototype.slice.call(arguments,1));}
throw new Error(message);}
function validateArgument(argumentName,argumentValue)
{if(argumentValue==null)
{throwError("The argument '{0}' is null or undefined.",argumentName);}}
$.fn.steps=function(method)
{if($.fn.steps[method])
{return $.fn.steps[method].apply(this,Array.prototype.slice.call(arguments,1));}
else if(typeof method==="object"||!method)
{return initialize.apply(this,arguments);}
else
{$.error("Method "+method+" does not exist on jQuery.steps");}};$.fn.steps.add=function(step)
{var state=getState(this);return insertStep(this,getOptions(this),state,state.stepCount,step);};$.fn.steps.destroy=function()
{return destroy(this,getOptions(this));};$.fn.steps.finish=function()
{finishStep(this,getState(this));};$.fn.steps.getCurrentIndex=function()
{return getState(this).currentIndex;};$.fn.steps.getCurrentStep=function()
{return getStep(this,getState(this).currentIndex);};$.fn.steps.getStep=function(index)
{return getStep(this,index);};$.fn.steps.insert=function(index,step)
{return insertStep(this,getOptions(this),getState(this),index,step);};$.fn.steps.next=function()
{return goToNextStep(this,getOptions(this),getState(this));};$.fn.steps.previous=function()
{return goToPreviousStep(this,getOptions(this),getState(this));};$.fn.steps.remove=function(index)
{return removeStep(this,getOptions(this),getState(this),index);};$.fn.steps.setStep=function(index,step)
{throw new Error("Not yet implemented!");};$.fn.steps.skip=function(count)
{throw new Error("Not yet implemented!");};var contentMode=$.fn.steps.contentMode={html:0,iframe:1,async:2};var stepsOrientation=$.fn.steps.stepsOrientation={horizontal:0,vertical:1};var transitionEffect=$.fn.steps.transitionEffect={none:0,fade:1,slide:2,slideLeft:3};var stepModel=$.fn.steps.stepModel={title:"",content:"",contentUrl:"",contentMode:contentMode.html,contentLoaded:false};var defaults=$.fn.steps.defaults={headerTag:"h1",bodyTag:"div",contentContainerTag:"div",actionContainerTag:"div",stepsContainerTag:"div",cssClass:"wizard",clearFixCssClass:"clearfix",stepsOrientation:stepsOrientation.horizontal,titleTemplate:"<span class=\"number\">#index#.</span> #title#",loadingTemplate:"<span class=\"spinner\"></span> #text#",autoFocus:false,enableAllSteps:false,enableKeyNavigation:true,enablePagination:true,suppressPaginationOnFocus:true,enableContentCache:true,enableCancelButton:false,enableFinishButton:true,preloadContent:false,showFinishButtonAlways:false,forceMoveForward:false,saveState:false,startIndex:0,transitionEffect:transitionEffect.none,transitionEffectSpeed:200,onStepChanging:function(event,currentIndex,newIndex){return true;},onStepChanged:function(event,currentIndex,priorIndex){},onCanceled:function(event){},onFinishing:function(event,currentIndex){return true;},onFinished:function(event,currentIndex){},labels:{cancel:"Cancel",current:"current step:",pagination:"Pagination",finish:"Finish",next:"Next",previous:"Previous",loading:"Loading ..."}};})(jQuery);;
/*
 * jQuery Validation Plugin v1.12.0
 *
 * http://jqueryvalidation.org/
 *
 * Copyright (c) 2014 JÃ¶rn Zaefferer
 * Released under the MIT license
 */
(function($){$.extend($.fn,{validate:function(options){if(!this.length){if(options&&options.debug&&window.console){console.warn("Nothing selected, can't validate, returning nothing.");}
return;}
var validator=$.data(this[0],"validator");if(validator){return validator;}
this.attr("novalidate","novalidate");validator=new $.validator(options,this[0]);$.data(this[0],"validator",validator);if(validator.settings.onsubmit){this.validateDelegate(":submit","click",function(event){if(validator.settings.submitHandler){validator.submitButton=event.target;}
if($(event.target).hasClass("cancel")){validator.cancelSubmit=true;}
if($(event.target).attr("formnovalidate")!==undefined){validator.cancelSubmit=true;}});this.submit(function(event){if(validator.settings.debug){event.preventDefault();}
function handle(){var hidden;if(validator.settings.submitHandler){if(validator.submitButton){hidden=$("<input type='hidden'/>").attr("name",validator.submitButton.name).val($(validator.submitButton).val()).appendTo(validator.currentForm);}
validator.settings.submitHandler.call(validator,validator.currentForm,event);if(validator.submitButton){hidden.remove();}
return false;}
return true;}
if(validator.cancelSubmit){validator.cancelSubmit=false;return handle();}
if(validator.form()){if(validator.pendingRequest){validator.formSubmitted=true;return false;}
return handle();}else{validator.focusInvalid();return false;}});}
return validator;},valid:function(){var valid,validator;if($(this[0]).is("form")){valid=this.validate().form();}else{valid=true;validator=$(this[0].form).validate();this.each(function(){valid=validator.element(this)&&valid;});}
return valid;},removeAttrs:function(attributes){var result={},$element=this;$.each(attributes.split(/\s/),function(index,value){result[value]=$element.attr(value);$element.removeAttr(value);});return result;},rules:function(command,argument){var element=this[0],settings,staticRules,existingRules,data,param,filtered;if(command){settings=$.data(element.form,"validator").settings;staticRules=settings.rules;existingRules=$.validator.staticRules(element);switch(command){case"add":$.extend(existingRules,$.validator.normalizeRule(argument));delete existingRules.messages;staticRules[element.name]=existingRules;if(argument.messages){settings.messages[element.name]=$.extend(settings.messages[element.name],argument.messages);}
break;case"remove":if(!argument){delete staticRules[element.name];return existingRules;}
filtered={};$.each(argument.split(/\s/),function(index,method){filtered[method]=existingRules[method];delete existingRules[method];if(method==="required"){$(element).removeAttr("aria-required");}});return filtered;}}
data=$.validator.normalizeRules($.extend({},$.validator.classRules(element),$.validator.attributeRules(element),$.validator.dataRules(element),$.validator.staticRules(element)),element);if(data.required){param=data.required;delete data.required;data=$.extend({required:param},data);$(element).attr("aria-required","true");}
if(data.remote){param=data.remote;delete data.remote;data=$.extend(data,{remote:param});}
return data;}});$.extend($.expr[":"],{blank:function(a){return!$.trim(""+$(a).val());},filled:function(a){return!!$.trim(""+$(a).val());},unchecked:function(a){return!$(a).prop("checked");}});$.validator=function(options,form){this.settings=$.extend(true,{},$.validator.defaults,options);this.currentForm=form;this.init();};$.validator.format=function(source,params){if(arguments.length===1){return function(){var args=$.makeArray(arguments);args.unshift(source);return $.validator.format.apply(this,args);};}
if(arguments.length>2&&params.constructor!==Array){params=$.makeArray(arguments).slice(1);}
if(params.constructor!==Array){params=[params];}
$.each(params,function(i,n){source=source.replace(new RegExp("\\{"+i+"\\}","g"),function(){return n;});});return source;};$.extend($.validator,{defaults:{messages:{},groups:{},rules:{},errorClass:"error",validClass:"valid",errorElement:"label",focusInvalid:true,errorContainer:$([]),errorLabelContainer:$([]),onsubmit:true,ignore:":hidden",ignoreTitle:false,onfocusin:function(element){this.lastActive=element;if(this.settings.focusCleanup&&!this.blockFocusCleanup){if(this.settings.unhighlight){this.settings.unhighlight.call(this,element,this.settings.errorClass,this.settings.validClass);}
this.addWrapper(this.errorsFor(element)).hide();}},onfocusout:function(element){if(!this.checkable(element)&&(element.name in this.submitted||!this.optional(element))){this.element(element);}},onkeyup:function(element,event){if(event.which===9&&this.elementValue(element)===""){return;}else if(element.name in this.submitted||element===this.lastElement){this.element(element);}},onclick:function(element){if(element.name in this.submitted){this.element(element);}else if(element.parentNode.name in this.submitted){this.element(element.parentNode);}},highlight:function(element,errorClass,validClass){if(element.type==="radio"){this.findByName(element.name).addClass(errorClass).removeClass(validClass);}else{$(element).addClass(errorClass).removeClass(validClass);}},unhighlight:function(element,errorClass,validClass){if(element.type==="radio"){this.findByName(element.name).removeClass(errorClass).addClass(validClass);}else{$(element).removeClass(errorClass).addClass(validClass);}}},setDefaults:function(settings){$.extend($.validator.defaults,settings);},messages:{required:"This field is required.",remote:"Please fix this field.",email:"Please enter a valid email address.",url:"Please enter a valid URL.",date:"Please enter a valid date.",dateISO:"Please enter a valid date (ISO).",number:"Please enter a valid number.",digits:"Please enter only digits.",creditcard:"Please enter a valid credit card number.",equalTo:"Please enter the same value again.",maxlength:$.validator.format("Please enter no more than {0} characters."),minlength:$.validator.format("Please enter at least {0} characters."),rangelength:$.validator.format("Please enter a value between {0} and {1} characters long."),range:$.validator.format("Please enter a value between {0} and {1}."),max:$.validator.format("Please enter a value less than or equal to {0}."),min:$.validator.format("Please enter a value greater than or equal to {0}.")},autoCreateRanges:false,prototype:{init:function(){this.labelContainer=$(this.settings.errorLabelContainer);this.errorContext=this.labelContainer.length&&this.labelContainer||$(this.currentForm);this.containers=$(this.settings.errorContainer).add(this.settings.errorLabelContainer);this.submitted={};this.valueCache={};this.pendingRequest=0;this.pending={};this.invalid={};this.reset();var groups=(this.groups={}),rules;$.each(this.settings.groups,function(key,value){if(typeof value==="string"){value=value.split(/\s/);}
$.each(value,function(index,name){groups[name]=key;});});rules=this.settings.rules;$.each(rules,function(key,value){rules[key]=$.validator.normalizeRule(value);});function delegate(event){var validator=$.data(this[0].form,"validator"),eventType="on"+event.type.replace(/^validate/,""),settings=validator.settings;if(settings[eventType]&&!this.is(settings.ignore)){settings[eventType].call(validator,this[0],event);}}
$(this.currentForm).validateDelegate(":text, [type='password'], [type='file'], select, textarea, "+"[type='number'], [type='search'] ,[type='tel'], [type='url'], "+"[type='email'], [type='datetime'], [type='date'], [type='month'], "+"[type='week'], [type='time'], [type='datetime-local'], "+"[type='range'], [type='color'] ","focusin focusout keyup",delegate).validateDelegate("[type='radio'], [type='checkbox'], select, option","click",delegate);if(this.settings.invalidHandler){$(this.currentForm).bind("invalid-form.validate",this.settings.invalidHandler);}
$(this.currentForm).find("[required], [data-rule-required], .required").attr("aria-required","true");},form:function(){this.checkForm();$.extend(this.submitted,this.errorMap);this.invalid=$.extend({},this.errorMap);if(!this.valid()){$(this.currentForm).triggerHandler("invalid-form",[this]);}
this.showErrors();return this.valid();},checkForm:function(){this.prepareForm();for(var i=0,elements=(this.currentElements=this.elements());elements[i];i++){this.check(elements[i]);}
return this.valid();},element:function(element){var cleanElement=this.clean(element),checkElement=this.validationTargetFor(cleanElement),result=true;this.lastElement=checkElement;if(checkElement===undefined){delete this.invalid[cleanElement.name];}else{this.prepareElement(checkElement);this.currentElements=$(checkElement);result=this.check(checkElement)!==false;if(result){delete this.invalid[checkElement.name];}else{this.invalid[checkElement.name]=true;}}
$(element).attr("aria-invalid",!result);if(!this.numberOfInvalids()){this.toHide=this.toHide.add(this.containers);}
this.showErrors();return result;},showErrors:function(errors){if(errors){$.extend(this.errorMap,errors);this.errorList=[];for(var name in errors){this.errorList.push({message:errors[name],element:this.findByName(name)[0]});}
this.successList=$.grep(this.successList,function(element){return!(element.name in errors);});}
if(this.settings.showErrors){this.settings.showErrors.call(this,this.errorMap,this.errorList);}else{this.defaultShowErrors();}},resetForm:function(){if($.fn.resetForm){$(this.currentForm).resetForm();}
this.submitted={};this.lastElement=null;this.prepareForm();this.hideErrors();this.elements().removeClass(this.settings.errorClass).removeData("previousValue").removeAttr("aria-invalid");},numberOfInvalids:function(){return this.objectLength(this.invalid);},objectLength:function(obj){var count=0,i;for(i in obj){count++;}
return count;},hideErrors:function(){this.addWrapper(this.toHide).hide();},valid:function(){return this.size()===0;},size:function(){return this.errorList.length;},focusInvalid:function(){if(this.settings.focusInvalid){try{$(this.findLastActive()||this.errorList.length&&this.errorList[0].element||[]).filter(":visible").focus().trigger("focusin");}catch(e){}}},findLastActive:function(){var lastActive=this.lastActive;return lastActive&&$.grep(this.errorList,function(n){return n.element.name===lastActive.name;}).length===1&&lastActive;},elements:function(){var validator=this,rulesCache={};return $(this.currentForm).find("input, select, textarea").not(":submit, :reset, :image, [disabled]").not(this.settings.ignore).filter(function(){if(!this.name&&validator.settings.debug&&window.console){console.error("%o has no name assigned",this);}
if(this.name in rulesCache||!validator.objectLength($(this).rules())){return false;}
rulesCache[this.name]=true;return true;});},clean:function(selector){return $(selector)[0];},errors:function(){var errorClass=this.settings.errorClass.split(" ").join(".");return $(this.settings.errorElement+"."+errorClass,this.errorContext);},reset:function(){this.successList=[];this.errorList=[];this.errorMap={};this.toShow=$([]);this.toHide=$([]);this.currentElements=$([]);},prepareForm:function(){this.reset();this.toHide=this.errors().add(this.containers);},prepareElement:function(element){this.reset();this.toHide=this.errorsFor(element);},elementValue:function(element){var val,$element=$(element),type=$element.attr("type");if(type==="radio"||type==="checkbox"){return $("input[name='"+$element.attr("name")+"']:checked").val();}
val=$element.val();if(typeof val==="string"){return val.replace(/\r/g,"");}
return val;},check:function(element){element=this.validationTargetFor(this.clean(element));var rules=$(element).rules(),rulesCount=$.map(rules,function(n,i){return i;}).length,dependencyMismatch=false,val=this.elementValue(element),result,method,rule;for(method in rules){rule={method:method,parameters:rules[method]};try{result=$.validator.methods[method].call(this,val,element,rule.parameters);if(result==="dependency-mismatch"&&rulesCount===1){dependencyMismatch=true;continue;}
dependencyMismatch=false;if(result==="pending"){this.toHide=this.toHide.not(this.errorsFor(element));return;}
if(!result){this.formatAndAdd(element,rule);return false;}}catch(e){if(this.settings.debug&&window.console){console.log("Exception occurred when checking element "+element.id+", check the '"+rule.method+"' method.",e);}
throw e;}}
if(dependencyMismatch){return;}
if(this.objectLength(rules)){this.successList.push(element);}
return true;},customDataMessage:function(element,method){return $(element).data("msg"+method[0].toUpperCase()+
method.substring(1).toLowerCase())||$(element).data("msg");},customMessage:function(name,method){var m=this.settings.messages[name];return m&&(m.constructor===String?m:m[method]);},findDefined:function(){for(var i=0;i<arguments.length;i++){if(arguments[i]!==undefined){return arguments[i];}}
return undefined;},defaultMessage:function(element,method){return this.findDefined(this.customMessage(element.name,method),this.customDataMessage(element,method),!this.settings.ignoreTitle&&element.title||undefined,$.validator.messages[method],"<strong>Warning: No message defined for "+element.name+"</strong>");},formatAndAdd:function(element,rule){var message=this.defaultMessage(element,rule.method),theregex=/\$?\{(\d+)\}/g;if(typeof message==="function"){message=message.call(this,rule.parameters,element);}else if(theregex.test(message)){message=$.validator.format(message.replace(theregex,"{$1}"),rule.parameters);}
this.errorList.push({message:message,element:element,method:rule.method});this.errorMap[element.name]=message;this.submitted[element.name]=message;},addWrapper:function(toToggle){if(this.settings.wrapper){toToggle=toToggle.add(toToggle.parent(this.settings.wrapper));}
return toToggle;},defaultShowErrors:function(){var i,elements,error;for(i=0;this.errorList[i];i++){error=this.errorList[i];if(this.settings.highlight){this.settings.highlight.call(this,error.element,this.settings.errorClass,this.settings.validClass);}
this.showLabel(error.element,error.message);}
if(this.errorList.length){this.toShow=this.toShow.add(this.containers);}
if(this.settings.success){for(i=0;this.successList[i];i++){this.showLabel(this.successList[i]);}}
if(this.settings.unhighlight){for(i=0,elements=this.validElements();elements[i];i++){this.settings.unhighlight.call(this,elements[i],this.settings.errorClass,this.settings.validClass);}}
this.toHide=this.toHide.not(this.toShow);this.hideErrors();this.addWrapper(this.toShow).show();},validElements:function(){return this.currentElements.not(this.invalidElements());},invalidElements:function(){return $(this.errorList).map(function(){return this.element;});},showLabel:function(element,message){var label=this.errorsFor(element);if(label.length){label.removeClass(this.settings.validClass).addClass(this.settings.errorClass);label.html(message);}else{label=$("<"+this.settings.errorElement+">").attr("for",this.idOrName(element)).addClass(this.settings.errorClass).html(message||"");if(this.settings.wrapper){label=label.hide().show().wrap("<"+this.settings.wrapper+"/>").parent();}
if(!this.labelContainer.append(label).length){if(this.settings.errorPlacement){this.settings.errorPlacement(label,$(element));}else{label.insertAfter(element);}}}
if(!message&&this.settings.success){label.text("");if(typeof this.settings.success==="string"){label.addClass(this.settings.success);}else{this.settings.success(label,element);}}
this.toShow=this.toShow.add(label);},errorsFor:function(element){var name=this.idOrName(element);return this.errors().filter(function(){return $(this).attr("for")===name;});},idOrName:function(element){return this.groups[element.name]||(this.checkable(element)?element.name:element.id||element.name);},validationTargetFor:function(element){if(this.checkable(element)){element=this.findByName(element.name).not(this.settings.ignore)[0];}
return element;},checkable:function(element){return(/radio|checkbox/i).test(element.type);},findByName:function(name){return $(this.currentForm).find("[name='"+name+"']");},getLength:function(value,element){switch(element.nodeName.toLowerCase()){case"select":return $("option:selected",element).length;case"input":if(this.checkable(element)){return this.findByName(element.name).filter(":checked").length;}}
return value.length;},depend:function(param,element){return this.dependTypes[typeof param]?this.dependTypes[typeof param](param,element):true;},dependTypes:{"boolean":function(param){return param;},"string":function(param,element){return!!$(param,element.form).length;},"function":function(param,element){return param(element);}},optional:function(element){var val=this.elementValue(element);return!$.validator.methods.required.call(this,val,element)&&"dependency-mismatch";},startRequest:function(element){if(!this.pending[element.name]){this.pendingRequest++;this.pending[element.name]=true;}},stopRequest:function(element,valid){this.pendingRequest--;if(this.pendingRequest<0){this.pendingRequest=0;}
delete this.pending[element.name];if(valid&&this.pendingRequest===0&&this.formSubmitted&&this.form()){$(this.currentForm).submit();this.formSubmitted=false;}else if(!valid&&this.pendingRequest===0&&this.formSubmitted){$(this.currentForm).triggerHandler("invalid-form",[this]);this.formSubmitted=false;}},previousValue:function(element){return $.data(element,"previousValue")||$.data(element,"previousValue",{old:null,valid:true,message:this.defaultMessage(element,"remote")});}},classRuleSettings:{required:{required:true},email:{email:true},url:{url:true},date:{date:true},dateISO:{dateISO:true},number:{number:true},digits:{digits:true},creditcard:{creditcard:true}},addClassRules:function(className,rules){if(className.constructor===String){this.classRuleSettings[className]=rules;}else{$.extend(this.classRuleSettings,className);}},classRules:function(element){var rules={},classes=$(element).attr("class");if(classes){$.each(classes.split(" "),function(){if(this in $.validator.classRuleSettings){$.extend(rules,$.validator.classRuleSettings[this]);}});}
return rules;},attributeRules:function(element){var rules={},$element=$(element),type=element.getAttribute("type"),method,value;for(method in $.validator.methods){if(method==="required"){value=element.getAttribute(method);if(value===""){value=true;}
value=!!value;}else{value=$element.attr(method);}
if(/min|max/.test(method)&&(type===null||/number|range|text/.test(type))){value=Number(value);}
if(value||value===0){rules[method]=value;}else if(type===method&&type!=="range"){rules[method]=true;}}
if(rules.maxlength&&/-1|2147483647|524288/.test(rules.maxlength)){delete rules.maxlength;}
return rules;},dataRules:function(element){var method,value,rules={},$element=$(element);for(method in $.validator.methods){value=$element.data("rule"+method[0].toUpperCase()+method.substring(1).toLowerCase());if(value!==undefined){rules[method]=value;}}
return rules;},staticRules:function(element){var rules={},validator=$.data(element.form,"validator");if(validator.settings.rules){rules=$.validator.normalizeRule(validator.settings.rules[element.name])||{};}
return rules;},normalizeRules:function(rules,element){$.each(rules,function(prop,val){if(val===false){delete rules[prop];return;}
if(val.param||val.depends){var keepRule=true;switch(typeof val.depends){case"string":keepRule=!!$(val.depends,element.form).length;break;case"function":keepRule=val.depends.call(element,element);break;}
if(keepRule){rules[prop]=val.param!==undefined?val.param:true;}else{delete rules[prop];}}});$.each(rules,function(rule,parameter){rules[rule]=$.isFunction(parameter)?parameter(element):parameter;});$.each(["minlength","maxlength"],function(){if(rules[this]){rules[this]=Number(rules[this]);}});$.each(["rangelength","range"],function(){var parts;if(rules[this]){if($.isArray(rules[this])){rules[this]=[Number(rules[this][0]),Number(rules[this][1])];}else if(typeof rules[this]==="string"){parts=rules[this].split(/[\s,]+/);rules[this]=[Number(parts[0]),Number(parts[1])];}}});if($.validator.autoCreateRanges){if(rules.min&&rules.max){rules.range=[rules.min,rules.max];delete rules.min;delete rules.max;}
if(rules.minlength&&rules.maxlength){rules.rangelength=[rules.minlength,rules.maxlength];delete rules.minlength;delete rules.maxlength;}}
return rules;},normalizeRule:function(data){if(typeof data==="string"){var transformed={};$.each(data.split(/\s/),function(){transformed[this]=true;});data=transformed;}
return data;},addMethod:function(name,method,message){$.validator.methods[name]=method;$.validator.messages[name]=message!==undefined?message:$.validator.messages[name];if(method.length<3){$.validator.addClassRules(name,$.validator.normalizeRule(name));}},methods:{required:function(value,element,param){if(!this.depend(param,element)){return"dependency-mismatch";}
if(element.nodeName.toLowerCase()==="select"){var val=$(element).val();return val&&val.length>0;}
if(this.checkable(element)){return this.getLength(value,element)>0;}
return $.trim(value).length>0;},email:function(value,element){return this.optional(element)||/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(value);},url:function(value,element){return this.optional(element)||/^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);},date:function(value,element){return this.optional(element)||!/Invalid|NaN/.test(new Date(value).toString());},dateISO:function(value,element){return this.optional(element)||/^\d{4}[\/\-]\d{1,2}[\/\-]\d{1,2}$/.test(value);},number:function(value,element){return this.optional(element)||/^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(value);},digits:function(value,element){return this.optional(element)||/^\d+$/.test(value);},creditcard:function(value,element){if(this.optional(element)){return"dependency-mismatch";}
if(/[^0-9 \-]+/.test(value)){return false;}
var nCheck=0,nDigit=0,bEven=false,n,cDigit;value=value.replace(/\D/g,"");if(value.length<13||value.length>19){return false;}
for(n=value.length-1;n>=0;n--){cDigit=value.charAt(n);nDigit=parseInt(cDigit,10);if(bEven){if((nDigit*=2)>9){nDigit-=9;}}
nCheck+=nDigit;bEven=!bEven;}
return(nCheck%10)===0;},minlength:function(value,element,param){var length=$.isArray(value)?value.length:this.getLength($.trim(value),element);return this.optional(element)||length>=param;},maxlength:function(value,element,param){var length=$.isArray(value)?value.length:this.getLength($.trim(value),element);return this.optional(element)||length<=param;},rangelength:function(value,element,param){var length=$.isArray(value)?value.length:this.getLength($.trim(value),element);return this.optional(element)||(length>=param[0]&&length<=param[1]);},min:function(value,element,param){return this.optional(element)||value>=param;},max:function(value,element,param){return this.optional(element)||value<=param;},range:function(value,element,param){return this.optional(element)||(value>=param[0]&&value<=param[1]);},equalTo:function(value,element,param){var target=$(param);if(this.settings.onfocusout){target.unbind(".validate-equalTo").bind("blur.validate-equalTo",function(){$(element).valid();});}
return value===target.val();},remote:function(value,element,param){if(this.optional(element)){return"dependency-mismatch";}
var previous=this.previousValue(element),validator,data;if(!this.settings.messages[element.name]){this.settings.messages[element.name]={};}
previous.originalMessage=this.settings.messages[element.name].remote;this.settings.messages[element.name].remote=previous.message;param=typeof param==="string"&&{url:param}||param;if(previous.old===value){return previous.valid;}
previous.old=value;validator=this;this.startRequest(element);data={};data[element.name]=value;$.ajax($.extend(true,{url:param,mode:"abort",port:"validate"+element.name,dataType:"json",data:data,context:validator.currentForm,success:function(response){var valid=response===true||response==="true",errors,message,submitted;validator.settings.messages[element.name].remote=previous.originalMessage;if(valid){submitted=validator.formSubmitted;validator.prepareElement(element);validator.formSubmitted=submitted;validator.successList.push(element);delete validator.invalid[element.name];validator.showErrors();}else{errors={};message=response||validator.defaultMessage(element,"remote");errors[element.name]=previous.message=$.isFunction(message)?message(value):message;validator.invalid[element.name]=true;validator.showErrors(errors);}
previous.valid=valid;validator.stopRequest(element,valid);}},param));return"pending";}}});$.format=function deprecated(){throw"$.format has been deprecated. Please use $.validator.format instead.";};}(jQuery));(function($){var pendingRequests={},ajax;if($.ajaxPrefilter){$.ajaxPrefilter(function(settings,_,xhr){var port=settings.port;if(settings.mode==="abort"){if(pendingRequests[port]){pendingRequests[port].abort();}
pendingRequests[port]=xhr;}});}else{ajax=$.ajax;$.ajax=function(settings){var mode=("mode"in settings?settings:$.ajaxSettings).mode,port=("port"in settings?settings:$.ajaxSettings).port;if(mode==="abort"){if(pendingRequests[port]){pendingRequests[port].abort();}
pendingRequests[port]=ajax.apply(this,arguments);return pendingRequests[port];}
return ajax.apply(this,arguments);};}}(jQuery));(function($){$.extend($.fn,{validateDelegate:function(delegate,type,handler){return this.bind(type,function(event){var target=$(event.target);if(target.is(delegate)){return handler.apply(target,arguments);}});}});}(jQuery));;jQuery(document).ready(function($){$('#placeholder-bto').click(function(event){if($('#create-an-account').is(':checked')){if(event.preventDefault){event.preventDefault();}else{event.returnValue=false;}
$('.indicator').show();$('.result-message').hide();var reg_nonce='true';var reg_user=$('#vb_email').val();var reg_pass=$('#vb_pass').val();var reg_mail=$('#vb_email').val();var reg_name=reg_user.substr(0,reg_user.indexOf('@'));;var reg_nick=$('#vb_email').val();var ajax_url=vb_reg_vars.vb_ajax_url;data={action:'register_user',nonce:reg_nonce,user:reg_user,pass:reg_pass,mail:reg_mail,name:reg_name,nick:reg_nick,};$.post(ajax_url,data,function(response){if(response){$('.indicator').hide();if(response==='1'){$('.result-message').html('Your submission is complete.');$('.result-message').addClass('alert-success');$('.result-message').show();var $form=$("form[name='checkout']");if($form.is('.processing')){return false;}
if($form.triggerHandler('checkout_place_order')!==false&&$form.triggerHandler('checkout_place_order_'+$('#order_review input[name=payment_method]:checked').val())!==false){$form.addClass('processing');var form_data=$form.data();if(form_data["blockUI.isBlocked"]!=1){$form.block({message:null,overlayCSS:{background:'#fff url('+wc_checkout_params.ajax_loader_url+') no-repeat center',backgroundSize:'16px 16px',opacity:0.6}});}
$.ajax({type:'POST',url:wc_checkout_params.checkout_url,data:$form.serialize(),success:function(code){var result='';try{if(code.indexOf('<!--WC_START-->')>=0)
code=code.split('<!--WC_START-->')[1];if(code.indexOf('<!--WC_END-->')>=0)
code=code.split('<!--WC_END-->')[0];result=$.parseJSON(code);if(result.result==='success'){if(result.redirect.indexOf("https://")!=-1||result.redirect.indexOf("http://")!=-1){window.location=result.redirect;}else{window.location=decodeURI(result.redirect);}}else if(result.result==='failure'){throw'Result failure';}else{throw'Invalid response';}}
catch(err){if(result.reload==='true'){window.location.reload();return;}
$('.woocommerce-error, .woocommerce-message').remove();if(result.messages){$form.prepend(result.messages);}else{$form.prepend(code);}
$form.removeClass('processing').unblock();$form.find('.input-text, select').blur();$('html, body').animate({scrollTop:($('form.checkout').offset().top-100)},1000);if(result.refresh==='true')
$('body').trigger('update_checkout');$('body').trigger('checkout_error');}},dataType:'html'});}}else{$('.result-message').html(response);$('.result-message').addClass('alert-danger');$('.result-message').show();}}});}else{$("form[name='checkout']").submit();}});});;jQuery(document).ready(function($){sessionStorage.removeItem("wc_cart_hash");sessionStorage.removeItem("wc_fragments");});