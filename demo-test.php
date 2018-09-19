<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
require_once('moac.php');

/**
 * Test suite for the moac-php.
 * Make sure you have created an account before running or you will get TONS OF ERRORS
 */

class TestNetFunctions extends TestBase
{
	private $mc;
	
	function __construct()
	{
		echo '<div><strong>Running '.__CLASS__.'</strong></div>';
		parent::__construct();
	}
	
	function CreateMOAC()
	{
		$this->mc = new MOAC('127.0.0.1', 8545);
		$this->assertIsA($this->mc, 'MOAC');
	}
	
	function ClientVersion()
	{
		$this->assertEqual($this->mc->chain3_clientVersion(), 'Moac/v1.0.2-stable-632a21f0/linux-amd64/go1.10');
	}
	
	function Sha3()
	{
		$this->assertEqual($this->mc->chain3_sha3('0x68656c6c6f20776f726c64'), '0x47173285a8d7341e5e972fc677286384f802f8ef42a5ec5f03bbfa254cb01fad');
	}
	
	function NetVersion()
	{
		$this->assertEqual($this->mc->net_version(), "99");
	}
	
	function IsListening()
	{
		$this->assertEqual($this->mc->net_listening(), TRUE);
	}
	
	function HasPeers()
	{
		$this->assertMatch($this->mc->net_peerCount(), '/0x[a-f0-9]+/');
	}
	
	function ProtocolVersion()
	{
		$this->assertIsNumeric($this->mc->mc_protocolVersion());
	}
	
	function CoinBase()
	{
		$coinbase = $this->mc->mc_coinbase();
		$this->assertLength($coinbase, 42);
		$this->assertIsHex($coinbase);
	}
}

class TestMOACFunctions extends TestBase
{
	private $account;
	
	function __construct()
	{
		echo '<div><strong>Running '.__CLASS__.'</strong></div>';
		parent::__construct();
	}
	
	function CreateMOAC()
	{
		$this->mc = new MOAC('127.0.0.1', 8545);
		$this->assertIsA($this->mc, 'MOAC');
	}
	
	function IsMining()
	{
		$mining = $this->mc->mc_mining();
		$this->assertIsBoolean($mining);
	}
	
	function HashRate()
	{
		$this->assertIsHex($this->mc->mc_hashrate());
	}
	
	function GasPrice()
	{
		$price = $this->mc->mc_gasPrice();
		$this->assertIsHex($price);
		
		// I assume gas will never be free?
		$this->assertNotEqual($price, '0x0');	
	}
	
	function Accounts()
	{
		$accounts = $this->mc->mc_accounts();
		
		$this->assertIsArray($accounts);
		$this->assertNotEqual(count($accounts), 0);
		$this->assertIsHex($accounts[0]);
		$this->assertLength($accounts[0], 42);
		
		// Save this account for later
		$this->account = $accounts[0];
	}
	
	function BlockNumber()
	{
		$blkNum = $this->mc->mc_blockNumber(TRUE);
		$blkHex = $this->mc->mc_blockNumber();
		
		$this->assertIsNumeric($blkNum);
		$this->assertNotEqual($blkNum, 0);
		$this->assertIsHex($blkHex);
		$this->assertEqual($blkHex, '0x'.dechex($blkNum));
	}
	
	function AccountBalance()
	{
		$balHex = $this->mc->mc_getBalance($this->account, 'latest');
		$balNum = $this->mc->mc_getBalance($this->account, 'latest', TRUE);
		
		$this->assertIsNumeric($balNum);
		$this->assertIsHex($balHex);
		$this->assertEqual($balHex, '0x'.dechex($balNum));
	}
	
	function AccountStorage()
	{
		$stor = $this->mc->mc_getStorageAt($this->account, '0x0', '0x1');
		
		$this->assertIsHex($stor);
	}
	
	function AddressTransactionCount()
	{
		$countHex = $this->mc->mc_getTransactionCount($this->account, 'latest');
		$countNum = $this->mc->mc_getTransactionCount($this->account, 'latest', TRUE);
		
		$this->assertIsNumeric($countNum);
		$this->assertIsHex($countHex);
		$this->assertEqual($countHex, '0x'.dechex($countNum));
	}
	
	function GetBlocks()
	{
		$block = $this->mc->mc_getBlockByNumber('latest');
		$blockByHash = $this->mc->mc_getBlockByHash($block->hash);
		
		$this->assertIsA($block, 'stdClass');
		$this->assertIsA($blockByHash, 'stdClass');
		$this->assertEqual($block->hash, $blockByHash->hash);
		
		$txCountByHash = $this->mc->mc_getBlockTransactionCountByHash($block->hash);
		$txCountByNum = $this->mc->mc_getBlockTransactionCountByNumber($block->number);
		
		$this->assertIsHex($txCountByHash);
		$this->assertIsHex($txCountByNum);
		$this->assertEqual($txCountByHash, $txCountByNum);
		$this->assertEqual($txCountByHash, '0x'.dechex(count($block->transactions)));
		
		$uncleCountByHash = $this->mc->mc_getUncleCountByBlockHash($block->hash);
		$uncleCountByNum = $this->mc->mc_getUncleCountByBlockNumber($block->number);
		
		$this->assertIsHex($uncleCountByHash);
		$this->assertIsHex($uncleCountByNum);
		$this->assertEqual($uncleCountByHash, $uncleCountByNum);
		$this->assertEqual($uncleCountByHash, '0x'.dechex(count($block->uncles)));
	}
	
	function GetTransactions()
	{
		// Get a recent block with some transactions
		$blockNum = $this->mc->mc_blockNumber(TRUE);
		do
		{
			$block = $this->mc->mc_getBlockByNumber('0x'.dechex(--$blockNum));
		}
		while(count($block->transactions) == 0);
		
		$tx = $block->transactions[0];
		
		$this->assertIsA($tx, 'stdClass');
		$this->assertIsHex($tx->hash);
		
		$txByBlock = $this->mc->mc_getTransactionByBlockHashAndIndex($block->hash, '0x0');
		
		$this->assertIsA($txByBlock, 'stdClass');
		$this->assertIsHex($txByBlock->hash);
		$this->assertEqual($tx->hash, $txByBlock->hash);
		
		$txByHash = $this->mc->mc_getTransactionByHash($tx->hash);
		
		$this->assertIsA($txByHash, 'stdClass');
		$this->assertIsHex($txByHash->hash);
		$this->assertEqual($tx->hash, $txByHash->hash);
		
		$receipt = $this->mc->mc_getTransactionReceipt($tx->hash);
		$this->assertIsA($receipt, 'stdClass');
		$this->assertEqual($receipt->blockHash, $block->hash);
		$this->assertEqual($receipt->blockNumber, '0x'.dechex($blockNum));
	}
	
	function DoTransaction()
	{
		// TODO: Test sending transactions. This requires mining, working on it.
	}
	
	function SendMessage()
	{
		// TODO: Message tests
	}
	
	function Compilers()
	{
		// TODO: Compiler tests
	}
	
	function Filters()
	{
		// TODO: Filter tests
	}
	
	function DB()
	{
		// TODO: DB Tests
	}
}

class TestWhisperFunctions extends TestBase
{
	function __construct()
	{
		echo '<div><strong>Running '.__CLASS__.'</strong></div>';
		parent::__construct();
	}
	
	function CreateMOAC()
	{
		$this->mc = new MOAC('127.0.0.1', 8545);
		$this->assertIsA($this->mc, 'MOAC');
	}
	
	
	
	function Post()
	{
		// TODO: Whisper post tests
	}
	
	function Filter()
	{
		// TODO: Whisper filter tests
	}
}

class TestBase
{
	private $test_count, $error_count;
	
	function __construct()
	{
		$this->test_count = $this->error_count = 0;
		set_error_handler(array($this, 'errorHandler'), E_USER_ERROR);
		$this->run();
	}
	
	function run()
	{
		foreach(get_class_methods($this) as $m)
		{
			if($m !== 'run' && $m !== '__construct' && $m != 'errorHandler' && !strstr($m, 'assert'))
			{
				try
				{
					$this->$m();
				}
				catch(Exception $e)
				{
				}
			}
		}
		
		if($this->error_count === 0)
		{
			echo '<div style="background-color: #00FF00;"><strong>Success:</strong> Ran '.$this->test_count.' tests successfully</div>';
		}
		else
		{
			echo '<div style="background-color: #FF0000;"><strong>Ran '.$this->test_count.' tests with '.$this->error_count.' errors</strong></div>';
		}
	}
	
	function assertEqual($a, $b)
	{
		$this->test_count++;
		
		if($a !== $b)
		{
			trigger_error("$a !== $b", E_USER_ERROR);
		}
	}
	
	function assertNotEqual($a, $b)
	{
		$this->test_count++;
		
		if($a === $b)
		{
			trigger_error("$a === $b", E_USER_ERROR);
		}
	}
	
	function assertIsA($a, $type)
	{
		$this->test_count++;
		
		if(!is_a($a, $type))
		{
			trigger_error("Object is not $type", E_USER_ERROR);
		}
	}
	
	function assertIsNumeric($a)
	{
		$this->test_count++;
		
		if(!is_numeric($a))
		{
			trigger_error("$a is not numeric", E_USER_ERROR);
		}
	}
	
	function assertMatch($a, $pattern)
	{
		$this->test_count++;
		
		if(!preg_match($pattern, $a))
		{
			trigger_error("$a does not match pattern '$pattern'", E_USER_ERROR);
		}
	}
	
	function assertLength($a, $len)
	{
		$this->test_count++;
		
		if(strlen($a) !== $len)
		{
			trigger_error("$a is not $len characters long", E_USER_ERROR);
		}
	}
	
	function assertIsHex($a)
	{
		$this->test_count++;
		
		if(!preg_match('/[0-9a-fx]+/', $a))
		{
			trigger_error("$a is not hex", E_USER_ERROR);
		}
	}
	
	function assertIsBoolean($a)
	{
		$this->test_count++;
		
		if(!is_bool($a))
		{
			trigger_error("Object is not boolean", E_USER_ERROR);
		}
	}
	
	function assertIsArray($a)
	{
		$this->test_count++;
		
		if(!is_array($a))
		{
			trigger_error("Object is not an array", E_USER_ERROR);
		}
	}
	
	function assertError($message)
	{
		$this->test_count++;
		trigger_error($message, E_USER_ERROR);
	}
	
	function errorHandler($errorNumber, $message, $file, $line, $context)
	{
		$additional = 'on line '.$line;
		
		$trace = array_reverse(debug_backtrace());
		array_pop($trace);
		if(isset($trace[3]) && isset($trace[4]))
		{
			$class = $trace[3]['class'];
			$function = $trace[3]['function'];
			$line = $trace[4]['line'];
			$additional = 'in <code>'.$class.'.'.$function.'()</code> on line '.$line;
		}
		
		echo '<div style="background-color: #FF0000;"><strong>Error:</strong> <code>'.$message.'</code> '.$additional.'</div>';
		$this->error_count++;
	}
}

foreach(array("TestNetFunctions", "TestMOACFunctions", "TestWhisperFunctions") as $c)
	$t = new $c();