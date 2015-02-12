<?php return array (
  'server' => array(
      'enabled' => false,
      'prefix' => '/jsonrpc', //URL for json server
      'methods' => array('POST'),  //Allowed http methods to call the server
      'resolvers' => array(
          'default' => '\{class}Controller', //ResolverName => resolverTemplate, {class} will be replaced by className (method is className.StaticFunctionName)
          'sample' => '\TestController' //ResolverName => resolverTemplate, Sample with fix className, remote client can't choice the controller name.
      ),
      'allowed' => array(
          'localhost' => '', //Ip and key, for localhost ip must be localhost, key can be blank or null
          //'192.168.1.0/24' => '__KEY__'
      )
  )
);