import {Text} from '../../src/core';

describe('core/text', () => {
	describe('encode', () => {
		it('Should be exported as function', () => {
			assert(typeof Text.encode === 'function');
		});

		it('Should encode passed string with html', () => {
			let source = `Yo <div class="name">World</div>`;
			let result = 'Yo &lt;div class=&quot;name&quot;&gt;World&lt;/div&gt;';

			assert(Text.encode(source) === result);
		});

		it('Should return passed value if passed not string', () => {
			assert(Text.encode(null) === null);
			assert(Text.encode(true) === true);

			let arr = [];
			assert(Text.encode(arr) === arr);

			let obj = {};
			assert(Text.encode(obj) === obj);
		});
	});

	describe('decode', () => {
		it('Should be exported as function', () => {
			assert(typeof Text.decode === 'function');
		});

		it('Should Text.decode passed string with encoded html', () => {
			let source = 'Yo &lt;div class=&quot;name&quot;&gt;World&lt;/div&gt;';
			let result = `Yo <div class="name">World</div>`;

			assert(Text.decode(source) === result);
		});

		it('Should return passed value if passed not string', () => {
			assert(Text.decode(null) === null);
			assert(Text.decode(true) === true);

			let arr = [];
			assert(Text.decode(arr) === arr);

			let obj = {};
			assert(Text.decode(obj) === obj);
		});
	});
});