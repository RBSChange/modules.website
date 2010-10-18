/*
 * jQuery ifixpng plugin
 * (previously known as pngfix)
 * Version 2.1  (23/04/2008)
 * @requires jQuery v1.1.3 or above
 *
 * Examples at: http://jquery.khurshid.com
 * Copyright (c) 2007 Kush M.
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 */
(function($){$.ifixpng=function(customPixel){$.ifixpng.pixel=customPixel;};$.ifixpng.getPixel=function(){return $.ifixpng.pixel||'/media/frontoffice/pixel.gif';};var hack={ltie7:$.browser.msie&&$.browser.version<7,filter:function(src){return"progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true,sizingMethod=crop,src='"+src+"')";}};$.fn.ifixpng=hack.ltie7?function(){return this.each(function(){var $$=$(this);var base=$('base').attr('href');if(base){base=base.replace(/\/[^\/]+$/,'/');}
if($$.is('img')||$$.is('input')){if($$.attr('src')){if($$.attr('src').match(/.*\.png([?].*)?$/i)){var source=(base&&$$.attr('src').search(/^(\/|http:)/i))?base+$$.attr('src'):$$.attr('src');$$.css({filter:hack.filter(source),width:$$.width(),height:$$.height()}).attr({src:$.ifixpng.getPixel()}).positionFix();}}}else{var image=$$.css('backgroundImage');if(image.match(/^url\(["']?(.*\.png([?].*)?)["']?\)$/i)){image=RegExp.$1;image=(base&&image.substring(0,1)!='/')?base+image:image;$$.css({backgroundImage:'none',filter:hack.filter(image)}).children().children().positionFix();}}});}:function(){return this;};$.fn.iunfixpng=hack.ltie7?function(){return this.each(function(){var $$=$(this);var src=$$.css('filter');if(src.match(/src=["']?(.*\.png([?].*)?)["']?/i)){src=RegExp.$1;if($$.is('img')||$$.is('input')){$$.attr({src:src}).css({filter:''});}else{$$.css({filter:'',background:'url('+src+')'});}}});}:function(){return this;};$.fn.positionFix=function(){return this.each(function(){var $$=$(this);var position=$$.css('position');if(position!='absolute'&&position!='relative'){$$.css({position:'relative'});}});};})(jQuery);