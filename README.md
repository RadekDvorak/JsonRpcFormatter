#JsonRpcFormatter#
JsonRpcFormatter is a JSON-RPC 2.0 formatter for PHP.

```
--> {"jsonrpc": "2.0", "method": "subtract", "params": [42, 23], "id": 1}
```

``` php
<?php

use JsonRpcFormatter\Request;
use JsonRpcFormatter\Validator\ArgumentValidator;

$validator = new ArgumentValidator;
$request = new Request($validator);
$request->setId("1");
$request->setMethod("subtract");
$request->setParams(array(42, 23));

// PHP 5.4
echo json_encode($request);

// PHP 5.3
echo json_encode($request->jsonSerialize());
```