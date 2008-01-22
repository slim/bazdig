<?php

require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');

class MarkTest extends UnitTestCase {

    var $WaraqService;

    function setUp()
    {
		global $waraq;

		define('WARAQ_ROOT', '..');
		require_once WARAQ_ROOT .'/ini.php';
        require_once '../lib/mark.php';
        $this->Mark =& new Mark("testid");
        $this->Mark2 =& new Mark("testid2");
		$this->Mark2->pageUrl = "http://localhost/test2";
		$this->Mark2->text  = "test text2";
        $this->Mark3 =& new Mark("testid3");
        $this->Mark4 =& new Mark("testid4");
		$this->Mark4->pageUrl = "http://localhost/test?s=55555";
		$this->db =& new PDO("sqlite2:markkit-test.db");
    }

    function tearDown()
    {
        unset($this->Mark);
		$tableName = Mark::get_table_name();
		$this->db->query("delete from $tableName where id='testid2'");
    }

    function testtoSQLinsert()
    {
        $result   = $this->Mark->toSQLinsert();
        $expected = "/insert/i";
        $this->assertWantedPattern($expected, $result);
        $expected = "/'testid'/";
        $this->assertWantedPattern($expected, $result);
        $result   = $this->Mark2->toSQLinsert();
        $expected = "/insert/i";
        $this->assertWantedPattern($expected, $result);
        $expected = "/'testid2'/";
        $this->assertWantedPattern($expected, $result);
        $expected = "/'http:\/\/localhost\/test2'/";
        $this->assertWantedPattern($expected, $result);
    }

    function testtoSQLselect()
    {
        $result   = $this->Mark->toSQLselect();
        $expected = "/select/i";
        $this->assertWantedPattern($expected, $result);
        $expected = "/id='testid'/";
        $this->assertWantedPattern($expected, $result);
    }

    function testsql_select()
    {
		$options = array('owner' => "testuser", 'pageUrl' => "http://localhost/dokuwiki/");
        $result   = Mark::sql_select($options);
        $expected = "/owner='testuser'/i";
        $this->assertWantedPattern($expected, $result);
        $expected = "/pageUrl LIKE 'http:\/\/localhost\/dokuwiki\/%'/i";
        $this->assertWantedPattern($expected, $result);
    }

    function testselect()
    {
		$this->Mark->creationDate = "1189807200";
		$this->Mark->pageUrl = "http://localhost/dokuwiki/";
		$this->Mark->text = "test text";
		$this->Mark->owner = "testuser";
		$this->Mark->startNodePath = array(1,2,3);
		$this->Mark->startOffset = '0';
		$this->Mark->endNodePath = array(4,5,6);
		$this->Mark->endOffset = '0';
		$this->Mark3->creationDate = "1189807200";
		$this->Mark3->pageUrl = "http://localhost/dokuwiki/x";
		$this->Mark3->text = "test text";
		$this->Mark3->owner = "testuser";
		$this->Mark3->startNodePath = array(1,2,3);
		$this->Mark3->startOffset = '0';
		$this->Mark3->endNodePath = array(4,5,6);
		$this->Mark3->endOffset = '0';
		$options = array('id' => 'testid');
        $result   = Mark::select($options, $this->db);
        $expected = array($this->Mark);
        $this->assertEqual($expected, $result);
		Mark::set_db($this->db);
        $result   = Mark::select($options);
        $this->assertEqual($expected, $result);
		Mark::set_db("sqlite2:markkit-test.db");
        $result   = Mark::select($options);
        $this->assertEqual($expected, $result);
		$options = array('pageUrl' => "http://localhost/dokuwiki/");
        $result   = Mark::select($options);
        $expected = array($this->Mark, $this->Mark3);
        $this->assertEqual($expected, $result);
    }

	function testcount()
	{
		$result = $this->Mark->count();
		$expected = 3;
        $this->assertEqual($expected, $result);
	}

    function testsave()
    {
        $this->Mark2->save($this->db);
        $result   = $this->db->query("select * from marks where id='testid2'");
        $expected = '00000';
        $this->assertEqual($expected, $result->errorCode());
		$result = $result->fetch();
        $expected = 'http://localhost/test2';
        $this->assertEqual($expected, $result['pageUrl']);
        $expected = 'test text2';
        $this->assertEqual($expected, $result['text']);
    }

    function testload()
    {
        $result   = $this->Mark->load($this->db);
        $expected = "testuser";
        $this->assertEqual($expected, $result->owner);
    }

    function testset_db()
    {
		global $waraq;

        $result   = Mark::set_db("mysql:host=localhost;dbname=markkit", "root", "");
        $expected = "mysql";
        $this->assertEqual($expected, $result->getAttribute(PDO::ATTR_DRIVER_NAME));
        $result   = Mark::set_db("sqlite2:markkit-test.db");
        $expected = "sqlite2";
        $this->assertEqual($expected, $result->getAttribute(PDO::ATTR_DRIVER_NAME));
    }
	
	function test_toHTMLanchor()
	{
		$result = $this->Mark2->toHTMLanchor();
		$expected = "<a href='http://localhost/test2' >test text2</a>";
        $this->assertEqual($expected, $result);
	}
	
	function test_strip_http_get_params()
	{
		$result = strip_http_get_params("http://localhost/test?s=55555");
		$expected = "http://localhost/test";
        $this->assertEqual($expected, $result);
	}

}
// Running the test.
$test =& new MarkTest;
$test->run(new HtmlReporter());
?>
