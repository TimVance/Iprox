import Type from '../type';
import Runtime from '../runtime';


export default function merge(current, target)
{
	if (Type.isObject(current) && Type.isObject(target))
	{
		return Object.keys(target).reduce((acc, key) => {
			if (Type.isObject(current[key]) && Type.isObject(target[key]))
			{
				acc[key] = merge(current[key], target[key]);
				return acc;
			}

			acc[key] = target[key];
			return acc;
		}, Runtime.clone(current));
	}
}