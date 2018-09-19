# Moac-Php-Api
A PHP interface to the MOAC JSON-RPC API. All documented API functions are present.

## Usage
    // include the class file
    require 'moac.php';
    
    // create a new object
    $MOAC = new MOAC('127.0.0.1', 8545);
    
    // do your thing
    echo $MOAC->net_version();

See `demo-test.php` for a complete example. 

## Function documentation
