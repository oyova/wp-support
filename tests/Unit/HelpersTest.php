<?php

namespace Tests\Unit;

use Countable;
use LogicException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class HelpersTest extends TestCase {
	/**
	 * Credit to Laravel for this function and test.
	 */
	public function test_oyo_blank() {
		$this->assertTrue( oyo_blank( null ) );
		$this->assertTrue( oyo_blank( '' ) );
		$this->assertTrue( oyo_blank( '  ' ) );
		$this->assertFalse( oyo_blank( 10 ) );
		$this->assertFalse( oyo_blank( true ) );
		$this->assertFalse( oyo_blank( false ) );
		$this->assertFalse( oyo_blank( 0 ) );
		$this->assertFalse( oyo_blank( 0.0 ) );

		$object = new SupportTestCountable();
		$this->assertTrue( oyo_blank( $object ) );
	}

	/**
	 * Credit to Laravel for this function and test.
	 */
	public function test_oyo_filled() {
		$this->assertFalse( oyo_filled( null ) );
		$this->assertFalse( oyo_filled( '' ) );
		$this->assertFalse( oyo_filled( '  ' ) );
		$this->assertTrue( oyo_filled( 10 ) );
		$this->assertTrue( oyo_filled( true ) );
		$this->assertTrue( oyo_filled( false ) );
		$this->assertTrue( oyo_filled( 0 ) );
		$this->assertTrue( oyo_filled( 0.0 ) );

		$object = new SupportTestCountable();
		$this->assertFalse( oyo_filled( $object ) );
	}

	/**
	 * Credit to Laravel for this function and test.
	 */
	public function test_throw() {
		$this->expectException( LogicException::class );

		oyo_throw_if( true, new LogicException() );
	}

	/**
	 * Credit to Laravel for this function and test.
	 */
	public function test_throw_default_exception() {
		$this->expectException( RuntimeException::class );

		oyo_throw_if( true );
	}

	/**
	 * Credit to Laravel for this function and test.
	 */
	public function test_throw_exception_with_message() {
		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'test' );

		oyo_throw_if( true, 'test' );
	}

	/**
	 * Credit to Laravel for this function and test.
	 */
	public function test_throw_exception_as_string_with_message() {
		$this->expectException( LogicException::class );
		$this->expectExceptionMessage( 'test' );

		oyo_throw_if( true, LogicException::class, 'test' );
	}

	/**
	 * Credit to Laravel for this function and test.
	 */
	public function test_oyo_throw_unless() {
		$this->expectException( LogicException::class );

		oyo_throw_unless( false, new LogicException() );
	}

	/**
	 * Credit to Laravel for this function and test.
	 */
	public function test_oyo_throw_unless_default_exception() {
		$this->expectException( RuntimeException::class );

		oyo_throw_unless( false );
	}

	/**
	 * Credit to Laravel for this function and test.
	 */
	public function test_oyo_throw_unless_exception_with_message() {
		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'test' );

		oyo_throw_unless( false, 'test' );
	}

	/**
	 * Credit to Laravel for this function and test.
	 */
	public function test_oyo_throw_unless_exception_as_string_with_message() {
		$this->expectException( LogicException::class );
		$this->expectExceptionMessage( 'test' );

		oyo_throw_unless( false, LogicException::class, 'test' );
	}

	/**
	 * Credit to Laravel for this function and test.
	 */
	public function test_throw_return_if_not_thrown() {
		$this->assertSame( 'foo', oyo_throw_unless( 'foo', new RuntimeException() ) );
	}

	/**
	 * Credit to Laravel for this function and test.
	 */
	public function test_throw_with_string() {
		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'Test Message' );

		oyo_throw_if( true, RuntimeException::class, 'Test Message' );
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

class SupportTestCountable implements Countable {
	public function count(): int {
		return 0;
	}
}
