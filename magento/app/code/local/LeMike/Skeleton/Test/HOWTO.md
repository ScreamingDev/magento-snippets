# Unit-Test in Magento

UnitTest using LeMike_Skeleton

<small>
    Created by [Mike Pretzlaw](http://mike-pretzlaw.de) / [@fxrmp](http://twitter.com/fxrmp)
</small>


## What is this?

- Introduction to UnitTest
- Usage of EcomDev_UnitTest for Magento
- Usage of LeMike_Skeleton_Test as a helper

<aside class="notes">
    Codex_UnitTest contains helping classes for easily creating UnitTests in Magento.
    You'll need Magento itself and EcomDev_UnitTest to use these.
</aside>



## Introduction to UnitTest

- Modules can be easily tested
- Almost simple algorithms
- Only few tests to full validation

<aside class="notes">
    Testing software can be done module by module which is known as UnitTest.
    This devides the whole application in tiny simple parts that only has to pass a few tests for being a complete
    valid extension.
</aside>


- Blackbox: Test internals (data structure / -types)
- Whitebox: Test usage (
- Edge: Test limiting conditions

<aside class="notes">
    The developer can test his algorithms in the blackbox which means that he looks what happens inside the code
    if some prequesites change.
    In addition to that the whitebox test only validates the user-input and method-output to serve several common
    usages.
    At last the limiting conditions have to be tested and the correct behaviour on bypassing these limits have to be
    assured.
</aside>


**UnitTests are isolated** - they do not interferre.

**Design-by-Contract is optional** for faster development process.

PHPUnit gives the opportunity for **semi-automated tests** to assure the already tested functionality.
<aside class="notes">
    Because only tested componenty and use cases can be validated.
    A test is never complete and just ensures that new code didn't harm the old one.
    Never the less automated testing brings a great effort to check this at any time very fast.
</aside>


### Blackbox test

```php
function testBlackbox() {
    $class = new ReflectionClass('Some_Class');
    $protected = $class->getProperty('_someProtected');
    $protected->setAccessible(true);

    $this->assertInternalType(
        'string',
        $protected->getValue(new Some_Class)
    );
}
```

```php
function testSelf() {
  // test the test
}
```


### Whitebox test

```php
function testWhitebox() {
    $frontend = new Some_Class();

    // data type of the class itself
    $methods = get_class_methods('Some_Class');
    $this->assertContains('getFoo', $methods);
    $this->assertClassHasAttribute('attrFoo', 'Some_Class');

    // return data types
    $this->assertInternalType('string', $frontend->getFoo());
}
```

```php
function testGetFoo() {
    $frontend = new Some_Class();

    // different cases
    $this->assertEquals('Hello!', $frontend->getFoo());
    $this->assertEquals('Hello World!', $frontend->getFoo('World'));
}
```

<aside class="notes">
    You can start making general tests in a testWhitebox method or break them down to test methods according to
    each method of the class.
    Every method can have its own testing class having a blackbox, whitebox and case-test.
    Test Cases will be grouped to Test Suites.
</aside>


### Test strategies

<aside class="notes">
    aus dem buch!
</aside>



## Usage of EcomDev_UnitTest for Magento

UnitTest made for Magento using PHPUnit.

For more information see https://github.com/EcomDev/EcomDev_PHPUnit.


### Controller Test


###
