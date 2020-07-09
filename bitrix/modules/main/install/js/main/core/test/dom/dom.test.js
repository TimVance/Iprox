import Dom from '../../src/lib/dom';

describe('Dom', () => {
	describe('#addClass', () => {
		describe('HTMLElement', () => {
			it('Should add class for HTMLElement if passed single string name', () => {
				const element = document.createElement('div');

				Dom.addClass(element, 'test');
				assert(element.className === 'test');
			});

			it('Should add class for HTMLElement if passed multiple string name', () => {
				const element = document.createElement('div');

				Dom.addClass(element, 'test1 test2 test3');
				assert(element.className === 'test1 test2 test3');
			});

			it('Should add unique classname', () => {
				const element = document.createElement('div');

				Dom.addClass(element, 'test1 test2 test3');
				assert(element.className === 'test1 test2 test3');

				Dom.addClass(element, 'test1');
				assert(element.className === 'test1 test2 test3');

				Dom.addClass(element, 'test1 test2');
				assert(element.className === 'test1 test2 test3');
			});

			it('Should add array of names', () => {
				const element = document.createElement('div');

				Dom.addClass(element, ['test1', 'test2', 'test3']);
				assert(element.className === 'test1 test2 test3');
			});
		});

		describe('SVG', () => {
			it('Should add class for SVG if passed single string name', () => {
				const element = document.createElementNS('http://www.w3.org/2000/svg', 'div');

				Dom.addClass(element, 'test');
				assert(element.getAttribute('class') === 'test');
			});

			it('Should add class for SVG if passed multiple string name', () => {
				const element = document.createElementNS('http://www.w3.org/2000/svg', 'div');

				Dom.addClass(element, 'test1 test2 test3');
				assert(element.getAttribute('class') === 'test1 test2 test3');
			});

			it('Should add unique classname', () => {
				const element = document.createElementNS('http://www.w3.org/2000/svg', 'div');

				Dom.addClass(element, 'test1 test2 test3');
				assert(element.getAttribute('class') === 'test1 test2 test3');

				Dom.addClass(element, 'test1');
				assert(element.getAttribute('class') === 'test1 test2 test3');

				Dom.addClass(element, 'test1 test2');
				assert(element.getAttribute('class') === 'test1 test2 test3');
			});

			it('Should add array of names', () => {
				const element = document.createElementNS('http://www.w3.org/2000/svg', 'div');

				Dom.addClass(element, ['test1', 'test2', 'test3']);
				assert(element.getAttribute('class') === 'test1 test2 test3');
			});
		});
	});

	describe('#hasClass', () => {
		describe('HTML', () => {
			it('Should return true if element className includes passed name', () => {
				const element = document.createElement('div');
				element.className = 'test1 test2 test3';

				assert(Dom.hasClass(element, 'test1'));
				assert(Dom.hasClass(element, 'test2'));
				assert(Dom.hasClass(element, 'test3'));
			});

			it('Should return true if element className includes passed multiple name string', () => {
				const element = document.createElement('div');
				element.className = 'test1 test2 test3';

				assert(Dom.hasClass(element, 'test1 test2'));
				assert(Dom.hasClass(element, 'test1 test3'));
				assert(Dom.hasClass(element, 'test3 test1'));
			});

			it('Should return true if element className includes all names from passed names array', () => {
				const element = document.createElement('div');
				element.className = 'test1 test2 test3';

				assert(Dom.hasClass(element, ['test1', 'test2']));
				assert(Dom.hasClass(element, ['test1', 'test3']));
				assert(Dom.hasClass(element, ['test3', 'test1']));
			});

			it('Should return false if element className not includes all names from passed string', () => {
				const element = document.createElement('div');
				element.className = 'test1 test2 test3';

				assert(!Dom.hasClass(element, 'test1 test2 test33'));
				assert(!Dom.hasClass(element, 'test1 test3 te'));
				assert(!Dom.hasClass(element, 'test3 test222 test1'));
			});
		});

		describe('SVG', () => {
			it('Should return true if element className includes passed name', () => {
				const element = document.createElementNS('http://www.w3.org/2000/svg', 'div');
				element.setAttribute('class', 'test1 test2 test3');

				assert(Dom.hasClass(element, 'test1'));
				assert(Dom.hasClass(element, 'test2'));
				assert(Dom.hasClass(element, 'test3'));
			});

			it('Should return true if element className includes passed multiple name string', () => {
				const element = document.createElementNS('http://www.w3.org/2000/svg', 'div');
				element.setAttribute('class', 'test1 test2 test3');

				assert(Dom.hasClass(element, 'test1 test2'));
				assert(Dom.hasClass(element, 'test1 test3'));
				assert(Dom.hasClass(element, 'test3 test1'));
			});

			it('Should return true if element className includes all names from passed names array', () => {
				const element = document.createElementNS('http://www.w3.org/2000/svg', 'div');
				element.setAttribute('class', 'test1 test2 test3');

				assert(Dom.hasClass(element, ['test1', 'test2']));
				assert(Dom.hasClass(element, ['test1', 'test3']));
				assert(Dom.hasClass(element, ['test3', 'test1']));
			});

			it('Should return false if element className not includes all names from passed string', () => {
				const element = document.createElementNS('http://www.w3.org/2000/svg', 'div');
				element.setAttribute('class', 'test1 test2 test3');

				assert(!Dom.hasClass(element, 'test1 test2 test33'));
				assert(!Dom.hasClass(element, 'test1 test3 te'));
				assert(!Dom.hasClass(element, 'test3 test222 test1'));
			});
		});
	});

	describe('#removeClass', () => {
		describe('HTML', () => {
			it('Should remove passed class name', () => {
				const element = document.createElement('div');
				element.className = 'test1 test2 test3';

				Dom.removeClass(element, 'test1');
				assert(element.className === 'test2 test3');

				Dom.removeClass(element, 'test3');
				assert(element.className === 'test2');

				Dom.removeClass(element, 'test2');
				assert(element.className === '');

				Dom.removeClass(element, 'test222');
				assert(element.className === '');
			});

			it('Should remove all names from string', () => {
				const element = document.createElement('div');
				element.className = 'test1 test2 test3';

				Dom.removeClass(element, 'test1 test3');
				assert(element.className === 'test2');
			});

			it('Should remove all names from names array', () => {
				const element = document.createElement('div');
				element.className = 'test1 test2 test3';

				Dom.removeClass(element, ['test2', 'test3']);
				assert(element.className === 'test1');

				Dom.removeClass(element, ['test1']);
				assert(element.className === '');
			});
		});

		describe('SVG', () => {
			it('Should remove passed name', () => {
				const element = document.createElementNS('http://www.w3.org/2000/svg', 'div');
				element.setAttribute('class', 'test1 test2 test3');

				Dom.removeClass(element, 'test1');
				assert(element.getAttribute('class') === 'test2 test3');

				Dom.removeClass(element, 'test3');
				assert(element.getAttribute('class') === 'test2');

				Dom.removeClass(element, 'test2');
				assert(element.getAttribute('class') === '');
			});

			it('Should remove all names from passed string', () => {
				const element = document.createElementNS('http://www.w3.org/2000/svg', 'div');
				element.setAttribute('class', 'test1 test2 test3');

				Dom.removeClass(element, 'test1 test2');
				assert(element.getAttribute('class') === 'test3');
			});

			it('Should remove all names from names array', () => {
				const element = document.createElementNS('http://www.w3.org/2000/svg', 'div');
				element.setAttribute('class', 'test1 test2 test3');

				Dom.removeClass(element, ['test2', 'test3']);
				assert(element.getAttribute('class') === 'test1');

				Dom.removeClass(element, ['test1']);
				assert(element.getAttribute('class') === '');
			});
		});
	});

	describe('#create', () => {
		it('Should create div', () => {
			const element = Dom.create('div');
			const result = document.createElement('div');

			assert.deepEqual(element, result);
		});

		it('Should create div with class', () => {
			const element = Dom.create('div', {
				props: {
					className: 'test'
				}
			});
			const result = document.createElement('div');
			result.classList.add('test');

			assert.deepEqual(element, result);
		});

		it('Should create div with classes', () => {
			const element = Dom.create('div', {
				props: {
					className: 'test test2'
				}
			});
			const result = document.createElement('div');
			result.classList.add('test');
			result.classList.add('test2');

			assert.deepEqual(element, result);
		});

		it('Should create div with children as string', () => {
			const element = Dom.create('div', {
				children: 'test string'
			});
			const result = document.createElement('div');
			result.innerHTML = 'test string';

			assert.deepEqual(element, result);
		});

		it('Should create div with children array', () => {
			const element = Dom.create('div', {
				children: [
					'test string'
				]
			});
			const result = document.createElement('div');
			result.innerHTML = 'test string';

			assert.deepEqual(element, result);
		});

		it('Should create from object options', () => {
			const element = Dom.create({
				tag: 'div',
			});

			const result = document.createElement('div');

			assert.deepEqual(element, result);
		});

		it('Should create from object with tag uppercase', () => {
			const element = Dom.create({
				tag: 'DIV',
			});

			const result = document.createElement('div');

			assert.deepEqual(element, result);
		});
	});

	describe('#test', () => {
		it('Should return computed property value', () => {
			const element = document.createElement('div');

			assert.ok(Dom.style(element, 'display') === 'block');

			element.style.setProperty('width', '10px');
			assert.ok(Dom.style(element, 'width') === '10px');

			element.style.setProperty('height', '10px');
			assert.ok(Dom.style(element, 'height') === '10px');
		});

		it('Should set multiple props values from object', () => {
			const element = document.createElement('div');
			const props = {
				display: 'inline',
				width: '11px',
				position: 'fixed',
			};

			Dom.style(element, props);

			assert.ok(Dom.style(element, 'display') === props.display);
			assert.ok(Dom.style(element, 'width') === props.width);
			assert.ok(Dom.style(element, 'position') === props.position);
		});

		describe('compatibility tests', () => {
			it('Should return element if passed value', () => {
				const element = document.createElement('div');

				assert.ok(Dom.style(element, 'display', 'none') === element);
				assert.ok(Dom.style(element, 'display', '') === element);
				assert.ok(Dom.style(element, 'width', '100px') === element);
			});
		});
	});
});