<?php

use tests\TestCase;
use fifo\Fifo;

class FifoTest extends TestCase
{
    function testStartsEmpty() {
        $fifo = new Fifo(1000);
        $this->assertNull($fifo->get(1));
    }

    function testGet() {
        $fifo = new Fifo(1000);
        $key = 'key1';
        $data = 'content for key1';
        $fifo->put($key, $data);
        $this->assertEquals($fifo->get($key), $data);
    }

    function testMultipleGet() {
        $fifo = new Fifo(1000);
        $key = 'key1';
        $data = 'content for key1';
        $key2 = 'key2';
        $data2 = 'content for key2';
        $fifo->put($key, $data);
        $fifo->put($key2, $data2);
        $this->assertEquals($fifo->get($key), $data);
        $this->assertEquals($fifo->get($key2), $data2);
    }

    function testPut() {
        $fifo = new Fifo(1000);
        $key1 = 'mykey1';
        $value1 = 'myvaluefromkey1';
        $fifo->put($key1, $value1);
        $this->assertEquals($fifo->get($key1), $value1);
    }

    function testMassivePut() {
        $numEntries = 90000;
        $fifo = new Fifo($numEntries);
        while($numEntries > 0) {
            $fifo->put($numEntries - 899999, 'some value...');
            $numEntries--;
        }
    }

    function testRemove() {
        $fifo = new Fifo(1000);
        $fifo->put('key1', 'value1');
        $fifo->put('key2', 'value2');
        $fifo->put('key3', 'value3');
        $ret = $fifo->remove('key2');
        $this->assertTrue($ret);
        $this->assertNull($fifo->get('key2'));
        // test remove of already removed key
        $ret = $fifo->remove('key2');
        $this->assertFalse($ret);
        // make sure no side effects took place
        $this->assertEquals($fifo->get('key1'), 'value1');
        $this->assertEquals($fifo->get('key3'), 'value3');
    }

    function testPutWhenFull() {
        $fifo = new Fifo(3);
        $key1 = 'key1';
        $value1 = 'value1forkey1';
        $key2 = 'key2';
        $value2 = 'value2forkey2';
        $key3 = 'key3';
        $value3 = 'value3forkey3';
        $key4 = 'key4';
        $value4 = 'value4forkey4';
        // fill the cache
        $fifo->put($key1, $value1);
        $fifo->put($key2, $value2);
        $fifo->put($key3, $value3);
        // access some elements more often
        $fifo->get($key2);
        $fifo->get($key2);
        $fifo->get($key3);
        // put a new entry to force cache to discard the oldest
        $fifo->put($key4, $value4);
        $this->assertNull($fifo->get($key1));
    }
}
