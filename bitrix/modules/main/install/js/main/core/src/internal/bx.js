var bxTmp = window.BX;

window.BX = function(node)
{
	if (BX.type.isNotEmptyString(node))
	{
		return document.getElementById(node);
	}

	if (BX.type.isDomNode(node))
	{
		return node;
	}

	if (BX.type.isFunction(node))
	{
		return BX.ready(node);
	}

	return null;
};

if (bxTmp)
{
	Object.keys(bxTmp).forEach((key) => {
		window.BX[key] = bxTmp[key];
	});
}

exports = window.BX;