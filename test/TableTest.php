<?php

require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');

class TableTest extends UnitTestCase {

    function setUp()
    {
        require_once '../lib/database.php';
		$this->db =& new BDB("sqlite:bazdig-test.db");
		$this->table = new Table('sql');
    }

    function tearDown()
    {
    }

    function test_listTables()
	{
		$tables = $this->db->listTables();
		$expected = array($this->table);
		$this->assertEqual($expected, $tables);
	}

	function test_loadColumns()
	{
		$this->table->loadColumns($this->db);
		$expected = array(new Column('id'), new Column('date'), new Column('code'));
		$this->assertEqual($expected, $this->table->columns);
	}

}
// Running the test.
$test =& new TableTest;
$test->run(new HtmlReporter());
?>
