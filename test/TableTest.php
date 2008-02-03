<?php

require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');

class TableTest extends UnitTestCase {

    function setUp()
    {
		define('WARAQ_ROOT', '..');
		require_once WARAQ_ROOT .'/ini.php';
        require_once '../lib/database.php';
		$this->dbsqlite =& new BDB("sqlite:bazdig-test.db");
		$this->dbmysql  =& new BDB("mysql:host=localhost;dbname=markkit", "root");
		$this->dbmysql2 =& new BDB(array('type' => 'mysql', 'host' => 'localhost', 'name' => 'markkit'), "root");
		$this->dbsqlite2 =& new BDB(array('type' => 'sqlite', 'name' => 'bazdig-test.db'));
		$this->table = new Table('sql');
		$this->table2 = new Table('marks');
    }

    function tearDown()
    {
    }

	function test_httpGet()
	{
		global $bazdig;

		$result = $this->dbmysql2->httpGet($bazdig->get('/test/query_string.php'));
		$expected = "dbt=mysql&dbn=markkit&dbh=localhost&dbu=root&dbp=";
		$this->assertEqual($expected, $result);
	}

	function test_getDsn()
	{
		$result = $this->dbmysql2->getDsn();
		$expected = "mysql:host=localhost;dbname=markkit";
		$this->assertEqual($expected, $result);
		$result = $this->dbsqlite2->getDsn();
		$expected = "sqlite:bazdig-test.db";
		$this->assertEqual($expected, $result);
	}

    function test_listTables()
	{
		$tables = $this->dbsqlite->listTables();
		$expected = array($this->table, new Table('bash'));
		$this->assertEqual($expected, $tables);
		$expected = array(new Table('ctrl_patch'), new Table('marks'));
		$tables = $this->dbmysql->listTables();
		$this->assertEqual($expected, $tables);
		$tables = $this->dbmysql2->listTables();
		$this->assertEqual($expected, $tables);
	}

	function test_loadColumns()
	{
		$this->table->loadColumns($this->dbsqlite);
		$expected = array(new Column('id'), new Column('code'), new Column('date'));
		$this->assertEqual($expected, $this->table->columns);
		$expected = array(new Column('id', 'varchar(50)'), new Column('creationDate', 'datetime'), new Column('pageUrl', 'varchar(255)'), new Column('text', 'varchar(255)'), new Column('owner', 'varchar(50)'), new Column ('startNodePath', 'varchar(128)'), new Column ('startOffset', 'int(11)'), new Column('endNodePath', 'varchar(128)'), new Column('endOffset', 'int(11)'));
		$this->table2->loadColumns($this->dbmysql);
		$this->assertEqual($expected, $this->table2->columns);
		$this->table2->loadColumns($this->dbmysql2);
		$this->assertEqual($expected, $this->table2->columns);
	}

}
// Running the test.
$test =& new TableTest;
$test->run(new HtmlReporter());
?>
