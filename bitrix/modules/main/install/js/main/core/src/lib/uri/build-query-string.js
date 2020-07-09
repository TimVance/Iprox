import Type from '../type';

export default function buildQueryString(params = {})
{
	const queryString = Object.keys(params)
		.reduce((acc, key) => {
			if (Type.isArray(params[key]))
			{
				params[key].forEach((paramValue) => {
					const encodedKey = encodeURIComponent(key);
					const encodedValue = encodeURIComponent(paramValue);

					acc.push(`${encodedKey}[]=${encodedValue}`);
				}, '');
			}

			if (Type.isPlainObject(params[key]))
			{
				Object.keys(params[key]).forEach((paramIndex) => {
					const encodedKey = encodeURIComponent(key);
					const encodedIndex = encodeURIComponent(paramIndex);
					const encodedValue = encodeURIComponent(params[key][paramIndex]);

					acc.push(`${encodedKey}[${encodedIndex}]=${encodedValue}`);
				}, '');
			}

			if (!Type.isObject(params[key]) && !Type.isArray(params[key]))
			{
				const encodedKey = encodeURIComponent(key);
				const encodedValue = encodeURIComponent(params[key]);

				acc.push(`${encodedKey}=${encodedValue}`);
			}

			return acc;
		}, []).join('&');

	if (queryString.length > 0)
	{
		return `?${queryString}`;
	}

	return queryString;
}