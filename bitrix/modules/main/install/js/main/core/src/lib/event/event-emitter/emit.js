import BaseEvent from '../base-event';
import {privateProps} from './private-stores';

export default function emit(context, eventName: any, event: BaseEvent)
{
	const {eventsMap} = privateProps.get(context);

	if (eventsMap.has(eventName))
	{
		eventsMap.get(eventName)
			.forEach((listener) => {
				listener(event);
			});
	}
}