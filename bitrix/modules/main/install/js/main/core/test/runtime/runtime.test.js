import Runtime from '../../src/lib/runtime';

describe('Runtime', () => {
	it('Should be exported as function', () => {
		assert(typeof Runtime === 'function');
	});

	describe('#merge', () => {
		it('Should be a function', () => {
			assert(typeof Runtime.merge === 'function');
		});

		it('Should merge 2 simple objects', () => {
			const source1 = {prop1: 1, prop2: 2};
			const source2 = {prop1: 99, prop3: 3};
			const result = Runtime.merge(source1, source2);

			assert.equal(Object.keys(result).length, 3);
			assert.equal(result.prop1, 99);
			assert.equal(result.prop2, 2);
			assert.equal(result.prop3, 3);
		});

		it('Should merge more than 2 objects', () => {
			const source1 = {prop1: 1, prop2: 2};
			const source2 = {prop1: 99, prop3: 3};
			const source3 = {prop1: 100, prop4: 4};
			const source4 = {prop2: 222, prop5: 5};

			const result = Runtime.merge(source1, source2, source3, source4);

			assert.equal(Object.keys(result).length, 5);
			assert.equal(result.prop1, 100);
			assert.equal(result.prop2, 222);
			assert.equal(result.prop3, 3);
			assert.equal(result.prop4, 4);
			assert.equal(result.prop5, 5);
		});

		it('Should not modify merging objects', () => {
			const source1 = {prop1: 1, prop2: 2};
			const source1Clone = {...source1};
			const source2 = {prop1: 99, prop3: 3};
			const source2Clone = {...source2};
			const source3 = {prop1: 100, prop4: 4};
			const source3Clone = {...source3};
			const source4 = {prop2: 222, prop5: 5};
			const source4Clone = {...source4};

			void Runtime.merge(source1, source2, source3, source4);

			assert.deepEqual(source1, source1Clone);
			assert.deepEqual(source2, source2Clone);
			assert.deepEqual(source3, source3Clone);
			assert.deepEqual(source4, source4Clone);
		});

		it('Should merge child objects', () => {
			const source1 = {
				prop1: 1,
				prop2: 2,
				propA: [1, 2, 3],
				child: {
					prop11: 11,
					prop22: 22,
					prop99: {
						prop111: 111,
					}
				},
			};

			const source2 = {
				prop1: 1,
				prop2: 2,
				propA: [1, 2, 4, 5],
				child: {
					prop11: 11,
					prop22: 2222,
					prop33: 33,
					prop99: {
						prop222: 222,
					}
				},
			};

			const result = {
				prop1: 1,
				prop2: 2,
				propA: [1, 2, 4, 5],
				child: {
					prop11: 11,
					prop22: 2222,
					prop33: 33,
					prop99: {
						prop111: 111,
						prop222: 222,
					}
				},
			};

			const merged = Runtime.merge(source1, source2);

			assert.deepEqual(merged, result);
		});

		it('Should merge arrays', () => {
			const source1 = [1, 2, 3, 4];
			const source2 = [9,,,7];

			const result = [9, 2, 3, 7];
			const merged = Runtime.merge(source1, source2);

			assert.deepEqual(merged, result);
		});

		it('Should deep merge array of objects', () => {
			const source1 = [{test1: 1}, {test2: 2}];
			const source2 = [{test1: 99}, {test3: 3}];

			const result = [{test1: 99}, {test2: 2, test3: 3}];
			const merged = Runtime.merge(source1, source2);

			assert.deepEqual(merged, result);
		});

		it('Should throw if passed bad params', () => {
			assert.throws(() => {
				Runtime.merge('', '');
			});

			assert.throws(() => {
				Runtime.merge(1);
			});

			assert.throws(() => {
				Runtime.merge(1, 2);
			});
		});
	});

	describe('#clone', () => {
		it('Should be a function', () => {
			assert(typeof Runtime.clone === 'function');
		});

		it('Should clone plain object', () => {
			const source = {
				prop1: 'value1',
				prop2: 'value2',
				prop3: ['1', '2', '3']
			};
			const clone = Runtime.clone(source);

			assert.deepEqual(source, clone);
			assert(clone !== source);
		});

		it('Should clone plain object with circular reference', () => {
			const source = {
				prop1: 'value1',
				prop2: 'value2',
				prop3: ['1', '2', '3'],
				prop4: source,
			};
			const clone = Runtime.clone(source);

			assert.deepEqual(source, clone);
			assert(clone !== source);
		});

		it('Should clone array', () => {
			const source = [1, 2, 3, 4];
			const clone = Runtime.clone(source);

			assert.deepEqual(source, clone);
			assert(clone !== source);
		});

		it('Should clone array with circular reference', () => {
			const source = [1, 2, 3, 4, source];
			const clone = Runtime.clone(source);

			assert.deepEqual(source, clone);
			assert(clone !== source);
		});

		it('Should clone typed object', () => {
			class MyClass2 {}
			class MyClass extends MyClass2 {
				constructor() {
					super();
					this.prop1 = 1;
					this.prop2 = 2;
				}
			}

			const source = new MyClass();
			const clone = Runtime.clone(source);

			assert.deepEqual(source, clone);
			assert(clone !== source);
			assert(clone instanceof MyClass);
			assert(clone instanceof MyClass2);
		});

		it('Should clone typed object', () => {
			function MyClass() {
				this.prop1 = 1;
			}

			MyClass.prototype.test = function() {};

			const source = new MyClass();
			const clone = Runtime.clone(source);

			assert.deepEqual(source, clone);
			assert(clone !== source);
		});

		it('Should clone named object without constructor property in prototype', () => {
			function MyClass() {}
			MyClass.prototype = {
				getName() {
					return 'MyClass';
				}
			};

			const source = new MyClass();
			const clone = Runtime.clone(source);

			assert.deepEqual(source, clone);
			assert(source !== clone);
			assert(source instanceof MyClass);
			assert(clone instanceof MyClass);
		});
	});
});