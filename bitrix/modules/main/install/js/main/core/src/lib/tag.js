import Type from './type';
import Dom from './dom';
import Loc from './loc';
import Text from './text';
import render from './tag/render';

function parseProps(sections: string[], ...substitutions: string[]): {[key: string]: string}
{
	return substitutions
		.reduce((acc, item, index) => (
			acc + item + sections[index + 1]
		), sections[0])
		.replace(/[\r\n\t]/g, '')
		.split(';')
		.reduce((acc, item) => {
			if (item !== '')
			{
				const parsed = item.split(':');
				acc[parsed[0].trim()] = parsed[1].trim();
			}

			return acc;
		}, {});
}

export default class Tag
{
	/**
	 * Encodes all substitutions
	 * @param sections
	 * @param substitutions
	 * @return {string}
	 */
	static safe(sections: string[], ...substitutions: string[])
	{
		return substitutions.reduce((acc, item, index) => (
			acc + Text.encode(item) + sections[index + 1]
		), sections[0]);
	}

	/**
	 * Decodes all substitutions
	 * @param sections
	 * @param substitutions
	 * @return {string}
	 */
	static unsafe(sections, ...substitutions)
	{
		return substitutions.reduce((acc, item, index) => (
			acc + Text.decode(item) + sections[index + 1]
		), sections[0]);
	}

	/**
	 * Adds styles to specified element
	 * @param {HTMLElement} element
	 * @return {Function}
	 */
	static style(element: HTMLElement): Function
	{
		if (!Type.isDomNode(element))
		{
			throw new Error('element is not HTMLElement');
		}

		return function styleTagHandler(...args) {
			Dom.style(element, parseProps(...args));
		};
	}

	/**
	 * Replace all messages identifiers to real messages
	 * @param sections
	 * @param substitutions
	 * @return {string}
	 */
	static message(sections: string[], ...substitutions: string[]): string
	{
		return substitutions.reduce((acc, item, index) => (
			acc + Loc.getMessage(item) + sections[index + 1]
		), sections[0]);
	}

	static render = render;

	/**
	 * Adds attributes to specified element
	 * @param element
	 * @return {Function}
	 */
	static attrs(element: HTMLElement)
	{
		if (!Type.isDomNode(element))
		{
			throw new Error('element is not HTMLElement');
		}

		return function attrsTagHandler(...args): string {
			const props = parseProps(...args);

			Object.keys(props).forEach((key) => {
				if (props[key] === null || props === 'null')
				{
					return element.removeAttribute(key);
				}

				return element.setAttribute(key, props[key]);
			});
		};
	}
}