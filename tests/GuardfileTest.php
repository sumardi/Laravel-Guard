<?php namespace Way\Console;

use \Way\Console\Guardfile;
use \Mockery as m;

// mock base_path
function base_path()
{
	return '';
}

class GuardfileTest extends \PHPUnit_Framework_TestCase {
	public function tearDown()
	{
		m::close();
	}

	public function testStoresFilesystemOnInstance()
	{
		$file = m::mock('Illuminate\Filesystem\Filesystem');
		$guardFile = new Guardfile($file);

		$fileProperty = $this->makePublic($guardFile, 'file');
		$this->assertInstanceOf('Illuminate\Filesystem\Filesystem', $fileProperty->getValue($guardFile));
	}

	public function testDefaultPath()
	{
		$file = m::mock('Illuminate\Filesystem\Filesystem');
		$guardFile = new Guardfile($file);

		$pathProperty = $this->makePublic($guardFile, 'path');

		$this->assertEquals('', $pathProperty->getValue($guardFile));
	}

	public function testCanSetPathUponInstantiation()
	{
		$file = m::mock('Illuminate\Filesystem\Filesystem');
		$guardFile = new Guardfile($file, 'foo/bar');

		$pathProperty = $this->makePublic($guardFile, 'path');

		$this->assertEquals('foo/bar', $pathProperty->getValue($guardFile));
	}

	public function testGetPath()
	{
		$file = m::mock('Illuminate\Filesystem\Filesystem');
		$guardFile = new Guardfile($file);

		$this->assertEquals('/Guardfile', $guardFile->getPath());
	}

	public function testCanGetContentsOfGuardfile()
	{
		$file = m::mock('Illuminate\Filesystem\Filesystem');
		$guardFile = new Guardfile($file);

		$file->shouldReceive('get')->once()->with('/Guardfile');

		$guardFile->getContents();
	}

	public function testCanPutToGuardFile()
	{
		$file = m::mock('Illuminate\Filesystem\Filesystem');
		$guardFile = new Guardfile($file);

		$file->shouldReceive('put')->once()->with('/Guardfile', 'foo');

		$guardFile->put('foo');
	}

	protected function makePublic($obj, $property)
	{
		$reflect = new \ReflectionObject($obj);
		$property = $reflect->getProperty($property);
		$property->setAccessible(true);

		return $property;
	}

}