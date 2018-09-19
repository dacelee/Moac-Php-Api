# Moac-Php-Api
A PHP interface to the MOAC JSON-RPC API. All documented API functions are present.

## Usage
    // include the class file
    require 'moac.php';
    
    // create a new object
    $MOAC = new MOAC('127.0.0.1', 8545);
    
    // do your thing
    echo $MOAC->net_version();

See `demo-test.php` for a check availability;. 

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
