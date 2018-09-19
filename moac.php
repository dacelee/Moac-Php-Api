<?php

/**
 * MOAC JSON-RPC interface
 *
 * See MOAC API documentation for more information:
 
 */

require_once(dirname(__FILE__).'/json-rpc.php');

class MOAC extends JSON_RPC
{
	private function moacer_request($method, $params=array())
	{
		try 
		{
			$ret = $this->request($method, $params);
			return $ret->result;
		}
		catch(RPCException $e) 
		{
			throw $e;
		}
	}
	
	private function decode_hex($input)
	{
		if(substr($input, 0, 2) == '0x')
			$input = substr($input, 2);
		
		if(preg_match('/[a-f0-9]+/', $input))
			return hexdec($input);
			
		return $input;
	}
	
	function chain3_clientVersion()
	{
		return $this->moacer_request(__FUNCTION__);
	}
	
	function chain3_sha3($input)
	{
		return $this->moacer_request(__FUNCTION__, array($input));
	}
	
	function net_version()
	{
		return $this->moacer_request(__FUNCTION__);
	}
	
	function net_listening()
	{
		return $this->moacer_request(__FUNCTION__);
	}
	
	function net_peerCount()
	{
		return $this->moacer_request(__FUNCTION__);
	}
	
	function mc_protocolVersion()
	{
		return $this->moacer_request(__FUNCTION__);
	}
	
	function mc_coinbase()
	{
		return $this->moacer_request(__FUNCTION__);
	}
	
	function mc_mining()
	{
		return $this->moacer_request(__FUNCTION__);
	}
	
	function mc_hashrate()
	{
		return $this->moacer_request(__FUNCTION__);
	}
	
	function mc_gasPrice()
	{
		return $this->moacer_request(__FUNCTION__);
	}
	
	function mc_accounts()
	{
		return $this->moacer_request(__FUNCTION__);
	}
	
	function mc_blockNumber($decode_hex=FALSE)
	{
		$block = $this->moacer_request(__FUNCTION__);
		
		if($decode_hex)
			$block = $this->decode_hex($block);
		
		return $block;
	}
	
	function mc_getBalance($address, $block='latest', $decode_hex=FALSE)
	{
		$balance = $this->moacer_request(__FUNCTION__, array($address, $block));
		
		if($decode_hex)
			$balance = $this->decode_hex($balance);
		
		return $balance;
	}
	
	function mc_getStorageAt($address, $at, $block='latest')
	{
		return $this->moacer_request(__FUNCTION__, array($address, $at, $block));
	}
	
	function mc_getTransactionCount($address, $block='latest', $decode_hex=FALSE)
	{
		$count = $this->moacer_request(__FUNCTION__, array($address, $block));
        
        if($decode_hex)
            $count = $this->decode_hex($count);
            
        return $count;   
	}
	
	function mc_getBlockTransactionCountByHash($tx_hash)
	{
		return $this->moacer_request(__FUNCTION__, array($tx_hash));
	}
	
	function mc_getBlockTransactionCountByNumber($tx='latest')
	{
		return $this->moacer_request(__FUNCTION__, array($tx));
	}
	
	function mc_getUncleCountByBlockHash($block_hash)
	{
		return $this->moacer_request(__FUNCTION__, array($block_hash));
	}
	
	function mc_getUncleCountByBlockNumber($block='latest')
	{
		return $this->moacer_request(__FUNCTION__, array($block));
	}
	
	function mc_getCode($address, $block='latest')
	{
		return $this->moacer_request(__FUNCTION__, array($address, $block));
	}
	
	function mc_sign($address, $input)
	{
		return $this->moacer_request(__FUNCTION__, array($address, $input));
	}
	
	function mc_sendTransaction($transaction)
	{
		if(!is_a($transaction, 'MOAC_Transaction'))
		{
			throw new ErrorException('Transaction object expected');
		}
		else
		{
			return $this->moacer_request(__FUNCTION__, $transaction->toArray());	
		}
	}
	
	function mc_call($message, $block)
	{
		if(!is_a($message, 'MOAC_Message'))
		{
			throw new ErrorException('Message object expected');
		}
		else
		{
			return $this->moacer_request(__FUNCTION__, $message->toArray());
		}
	}
	
	function mc_estimateGas($message, $block)
	{
		if(!is_a($message, 'MOAC_Message'))
		{
			throw new ErrorException('Message object expected');
		}
		else
		{
			return $this->moacer_request(__FUNCTION__, $message->toArray());
		}
	}
	
	function mc_getBlockByHash($hash, $full_tx=TRUE)
	{
		return $this->moacer_request(__FUNCTION__, array($hash, $full_tx));
	}
	
	function mc_getBlockByNumber($block='latest', $full_tx=TRUE)
	{
		return $this->moacer_request(__FUNCTION__, array($block, $full_tx));
	}
	
	function mc_getTransactionByHash($hash)
	{
		return $this->moacer_request(__FUNCTION__, array($hash));
	}
	
	function mc_getTransactionByBlockHashAndIndex($hash, $index)
	{
		return $this->moacer_request(__FUNCTION__, array($hash, $index));
	}
	
	function mc_getTransactionByBlockNumberAndIndex($block, $index)
	{
		return $this->moacer_request(__FUNCTION__, array($block, $index));
	}
	
	function mc_getTransactionReceipt($tx_hash)
	{
		return $this->moacer_request(__FUNCTION__, array($tx_hash));
	}
	
	function mc_getUncleByBlockHashAndIndex($hash, $index)
	{
		return $this->moacer_request(__FUNCTION__, array($hash, $index));
	}
	
	function mc_getUncleByBlockNumberAndIndex($block, $index)
	{
		return $this->moacer_request(__FUNCTION__, array($block, $index));
	}
	
	function mc_getCompilers()
	{
		return $this->moacer_request(__FUNCTION__);
	}
	
	function mc_compileSolidity($code)
	{
		return $this->moacer_request(__FUNCTION__, array($code));
	}
	
	function mc_compileLLL($code)
	{
		return $this->moacer_request(__FUNCTION__, array($code));
	}
	
	function mc_compileSerpent($code)
	{
		return $this->moacer_request(__FUNCTION__, array($code));
	}
	
	function mc_newFilter($filter, $decode_hex=FALSE)
	{
		if(!is_a($filter, 'MOAC_Filter'))
		{
			throw new ErrorException('Expected a Filter object');
		}
		else
		{
			$id = $this->moacer_request(__FUNCTION__, $filter->toArray());
			
			if($decode_hex)
				$id = $this->decode_hex($id);
			
			return $id;
		}
	}
	
	function mc_newBlockFilter($decode_hex=FALSE)
	{
		$id = $this->moacer_request(__FUNCTION__);
		
		if($decode_hex)
			$id = $this->decode_hex($id);
		
		return $id;
	}
	
	function mc_newPendingTransactionFilter($decode_hex=FALSE)
	{
		$id = $this->moacer_request(__FUNCTION__);
		
		if($decode_hex)
			$id = $this->decode_hex($id);
		
		return $id;
	}
	
	function mc_uninstallFilter($id)
	{
		return $this->moacer_request(__FUNCTION__, array($id));
	}
	
	function mc_getFilterChanges($id)
	{
		return $this->moacer_request(__FUNCTION__, array($id));
	}
	
	function mc_getFilterLogs($id)
	{
		return $this->moacer_request(__FUNCTION__, array($id));
	}
	
	function mc_getLogs($filter)
	{
		if(!is_a($filter, 'MOAC_Filter'))
		{
			throw new ErrorException('Expected a Filter object');
		}
		else
		{
			return $this->moacer_request(__FUNCTION__, $filter->toArray());
		}
	}
	
	function mc_getWork()
	{
		return $this->moacer_request(__FUNCTION__);
	}
	
	function mc_submitWork($nonce, $pow_hash, $mix_digest)
	{
		return $this->moacer_request(__FUNCTION__, array($nonce, $pow_hash, $mix_digest));
	}
	
	function db_putString($db, $key, $value)
	{
		return $this->moacer_request(__FUNCTION__, array($db, $key, $value));
	}
	
	function db_getString($db, $key)
	{
		return $this->moacer_request(__FUNCTION__, array($db, $key));
	}
	
	function db_putHex($db, $key, $value)
	{
		return $this->moacer_request(__FUNCTION__, array($db, $key, $value));
	}
	
	function db_getHex($db, $key)
	{
		return $this->moacer_request(__FUNCTION__, array($db, $key));
	}
	
	function shh_version()
	{
		return $this->moacer_request(__FUNCTION__);
	}
	
	function shh_post($post)
	{
		if(!is_a($post, 'Whisper_Post'))
		{
			throw new ErrorException('Expected a Whisper post');
		}
		else
		{
			return $this->moacer_request(__FUNCTION__, $post->toArray());
		}
	}
	
	function shh_newIdentinty()
	{
		return $this->moacer_request(__FUNCTION__);
	}
	
	function shh_hasIdentity($id)
	{
		return $this->moacer_request(__FUNCTION__);
	}
	
	function shh_newFilter($to=NULL, $topics=array())
	{
		return $this->moacer_request(__FUNCTION__, array(array('to'=>$to, 'topics'=>$topics)));
	}
	
	function shh_uninstallFilter($id)
	{
		return $this->moacer_request(__FUNCTION__, array($id));
	}
	
	function shh_getFilterChanges($id)
	{
		return $this->moacer_request(__FUNCTION__, array($id));
	}
	
	function shh_getMessages($id)
	{
		return $this->moacer_request(__FUNCTION__, array($id));
	}
}

/**
 *	MOAC transaction object
 */
class MOAC_Transaction
{
	private $to, $from, $gas, $gasPrice, $value, $data, $nonce;
	
	function __construct($from, $to, $gas, $gasPrice, $value, $data='', $nonce=NULL)
	{
		$this->from = $from;
		$this->to = $to;
		$this->gas = $gas;
		$this->gasPrice = $gasPrice;
		$this->value = $value;
		$this->data = $data;
		$this->nonce = $nonce;
	}
	
	function toArray()
	{
		return array(
			array
			(
				'from'=>$this->from,
				'to'=>$this->to,
				'gas'=>$this->gas,
				'gasPrice'=>$this->gasPrice,
				'value'=>$this->value,
				'data'=>$this->data,
				'nonce'=>$this->nonce
			)
		);
	}
}

/**
 *	MOAC message -- Same as a transaction, except using this won't
 *  post the transaction to the blockchain.
 */
class MOAC_Message extends MOAC_Transaction
{

}

/**
 *	MOAC transaction filter object
 */
class MOAC_Filter
{
	private $fromBlock, $toBlock, $address, $topics;
	
	function __construct($fromBlock, $toBlock, $address, $topics)
	{
		$this->fromBlock = $fromBlock;
		$this->toBlock = $toBlock;
		$this->address = $address;
		$this->topics = $topics;
	}
	
	function toArray()
	{
		return array(
			array
			(
				'fromBlock'=>$this->fromBlock,
				'toBlock'=>$this->toBlock,
				'address'=>$this->address,
				'topics'=>$this->topics
			)
		);
	}
}

/**
 * 	MOAC whisper post object
 */
class Whisper_Post
{
	private $from, $to, $topics, $payload, $priority, $ttl;
	
	function __construct($from, $to, $topics, $payload, $priority, $ttl)
	{
		$this->from = $from;
		$this->to = $to;
		$this->topics = $topics;
		$this->payload = $payload;
		$this->priority = $priority;
		$this->ttl = $ttl;
	}
	
	function toArray()
	{
		return array(
			array
			(
				'from'=>$this->from,
				'to'=>$this->to,
				'topics'=>$this->topics,
				'payload'=>$this->payload,
				'priority'=>$this->priority,
				'ttl'=>$this->ttl
			)
		);
	}
}