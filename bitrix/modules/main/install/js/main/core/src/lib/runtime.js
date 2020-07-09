import Type from './type';
import debug from './runtime/debug';
import loadExtension from './runtime/loadextension/load.extension';
import clone from './runtime/clone';
import {
	externalScripts,
	externalStyles,
	inlineScripts,
	loadAll,
} from './runtime/loadextension/lib/load.extension.utils';
import merge from './runtime/merge';

export default class Runtime
{
	static debug = debug;
	static loadExtension = loadExtension;
	static clone = clone;

	static debounce(func: Function, wait: number = 0, context = null): Function
	{
		let timeoutId;

		return function debounced(...args)
		{
			if (Type.isNumber(timeoutId))
			{
				clearTimeout(timeoutId);
			}

			timeoutId = setTimeout(() => {
				func.apply((context || this), args);
			}, wait);
		};
	}

	static throttle(func: Function, wait: number = 0, context = null): Function
	{
		let timer = 0;
		let invoke;

		return function wrapper(...args)
		{
			invoke = true;

			if (!timer)
			{
				const q = function q()
				{
					if (invoke)
					{
						func.apply((context || this), args);
						invoke = false;
						timer = setTimeout(q, wait);
					}
					else
					{
						timer = null;
					}
				};
				q();
			}
		};
	}

	static html(node: HTMLElement, html, params = {}): Promise | string
	{
		if (Type.isNil(html) && Type.isDomNode(node))
		{
			return node.innerHTML;
		}

		// eslint-disable-next-line
		const parsedHtml = BX.processHTML(html);
		const externalCss = parsedHtml.STYLE.reduce(externalStyles, []);
		const externalJs = parsedHtml.SCRIPT.reduce(externalScripts, []);
		const inlineJs = parsedHtml.SCRIPT.reduce(inlineScripts, []);

		if (Type.isDomNode(node))
		{
			if (params.htmlFirst || (!externalJs.length && !externalCss.length))
			{
				node.innerHTML = parsedHtml.HTML;
			}
		}

		return Promise
			.all([
				loadAll(externalJs),
				loadAll(externalCss),
			])
			.then(() => {
				if (Type.isDomNode(node) && (externalJs.length > 0 || externalCss.length > 0))
				{
					node.innerHTML = parsedHtml.HTML;
				}

				// eslint-disable-next-line
				inlineJs.forEach(script => BX.evalGlobal(script));

				if (Type.isFunction(params.callback))
				{
					params.callback();
				}
			});
	}

	/**
	 * Merges objects or arrays
	 * @param targets
	 * @return {any}
	 */
	static merge(...targets)
	{
		const filtered = targets.filter(Type.isObject);

		if (filtered.length < 1)
		{
			throw new Error(`${filtered[0]} is not object`);
		}

		const output = Runtime.clone(filtered.shift());
		return targets.reduce((acc, target) => merge(acc, target), output);
	}
}