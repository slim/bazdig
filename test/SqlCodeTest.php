<?php

require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');

class SqlCodeTest extends UnitTestCase {

    function setUp()
    {
        require_once '../lib/code.php';
		$this->query = 'select * from test';
		$this->md5 = md5($this->query);
		$this->sqlCode = new SqlCode($this->query);
		$this->db =& new PDO("sqlite:bazdig-test.db");
		SqlCode::set_db($this->db);
		$this->sqlCode2 = new SqlCode("select * from test");
		$this->sqlCode2->id = 'test:001';
		$this->sqlCode2->date = '2008-01-25';
		$this->markkitDB =& new PDO("sqlite2:markkit-test.db");
		$this->markkitQuery3 = new SqlCode("select * from marks where pageUrl like '%x'");
		$this->markkitQuery4 = new SqlCode("create table marks (pageUrl, text)");
		$this->sqlCode3 = new SqlCode('
-- test comment
--
select * from test3
');
    }

    function tearDown()
    {
		$tableName = SqlCode::get_table_name();
		$id = $this->sqlCode->id;
		$this->db->exec("delete from $tableName where id='$id'");
		$id = $this->markkitQuery3->id;
		$this->db->exec("delete from $tableName where id='$id'");
    }

	function test_get_title()
	{
		$result = $this->sqlCode3->get_title();
		$expected = 'test comment';
		$this->assertEqual($expected, $result);
		$result = $this->sqlCode->get_title();
		$this->assertFalse($result);
	}

	function test_extractColumns()
	{
		$result = firstWord('  test  test2 test3 ');
		$expected = 'test';
		$this->assertEqual($expected, $result);
		$result = firstWord('test');
		$this->assertEqual($expected, $result);
		$result = $this->markkitQuery4->extractColumns();
		$expected = 2;
		$this->assertEqual($expected, count($result));
		$expected = array(new Column('pageUrl'), new Column('text'));
		$this->assertEqual($expected, $result);
	}

	function test_search()
	{
		$result = SqlCode::search("test");
		$expected = 2;
        $this->assertEqual($expected, count($result));
		$expected = $this->sqlCode2;
		$this->assertTrue($expected == $result[0] || $expected == $result[1]);
		$result = SqlCode::search("test", "limit 1");
		$expected = 1;
        $this->assertEqual($expected, count($result));
		$result = SqlCode::search("test", "where id='test:005'");
		$expected = 1;
        $this->assertEqual($expected, count($result));
	}

	function test_exec()
	{
		$markkitQuery1 = new SqlCode("select * from marks where id='testid'");
		$result = $markkitQuery1->exec($this->markkitDB)->fetchAll();
		$expected = 'testuser';
        $this->assertEqual($expected, $result[0]['owner']);
		$markkitQuery2 = new SqlCode("select * from marks where id='testid4'");
		$result = $markkitQuery2->exec($this->markkitDB)->fetchAll();
		$expected = 'http://localhost/test/x';
        $this->assertEqual($expected, $result[0]['pageUrl']);
		$result = $this->markkitQuery3->exec($this->markkitDB)->fetchAll();
		$expected = 'http://localhost/test/x';
        $this->assertEqual($expected, $result[1]['pageUrl']);
	}

    function test_toSQLinsert()
    {
        $result   = $this->sqlCode->toSQLinsert();
        $expected = "/insert/i";
        $this->assertWantedPattern($expected, $result);
        $expected = "/'md5:".$this->md5."'/";
        $this->assertWantedPattern($expected, $result);
    }

    function test_toSQLselect()
    {
        $result   = $this->sqlCode->toSQLselect();
        $expected = "/select/i";
        $this->assertWantedPattern($expected, $result);
        $expected = "/id='md5:".$this->md5."'/";
        $this->assertWantedPattern($expected, $result);
    }

    function test_sql_select()
    {
		$options = " where code like 'select %'";
        $result   = SqlCode::sql_select($options);
        $expected = "/$options/i";
        $this->assertWantedPattern($expected, $result);
        $expected = "/select.+id.+from/i";
        $this->assertWantedPattern($expected, $result);
        $expected = "/select.+code.+from/i";
        $this->assertWantedPattern($expected, $result);
        $expected = "/select.+date.+from/i";
        $this->assertWantedPattern($expected, $result);
    }

    function test_select()
    {
		$result = SqlCode::select(" where id='test:001'");
		$expected = array($this->sqlCode2);
        $this->assertEqual($expected, $result);
    }

    function test_save()
    {
		$this->sqlCode->save();
		$md5 = $this->sqlCode->id;
		$result = $this->db->query("select code from sql where id='$md5'");
		$rows = $result->fetchAll(PDO::FETCH_ASSOC);
		$count = count($rows);
		$expected = 1;
        $this->assertEqual($expected, $count);
		$expected = "select * from test";
        $this->assertEqual($expected, $rows[0]['code']);
		$this->sqlCode->save(); // save a second time
		$result = $this->db->query("select * from sql where id='$md5'");
		$rows = $result->fetchAll(PDO::FETCH_ASSOC);
		$count = count($rows);
		$expected = 1;
        $this->assertEqual($expected, $count);
		$this->markkitQuery3->save();
		$id = $this->markkitQuery3->id;
		$result = $this->db->query("select * from sql where id='$id'");
		$rows = $result->fetchAll(PDO::FETCH_ASSOC);
		$count = count($rows);
		$expected = 1;
        $this->assertEqual($expected, $count);
		$expected = "/'%x'/";
        $this->assertWantedPattern($expected, $rows[0]['code']);
		$expected = $id;
        $this->assertEqual($expected, $rows[0]['id']);
    }

}
// Running the test.
$test =& new SqlCodeTest;
$test->run(new HtmlReporter());
?>
