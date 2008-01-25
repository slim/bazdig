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
    }

    function tearDown()
    {
		$tableName = SqlCode::get_table_name();
		$id = $this->sqlCode->id;
		$this->db->exec("delete from $tableName where id='$id'");
    }

    function testtoSQLinsert()
    {
        $result   = $this->sqlCode->toSQLinsert();
        $expected = "/insert/i";
        $this->assertWantedPattern($expected, $result);
        $expected = "/'md5:".$this->md5."'/";
        $this->assertWantedPattern($expected, $result);
    }

    function testtoSQLselect()
    {
        $result   = $this->sqlCode->toSQLselect();
        $expected = "/select/i";
        $this->assertWantedPattern($expected, $result);
        $expected = "/id='md5:".$this->md5."'/";
        $this->assertWantedPattern($expected, $result);
    }

    function testsql_select()
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

    function testselect()
    {
		$result = SqlCode::select(" where id='test:001'");
		$expected = array($this->sqlCode2);
        $this->assertEqual($expected, $result);
    }

    function testsave()
    {
		$this->sqlCode->save();
		$md5 = $this->sqlCode->id;
		$result = $this->db->query("select code from sql where id='$md5'");
		$result = $result->fetch();
		$expected = "select * from test";
        $this->assertEqual($expected, $result['code']);
		$this->sqlCode->save();
		$result = $this->db->query("select * from sql where id='$md5'");
		$result = $result->fetchAll();
		$expected = 1;
        $this->assertEqual($expected, count($result));
    }

}
// Running the test.
$test =& new SqlCodeTest;
$test->run(new HtmlReporter());
?>
