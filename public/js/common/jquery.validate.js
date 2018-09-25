/**
 * jQuery拡張 エラースタイル
 *
 * @param {string}
 *            message エラーメッセージ
 * @param {string}
 *            style スタイル名 [デフォルト:item-error]
 * @return {object} 自身の jQuery オブジェクト
 * @public
 * @requires jQuery.castString
 * @example $('input').errorStyle('エラーがあります', 'item-error');
 */
jQuery.fn.errorStyle = function(message, style) {
	try {
		return (this.each(function(index, dom) {
			try {
				message = jQuery.castString(message);
				if (message !== '') {
					style = jQuery.castString(style);
					if (style === '') {
						style = 'item-error';
					}
					//
					if (message!=jQuery(this).attr("has-balloontip-message")) {
						// エラースタイルを持っていない場合
						jQuery(this).addClass(style);
						if(style == 'item-error'){
							jQuery(this).balloontip(message);
						}else{
							jQuery(this).balloontip(message, 'focus');
						}
						$(this).focus();
					}
				}
			} catch (e) {
				alert('errorStyle' + e.message);
				return (false);
			}
		}));
	} catch (e) {
		alert('errorStyle' + e.message);
		return (this.each(function(index, dom) {
		}));
	}
};

/**
 * jQuery拡張 エラースタイル削除
 *
 * @param {string}
 *            style スタイル名 [デフォルト:item-error]
 * @return {object} 自身の jQuery オブジェクト
 * @public
 * @requires jQuery.castString
 * @example $('input').removeErrorStyle('item-error');
 */
jQuery.fn.removeErrorStyle = function(style) {
	try {
		return (this.each(function(index, dom) {
			try {
				style = jQuery.castString(style);
				if (style === '') {
					style = 'item-error';
				}
				//
				jQuery(this).removeClass(style).removeBalloontip();
			} catch (e) {
				alert('removeErrorStyle' + e.message);
				return (false);
			}
		}));
	} catch (e) {
		alert('removeErrorStyle' + e.message);
		return (this.each(function(index, dom) {
		}));
	}
};
/**
 * jQuery拡張 ツールチップ用
 *
 * @param {integer}
 *            _xOffset マウスからの x 軸距離
 * @private
 */
var _xOffset = 10;

/**
 * jQuery拡張 ツールチップ用
 *
 * @param {integer}
 *            _yOffset マウスからの y 軸距離
 * @private
 */
var _yOffset = 0;

/**
 * jQuery拡張 ツールチップ用
 *
 * @param {string}
 *            _balloontipId balloontip 本体のID名 (変更時は component.css も変更する)
 * @private
 */
var _balloontipId = 'has-balloontip-class';

/**
 * jQuery拡張 ツールチップ用
 *
 * @param {string}
 *            _balloontipId balloontip 内容記憶属性
 * @private
 */
var _toottipAttr = 'has-balloontip-message';


function _balloontipMousefocus(event, object, callback) {
	try {
		// 表示
		if (jQuery('#' + _balloontipId).size() > 0) {
			// 他で表示した balloontip があれば削除
			jQuery('#' + _balloontipId).remove();
		}
		var balloontipMessage = jQuery
				.castString(object.attr(_toottipAttr));
		if (balloontipMessage !== '') {
			// css強制指定 iframe 内のコンポーネントに対しても対処
			var parent = object.parent();
			//parent.css({'position' : 'relative'});
			var position  = object.position();
			jQuery('body').append(
					'<p id="' + _balloontipId + '"><span class="arrow"></span>' + balloontipMessage
					+ '</p>');

			var erroutHeight = jQuery('#' + _balloontipId).outerHeight();
			var errHeight = jQuery('#' + _balloontipId).height();
			var errlineHeight = jQuery('#' + _balloontipId).css('line-height');
			var errorgHeight = parseInt(errlineHeight)  + erroutHeight - errHeight;
			var widowWidth = $(window).width();
			var left = object.offset().left + event['target'].offsetWidth - 40;
			var ballmsgWidth =  jQuery('#' + _balloontipId).outerWidth();
			if(widowWidth < (left + ballmsgWidth)) {
				var right = widowWidth - object.offset().left - event['target'].offsetWidth;
				var css = {
					'top' : (object.offset().top-errorgHeight - 7)+ 'px',
					'right' : right + 'px',
					'position': 'absolute',
					'z-index' : '999'
				};
				jQuery('#' + _balloontipId).find('span.arrow').removeClass('arrow').addClass('arrow-right');
			} else {
				var css = {
					'top' : (object.offset().top-errorgHeight - 7)+ 'px',
					'left' : left + 'px',
					'position': 'absolute',
					'z-index' : '999'
				};
			}


			jQuery('#' + _balloontipId).css(css);

			if(callback){
				callback(jQuery('#' + _balloontipId));
			}
			jQuery('#' + _balloontipId).fadeIn(300, null);
		}
	} catch (e) {
		alert('balloontipMouseover' + e.message);
	}
}


/**
 * jQuery拡張 ツールチップ用:マウスホバー
 *
 * @param {object}
 *            event イベント
 * @param {object}
 *            object jQuery オブジェクト
 * @private
 * @requires jQuery.castString
 * @requires _balloontipId
 * @example _balloontipMouseover(event, jQuery(this));
 */

function _balloontipMouseover(event, object, callback) {
	try {
		// 表示
		if (jQuery('#' + _balloontipId).size() > 0) {
			// 他で表示した balloontip があれば削除
			jQuery('#' + _balloontipId).remove();
		}
		var balloontipMessage = jQuery
				.castString(object.attr(_toottipAttr));
		if (balloontipMessage !== '') {
			// css強制指定 iframe 内のコンポーネントに対しても対処
			var parent = object.parent();
			//parent.css({'position' : 'relative'});
			var position  = object.position();
			jQuery('body').append(
					'<p id="' + _balloontipId + '"><span class="arrow"></span>' + balloontipMessage
							+ '</p>');

			var erroutHeight = jQuery('#' + _balloontipId).outerHeight();
			var errHeight = jQuery('#' + _balloontipId).height();
			var errlineHeight = jQuery('#' + _balloontipId).css('line-height');
			var errorgHeight = parseInt(errlineHeight)  + erroutHeight - errHeight;
			//css
			var css = {
				'top' : (object.offset().top-errorgHeight-7)+ 'px',
				'left' : (object.offset().left-errorgHeight + 10)+ 'px',
				'position': 'absolute',
				'z-index' : '999'
			};

			jQuery('#' + _balloontipId).css(css);

			if(callback){
				callback(jQuery('#' + _balloontipId, parent));
			}
			jQuery('#' + _balloontipId, parent).fadeIn(300,
					null);
		}
	} catch (e) {
		alert('balloontipMouseover' + e.message);
	}
}

/**
 * jQuery拡張 ツールチップ用:マウスホバー
 *
 * @param {object}
 *            event イベント
 * @param {object}
 *            object jQuery オブジェクト
 * @private
 * @requires _balloontipId
 * @example _balloontipMouseout(event, jQuery(this));
 */
function _balloontipMouseout(event, object) {
	try {
		// 非表示 iframe 内のコンポーネントに対しても対処
		var isFocus = $(object).is(":focus");
		if(!isFocus){
			jQuery('#' + _balloontipId, object.parents('body')).remove();
		}
		_showErrorFocus();
	} catch (e) {
		alert('balloontipMouseout' + e.message);
	}
}

/**
 * jQuery拡張 ツールチップ用:マウスホバー
 *
 * @param {object}
 *            event jQuery Event オブジェクト
 * @param {object}
 *            object jQuery オブジェクト
 * @private
 * @requires _yOffset
 * @requires _xOffset
 * @example _balloontipMousemove(event, jQuery(this));
 */
function _balloontipMousemove(event, object, callback) {
	try {
		var parent = object.parent();

		var balloontip = jQuery('#' + _balloontipId, parent);
		var width = balloontip.outerWidth(true);
		var height = balloontip.outerHeight(true);
		var x = parseInt(event['pageX'], 10) + _xOffset;
		var y = parseInt(event['pageY'], 10) + _yOffset;
		var windowWidth = jQuery(window).width();
		var windowHeight = jQuery(window).height();
		var windowLeft = jQuery(window).scrollLeft();
		var windowTop = jQuery(window).scrollTop();
		var xOffset = 0;
		var yOffset = 0;
		if (x + width > windowWidth + windowLeft) {
			x = parseInt(windowWidth + windowLeft - width - _xOffset, 10);
			yOffset = -1 * height - 10;
		}
		if (y + height > windowHeight + windowTop) {
			y = parseInt(windowHeight + windowTop - height - _yOffset, 10);
		}
		//parent.css({'position' : 'relative'});
		// マウスムーブ -> balloontip をマウスに追従
		var objectOffset = object.offset();
		var position  = object.position();
		var css = {
			'left' : (x - objectOffset.left - 25 + position.left) + 'px',
		};
		// iframe 内のコンポーネントに対しても対処
		//jQuery('#' + _balloontipId, parent).css(css);
		//nguyen van bien 2014/10/30
		var erroutHeight = jQuery('#' + _balloontipId).outerHeight();
		var errHeight = jQuery('#' + _balloontipId).height();
		var errlineHeight = jQuery('#' + _balloontipId).css('line-height');
		var errorgHeight = parseInt(errlineHeight)  + erroutHeight - errHeight;
		var css = {
			'top' : (object.offset().top-errorgHeight-7)+ 'px',
			'left' : (parseInt(event['pageX'])-10)+ 'px',
			'position': 'absolute',
			'z-index' : '999'
		};

		jQuery('#' + _balloontipId).css(css);
		if(callback){
			callback(jQuery('#' + _balloontipId, parent));
		}

	} catch (e) {
		alert('balloontipMousemove' + e.message);
	}
}

/**
 * jQuery拡張 ツールチップ
 *
 * @param {string}
 *            message メッセージ ※null の場合ツールチップ削除
 * @return {object} 自身の jQuery オブジェクト
 * @public
 * @requires jQuery.castString
 * @requires _toottipAttr
 * @requires _balloontipMouseover
 * @requires _balloontipMouseout
 * @requires _balloontipMousemove
 * @example $('#sample').balloontip('ポップアップ表示');
 */
jQuery.fn.balloontip = function(message, option) {
	try {
		// message = jQuery.castHtml(message);
		// message = message.replace(/\r\n/g, '<br />').replace(/\r/g, '<br />').replace(/\n/g, '<br />');
		//
		return (this.each(function(index, dom) {
			try {
				if(option == 'focus'){
					jQuery(this).attr(_toottipAttr, message).focus(function(event) {
						if(jQuery(this).parent().find('i.fa').length > 0) {
							_balloontipMousefocus(event, jQuery(this), function(el){
								var left  = el.position().left + 17;
								el.css({'left' : left});
							});
						} else {
							_balloontipMousefocus(event, jQuery(this));
						}
					}).focusout(function(event) {
						_balloontipMouseout(event, jQuery(this));
					});

				} else {
					jQuery(this).attr(_toottipAttr, message).mouseover(function(event) {
						_balloontipMouseover(event, jQuery(this));
					}).mouseout(function(event) {
						_balloontipMouseout(event, jQuery(this));
					}).focusout(function(event) {
						_balloontipMouseout(event, jQuery(this));
					}).focus(function(event) {
						_balloontipMouseover(event, jQuery(this));
					});
				}

			} catch (e) {
				alert('balloontip' + e.message);
				return (false);
			}
		}));
	} catch (e) {
		alert('balloontip' + e.message);
		return (this.each(function(index, dom) {
		}));
	}
};


/**
 * jQuery拡張 ツールチップ削除
 *
 * @return {object} 自身の jQuery オブジェクト
 * @public
 * @requires _toottipAttr
 * @requires _balloontipMouseover
 * @requires _balloontipMouseout
 * @requires _balloontipMousemove
 * @requires _balloontipId
 * @example $('#sample').removeBalloontip();
 */
jQuery.fn.removeBalloontip = function() {
	try {
		return (this.each(function(index, dom) {
			try {
				jQuery(this).removeAttr(_toottipAttr).unbind('mouseover',
						_balloontipMouseover).unbind('focus',
						_balloontipMouseover).unbind('mouseout',
						_balloontipMouseout).unbind('focusout',
						_balloontipMouseout);
//						.unbind('mousemove',
//						_balloontipMousemove);
				jQuery('#' + _balloontipId).remove();
			} catch (e) {
				alert('removeBalloontip' + e.message);
				return (false);
			}
		}));
	} catch (e) {
		alert('removeBalloontip' + e.message);
		return (this.each(function(index, dom) {
		}));
	}
};
// ----------+[ キャスト関連 ]+----------
/**
 * 文字列へのキャスト
 *
 * @param {object}
 *            target 対象
 * @return {string} 文字列 (null / undefined の場合は空文字を返す)
 * @public
 * @example var string = $.castString(text);
 */
jQuery.castString = function(target) {
	try {
		if (target == null) {
			return ('');
		} else {
			return (target.toString());
		}
	} catch (e) {
		alert('castString' + e.message);
		return ('');
	}
};
/**
 * show tooltip error when focus
 *
 * @author		:	kyvd - 2016/08/10 - create
 * @author		:
 * @return		:	null
 * @access		:	public
 * @see			:	init
 */
function _showErrorFocus() {
	try {
		$('body').find('.item-error:focus').each(function() {
			_balloontipMouseover('', jQuery(this));
		});
	} catch (e) {
		alert('_showErrorFocus' + e.message);
	}
}
