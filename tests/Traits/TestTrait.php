<?php

trait TestTrait {
  protected static $lockfile = '.test.lock';
  public function setUp()
  {
    parent::setUp();
    // defined in tests/tools/cleanDB.php
    cleanDB();
  }

  public static function setUpBeforeClass()
  {
    parent::setUpBeforeClass();
    self::lock();
  }

  public function getSnapPath()
  {
    $extra = date('Y-m-d-H-i') . '-' . uniqid();
    return "./tests/logs/screenshot-$extra.png";
  }

  public static function tearDownAfterClass()
  {
    parent::tearDownAfterClass();
    self::unlock();
  }

  public static function lock()
  {
    return file_put_contents( self::$lockfile, '' );
  }

  public static function unlock()
  {
    return unlink( self::$lockfile );
  }

}