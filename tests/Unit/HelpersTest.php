<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase {

	public function test_oyo_blank_works_correctly(): void {
		$this->assertTrue( oyo_blank( null ) );
		$this->assertTrue( oyo_blank( '' ) );
		$this->assertTrue( oyo_blank( '   ' ) );
		$this->assertFalse( oyo_blank( 0 ) );
		$this->assertFalse( oyo_blank( false ) );
		$this->assertTrue( oyo_blank( array() ) );
	}

	public function test_oyo_element_attr_throws_exception_when_blank_attr(): void {
		$this->expectExceptionMessage( 'A valid attr must be provided.' );
		oyo_element_attr( '<a href="test.com">', '' );
	}

	public function test_oyo_element_attr_works_correctly(): void {
		$this->assertNull(
			oyo_element_attr( '<span>test</span>', 'class' )
		);

		$this->assertNull(
			oyo_element_attr( '<span class=" ">test</span>', 'class' )
		);

		$this->assertEquals(
			oyo_element_attr( '<span class="ml-10 mr-5">test</span>', 'class' ),
			'ml-10 mr-5'
		);

		$this->assertEquals(
			oyo_element_attr( '<a href="test.com">test</a>', 'href' ),
			'test.com'
		);
	}
}
