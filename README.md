# Moac-Php-Chain3-Api
A PHP interface to the MOAC JSON-RPC API. All documented API functions are present.

PHP client for MOAC [Chain3 JSON RPC API](https://github.com/MOACChain/chain3).
## Usage
    // include the class file
    require 'moac.php';
    
    // create a new object
    $MOAC = new MOAC('127.0.0.1', 8545);
    
    // do your thing
    echo $MOAC->net_version();

See `demo-test.php` for a check availability;. 

If the self-test fails

please modify the MOAC client port on line demo-test.php 24 lines 248 lines for your RPC port, if you use the test network, modify the chain3_client Version on 30 lines and the net_version on 40 lines.

## Implemented JSON-RPC methods
* chain3_clientVersion
* net_version 
* net_listening
* net_peerCount
* mc_coinbase
* mc_mining
* mc_hashrate
* mc_gasPrice
* mc_accounts
* mc_blockNumber
* mc_getBalance
* mc_*****

Total 51 items
See moac.php complete methods

----------------
##Donations

MOAC: 0x53be4cb8f27152893b448f9f569624afd1a97e0c
