export default function getPrototype(value: any) {
	let proto = value;

	while (Object.getPrototypeOf(proto) !== null)
	{
		proto = Object.getPrototypeOf(proto);
	}

	return proto;
}