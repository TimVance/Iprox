import Type from './lib/type';
import Reflection from './lib/reflection';
import Dom from './lib/dom';
import Browser from './lib/browser';
import Event from './lib/event';
import Http from './lib/http';
import Runtime from './lib/runtime';
import messageFunction from './lib/loc/message';
import * as debugNs from './lib/runtime/debug';
import {isReady} from './lib/event/ready';
import getElement from './internal/get-element';

// BX.*
export const {getClass, namespace} = Reflection;
export const message = messageFunction;

export const {
	replace,
	remove,
	clean,
	insertBefore,
	insertAfter,
	append,
	prepend,
	style,
	adjust,
	create,
	isShown,
} = Dom;

export const addClass = (...args) => {
	const preparedArgs = [...args];
	preparedArgs[0] = getElement(args[0]);
	Dom.addClass(...preparedArgs);
};

export const removeClass = (...args) => {
	const preparedArgs = [...args];
	preparedArgs[0] = getElement(args[0]);
	Dom.removeClass(...preparedArgs);
};

export const hasClass = (...args) => {
	const preparedArgs = [...args];
	preparedArgs[0] = getElement(args[0]);
	return Dom.hasClass(...preparedArgs);
};

export const toggleClass = (...args) => {
	const preparedArgs = [...args];
	preparedArgs[0] = getElement(args[0]);
	Dom.toggleClass(...preparedArgs);
};

export const cleanNode = (element, removeElement = false) => {
	let currentElement = element;

	if (Type.isString(element))
	{
		currentElement = document.getElementById(element);
	}

	if (Type.isDomNode(currentElement))
	{
		Dom.clean(currentElement);

		if (removeElement)
		{
			Dom.remove(currentElement);
			return currentElement;
		}
	}

	return currentElement;
};

export const getCookie = Http.Cookie.get;
export const setCookie = (name, value, options = {}) => {
	const attributes = {...options};

	if (Type.isNumber(attributes.expires))
	{
		attributes.expires /= (3600 * 24);
	}

	Http.Cookie.set(name, value, options);
};

export const {
	bind,
	unbind,
	unbindAll,
	bindOnce,
	ready,
} = Event;

export {isReady};
export const {
	debugState: debugEnableFlag,
	isDebugEnabled: debugStatus,
	default: debug,
} = debugNs;

export const debugEnable = (value) => {
	if (value)
	{
		debugNs.enableDebug();
	}
	else
	{
		debugNs.disableDebug();
	}
};

export const {
	clone,
	loadExtension: loadExt,
	debounce,
	throttle,
	html,
} = Runtime;

// BX.type
export const type = {
	isString: Type.isString,
	isFunction: Type.isFunction,
	isObject: Type.isObject,
	isObjectLike: Type.isObjectLike,
	isPlainObject: Type.isPlainObject,
	isBoolean: Type.isBoolean,
	isNumber: Type.isNumber,
	isInteger: Type.isInteger,
	isFloat: Type.isFloat,
	isNil: Type.isNil,
	isArray: Type.isArray,
	isArrayLike: Type.isArrayLike,
	isDate: Type.isDate,
	isDomNode: Type.isDomNode,
	isElementNode: Type.isElementNode,
	isTextNode: Type.isTextNode,
	isMap: Type.isMap,
	isSet: Type.isSet,
	isWeakMap: Type.isWeakMap,
	isWeakSet: Type.isWeakSet,
	isPrototype: Type.isPrototype,
	isRegExp: Type.isRegExp,
	isNull: Type.isNull,
	isUndefined: Type.isUndefined,
	isArrayBuffer: Type.isArrayBuffer,
	isTypedArray: Type.isTypedArray,
	isNotEmptyString: value => Type.isString(value) && value !== '',
	isNotEmptyObject: value => Type.isObjectLike(value) && Object.keys(value).length > 0,
	isMapKey: Type.isObject,
	stringToInt: (value) => {
		const parsed = parseInt(value);
		return !Number.isNaN(parsed) ? parsed : 0;
	},
};

// BX.browser
export const browser = {
	IsOpera: Browser.isOpera,
	IsIE: Browser.isIE,
	IsIE6: Browser.isIE6,
	IsIE7: Browser.isIE7,
	IsIE8: Browser.isIE8,
	IsIE9: Browser.isIE9,
	IsIE10: Browser.isIE10,
	IsIE11: Browser.isIE11,
	IsSafari: Browser.isSafari,
	IsFirefox: Browser.isFirefox,
	IsChrome: Browser.isChrome,
	DetectIeVersion: Browser.detectIEVersion,
	IsMac: Browser.isMac,
	IsAndroid: Browser.isAndroid,
	isIPad: Browser.isIPad,
	isIPhone: Browser.isIPhone,
	IsIOS: Browser.isIOS,
	IsMobile: Browser.isMobile,
	isRetina: Browser.isRetina,
	IsDoctype: Browser.isDoctype,
	SupportLocalStorage: Browser.isLocalStorageSupported,
	addGlobalClass: Browser.addGlobalClass,
	DetectAndroidVersion: Browser.detectAndroidVersion,
	isPropertySupported: Browser.isPropertySupported,
	addGlobalFeatures: Browser.addGlobalFeatures,
};

// eslint-disable-next-line
const ajax = window.BX ? window.BX.ajax : () => {};
export {ajax};