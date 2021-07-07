define({ "api": [
  {
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "optional": false,
            "field": "varname1",
            "description": "<p>No type.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "varname2",
            "description": "<p>With type.</p>"
          }
        ]
      }
    },
    "type": "",
    "url": "",
    "version": "0.0.0",
    "filename": "./doc/main.js",
    "group": "/home/mihiran/MyProjects/auction-web-app-with-angular-symfony/server/src/Controller/doc/main.js",
    "groupTitle": "/home/mihiran/MyProjects/auction-web-app-with-angular-symfony/server/src/Controller/doc/main.js",
    "name": ""
  },
  {
    "type": "get",
    "url": "http://localhost:8001/api/accessToken",
    "title": "Access Token - Get",
    "description": "<p>Get Access Token</p>",
    "name": "getAccessToken",
    "group": "AUTHENTICATION",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "username",
            "description": "<ul> <li>Username</li> </ul>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<ul> <li>Password</li> </ul>"
          }
        ]
      }
    },
    "sampleRequest": [
      {
        "url": "http://localhost:8001/api/accessToken"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Json",
            "optional": false,
            "field": "Object",
            "description": "<p>Object containing access token data</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\n  \"id\": 1,\n  \"userId\": 1,\n  \"username\": \"admin1\",\n  \"token\": \"af874ho9s8dfush6\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "BadRequest",
            "description": "<p>Bad Request</p>"
          }
        ],
        "401": [
          {
            "group": "401",
            "optional": false,
            "field": "Unauthorized",
            "description": "<p>Unauthorized</p>"
          }
        ],
        "404": [
          {
            "group": "404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Not Found</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./AccessTokenController.php",
    "groupTitle": "AUTHENTICATION"
  },
  {
    "type": "get",
    "url": "http://localhost:8001/api/permissions",
    "title": "Permissions - Get",
    "description": "<p>Get Permissions</p>",
    "name": "getPermissions",
    "group": "AUTHORIZATION",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "accessToken",
            "description": "<ul> <li>Access Token</li> </ul>"
          }
        ]
      }
    },
    "sampleRequest": [
      {
        "url": "http://localhost:8001/api/permissions"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Json",
            "optional": false,
            "field": "Object",
            "description": "<p>Object containing permission data</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\n  \"item\": {\n    \"canRead\": true,\n    \"canCreate\": true,\n    \"canUpdate\": true,\n    \"canDelete\": true\n  },\n  \"bid\": {\n    \"canRead\": true,\n    \"canCreate\": true,\n    \"canUpdate\": false,\n    \"canDelete\": false\n  },\n  \"bid_history\": {\n    \"canRead\": true,\n    \"canCreate\": false,\n    \"canUpdate\": false,\n    \"canDelete\": false\n  },\n  \"configure_auto_bid\": {\n    \"canRead\": true,\n    \"canCreate\": true,\n    \"canUpdate\": true,\n    \"canDelete\": false\n  },\n  \"admin_dashboard\": {\n    \"canRead\": true,\n    \"canCreate\": false,\n    \"canUpdate\": false,\n    \"canDelete\": false\n  },\n  \"user_details\": {\n    \"canRead\": true,\n    \"canCreate\": false,\n    \"canUpdate\": true,\n    \"canDelete\": false\n  },\n  \"item_bill\": {\n    \"canRead\": true,\n    \"canCreate\": false,\n    \"canUpdate\": false,\n    \"canDelete\": false\n  }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "BadRequest",
            "description": "<p>Bad Request</p>"
          }
        ],
        "401": [
          {
            "group": "401",
            "optional": false,
            "field": "Unauthorized",
            "description": "<p>Unauthorized</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./UserRoleDataGroupController.php",
    "groupTitle": "AUTHORIZATION"
  },
  {
    "type": "get",
    "url": "http://localhost:8001/api/autoBidConfig",
    "title": "Auto Bid Config - Get",
    "description": "<p>Get Auto Bid Config</p>",
    "name": "getAutoBidConfig",
    "group": "BID",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "accessToken",
            "description": "<ul> <li>Access Token</li> </ul>"
          }
        ]
      }
    },
    "sampleRequest": [
      {
        "url": "http://localhost:8001/api/autoBidConfig"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Json",
            "optional": false,
            "field": "Object",
            "description": "<p>Object containing auto bid config data</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\n  \"id\": 1,\n  \"userId\": 1,\n  \"userName\": \"admin1\",\n  \"maxBidAmount\": \"2500.00\",\n  \"currentBidAmount\": \"1250.00\",\n  \"notifyPercentage\": 90,\n  \"isAutoBidEnabled\": true,\n  \"isMaxBidExceedNotified\": false\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "BadRequest",
            "description": "<p>Bad Request</p>"
          }
        ],
        "401": [
          {
            "group": "401",
            "optional": false,
            "field": "Unauthorized",
            "description": "<p>Unauthorized</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./UserBidConfigController.php",
    "groupTitle": "BID"
  },
  {
    "type": "get",
    "url": "http://localhost:8001/api/bids",
    "title": "Bids - Get",
    "description": "<p>Get Bids</p>",
    "name": "getBids",
    "group": "BID",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "accessToken",
            "description": "<ul> <li>Access Token</li> </ul>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "filter[itemId]",
            "description": "<ul> <li>Item Id</li> </ul>"
          }
        ]
      }
    },
    "sampleRequest": [
      {
        "url": "http://localhost:8001/api/bids"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Json",
            "optional": false,
            "field": "Object",
            "description": "<p>Object containing bids data</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "[\n  {\n    \"id\":1,\n    \"userId\":1,\n    \"username\":\"admin1\",\n    \"itemId\":1,\n    \"itemName\":\"Item 1\",\n    \"bid\":\"1951.00\",\n    \"isAutoBid\":false,\n    \"dateTime\":\"2021-07-08 21:10\"\n  },\n  {\n    \"id\":3,\n    \"userId\":1,\n    \"username\":\"admin1\",\n    \"itemId\":1,\n    \"itemName\":\"Item 1\",\n    \"bid\":\"1953.00\",\n    \"isAutoBid\":true,\n    \"dateTime\":\"2021-07-08 21:16\"\n  }\n]",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "BadRequest",
            "description": "<p>Bad Request</p>"
          }
        ],
        "401": [
          {
            "group": "401",
            "optional": false,
            "field": "Unauthorized",
            "description": "<p>Unauthorized</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./BidController.php",
    "groupTitle": "BID"
  },
  {
    "type": "post",
    "url": "http://localhost:8001/api/bids",
    "title": "Bid - Post",
    "description": "<p>Save Bid</p>",
    "name": "saveBid",
    "group": "BID",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Json",
            "optional": false,
            "field": "Object",
            "description": "<p>Object containing bid data with access token</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Parameter Object-Example:",
          "content": "{\n  \"itemId\":1,\n  \"bid\":\"1956.00\",\n  \"isAutoBid\":true,\n  \"accessToken\":\"af874ho9s8dfush6\"\n}",
          "type": "Json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "http://localhost:8001/api/bids"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Json",
            "optional": false,
            "field": "Object",
            "description": "<p>Object containing bid data</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\n  \"id\":7,\n  \"userId\":1,\n  \"username\":\"admin1\",\n  \"itemId\":1,\n  \"itemName\":\"Item 1\",\n  \"bid\":\"1956.00\",\n  \"isAutoBid\":true,\n  \"dateTime\":\"2021-07-08 22:40\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "BadRequest",
            "description": "<p>Bad Request</p>"
          }
        ],
        "401": [
          {
            "group": "401",
            "optional": false,
            "field": "Unauthorized",
            "description": "<p>Unauthorized</p>"
          }
        ],
        "404": [
          {
            "group": "404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Not Found</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./BidController.php",
    "groupTitle": "BID"
  },
  {
    "type": "put",
    "url": "http://localhost:8001/api/autoBidConfig",
    "title": "Auto Bid Config - Put",
    "description": "<p>Update Auto Bid Config</p>",
    "name": "updateAutoBidConfig",
    "group": "BID",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Json",
            "optional": false,
            "field": "Object",
            "description": "<p>Object containing auto bid config data with access token</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Parameter Object-Example:",
          "content": "{\n  \"maxBidAmount\":\"2500.00\",\n  \"notifyPercentage\":\"90\",\n  \"isAutoBidEnabled\":true,\n  \"accessToken\":\"af874ho9s8dfush6\"\n}",
          "type": "Json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "http://localhost:8001/api/autoBidConfig"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Json",
            "optional": false,
            "field": "Object",
            "description": "<p>Object containing auto bid config data</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\n  \"id\":1,\n  \"userId\":1,\n  \"userName\":\"admin1\",\n  \"maxBidAmount\":\"2500.00\",\n  \"currentBidAmount\":\"1250.00\",\n  \"notifyPercentage\":90,\n  \"isAutoBidEnabled\":true,\n  \"isMaxBidExceedNotified\":false\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "BadRequest",
            "description": "<p>Bad Request</p>"
          }
        ],
        "401": [
          {
            "group": "401",
            "optional": false,
            "field": "Unauthorized",
            "description": "<p>Unauthorized</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./UserBidConfigController.php",
    "groupTitle": "BID"
  },
  {
    "type": "delete",
    "url": "http://localhost:8001/api/items/:id",
    "title": "Item - Delete",
    "description": "<p>Delete Item</p>",
    "name": "deleteItem",
    "group": "ITEM",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "accessToken",
            "description": "<ul> <li>Access Token</li> </ul>"
          }
        ]
      }
    },
    "sampleRequest": [
      {
        "url": "http://localhost:8001/api/items/1"
      }
    ],
    "success": {
      "fields": {
        "204": [
          {
            "group": "204",
            "optional": false,
            "field": "NoContent",
            "description": ""
          }
        ]
      }
    },
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "BadRequest",
            "description": "<p>Bad Request</p>"
          }
        ],
        "401": [
          {
            "group": "401",
            "optional": false,
            "field": "Unauthorized",
            "description": "<p>Unauthorized</p>"
          }
        ],
        "404": [
          {
            "group": "404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Not Found</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./ItemController.php",
    "groupTitle": "ITEM"
  },
  {
    "type": "get",
    "url": "http://localhost:8001/api/items/downloadBill",
    "title": "Download Item Bill - Get",
    "description": "<p>Download Item Bill</p>",
    "name": "downloadItemBill",
    "group": "ITEM",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "accessToken",
            "description": "<ul> <li>Access Token</li> </ul>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "itemId",
            "description": "<ul> <li>Item Id</li> </ul>"
          }
        ]
      }
    },
    "sampleRequest": [
      {
        "url": "http://localhost:8001/api/items/downloadBill"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Attachment",
            "optional": false,
            "field": "Attachment",
            "description": "<p>Pdf containing item bill</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "BadRequest",
            "description": "<p>Bad Request</p>"
          }
        ],
        "401": [
          {
            "group": "401",
            "optional": false,
            "field": "Unauthorized",
            "description": "<p>Unauthorized</p>"
          }
        ],
        "404": [
          {
            "group": "404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Not Found</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./ItemBillController.php",
    "groupTitle": "ITEM"
  },
  {
    "type": "get",
    "url": "http://localhost:8001/api/items/:id",
    "title": "Item - Get",
    "description": "<p>Get Item</p>",
    "name": "getItem",
    "group": "ITEM",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "accessToken",
            "description": "<ul> <li>Access Token</li> </ul>"
          }
        ]
      }
    },
    "sampleRequest": [
      {
        "url": "http://localhost:8001/api/items/1"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Json",
            "optional": false,
            "field": "Object",
            "description": "<p>Object containing item data</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\n  \"id\": 2,\n  \"name\": \"Item 4\",\n  \"description\": \"Description 4\",\n  \"price\": \"400.00\",\n  \"bid\": \"460.00\",\n  \"closeDateTime\": \"2021-07-07 21:45\",\n  \"isAutoBidEnabled\": false,\n  \"isClosed\": true,\n  \"isAwardNotified\": true,\n  \"awardedUserId\": 3,\n  \"awardedUsername\": \"user1\",\n  \"awardedUserRoleId\": 2,\n  \"awardedUserRoleName\": \"Regular\",\n  \"awardedUserEmail\": \"user1@gmail.com\",\n  \"awardedUserFirstName\": \"Mike\",\n  \"awardedUserLastName\": \"Smith\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "BadRequest",
            "description": "<p>Bad Request</p>"
          }
        ],
        "401": [
          {
            "group": "401",
            "optional": false,
            "field": "Unauthorized",
            "description": "<p>Unauthorized</p>"
          }
        ],
        "404": [
          {
            "group": "404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Not Found</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./ItemController.php",
    "groupTitle": "ITEM"
  },
  {
    "type": "get",
    "url": "http://localhost:8001/api/items",
    "title": "Items - Get",
    "description": "<p>Get Items</p>",
    "name": "getItems",
    "group": "ITEM",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "accessToken",
            "description": "<ul> <li>Access Token</li> </ul>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "filter[name]",
            "description": "<ul> <li>Item Name</li> </ul>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "filter[description]",
            "description": "<ul> <li>Item Description</li> </ul>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "limit",
            "description": "<ul> <li>Limit</li> </ul>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "offset",
            "description": "<ul> <li>Offset</li> </ul>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "sortField",
            "description": "<ul> <li>Sort Field</li> </ul>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "sortOrder",
            "description": "<ul> <li>Sort Order</li> </ul>"
          }
        ]
      }
    },
    "sampleRequest": [
      {
        "url": "http://localhost:8001/api/items"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Json",
            "optional": false,
            "field": "Object",
            "description": "<p>Object containing items data</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "[\n  {\n    \"id\": 1,\n    \"name\": \"Item 1\",\n    \"description\": \"Description 1\",\n    \"price\": \"1800.00\",\n    \"bid\": \"1950.00\",\n    \"closeDateTime\": \"2021-07-08 16:20\",\n    \"isAutoBidEnabled\": true,\n    \"isClosed\": false,\n    \"isAwardNotified\": false,\n    \"awardedUserId\": null,\n    \"awardedUsername\": null,\n    \"awardedUserRoleId\": null,\n    \"awardedUserRoleName\": null,\n    \"awardedUserEmail\": null,\n    \"awardedUserFirstName\": null,\n    \"awardedUserLastName\": null\n  },\n  {\n    \"id\": 2,\n    \"name\": \"Item 4\",\n    \"description\": \"Description 4\",\n    \"price\": \"400.00\",\n    \"bid\": \"460.00\",\n    \"closeDateTime\": \"2021-07-07 21:45\",\n    \"isAutoBidEnabled\": false,\n    \"isClosed\": true,\n    \"isAwardNotified\": true,\n    \"awardedUserId\": 3,\n    \"awardedUsername\": \"user1\",\n    \"awardedUserRoleId\": 2,\n    \"awardedUserRoleName\": \"Regular\",\n    \"awardedUserEmail\": \"user1@gmail.com\",\n    \"awardedUserFirstName\": \"Mike\",\n    \"awardedUserLastName\": \"Smith\"\n  }\n]",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "BadRequest",
            "description": "<p>Bad Request</p>"
          }
        ],
        "401": [
          {
            "group": "401",
            "optional": false,
            "field": "Unauthorized",
            "description": "<p>Unauthorized</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./ItemController.php",
    "groupTitle": "ITEM"
  },
  {
    "type": "post",
    "url": "http://localhost:8001/api/items",
    "title": "Item - Post",
    "description": "<p>Save Item</p>",
    "name": "saveItem",
    "group": "ITEM",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Json",
            "optional": false,
            "field": "Object",
            "description": "<p>Object containing item data with access token</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Parameter Object-Example:",
          "content": "{\n  \"name\":\"Item 3\",\n  \"description\":\"Description 3\",\n  \"price\":\"1500\",\n  \"bid\":\"1600\",\n  \"closeDateTime\":\"2021-07-08 10:50\",\n  \"accessToken\":\"af874ho9s8dfush6\"\n}",
          "type": "Json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "http://localhost:8001/api/items"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Json",
            "optional": false,
            "field": "Object",
            "description": "<p>Object containing item data</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\n  \"id\":3,\n  \"name\":\"Item 3\",\n  \"description\":\"Description 3\",\n  \"price\":\"1500\",\n  \"bid\":\"1600\",\n  \"closeDateTime\":\"2021-07-08 10:50\",\n  \"isAutoBidEnabled\":false,\n  \"isClosed\":false,\n  \"isAwardNotified\":false,\n  \"awardedUserId\":null,\n  \"awardedUsername\":null,\n  \"awardedUserRoleId\":null,\n  \"awardedUserRoleName\":null,\n  \"awardedUserEmail\":null,\n  \"awardedUserFirstName\":null,\n  \"awardedUserLastName\":null\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "BadRequest",
            "description": "<p>Bad Request</p>"
          }
        ],
        "401": [
          {
            "group": "401",
            "optional": false,
            "field": "Unauthorized",
            "description": "<p>Unauthorized</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./ItemController.php",
    "groupTitle": "ITEM"
  },
  {
    "type": "put",
    "url": "http://localhost:8001/api/items/:id",
    "title": "Item - Put",
    "description": "<p>Update Item</p>",
    "name": "updateItem",
    "group": "ITEM",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Json",
            "optional": false,
            "field": "Object",
            "description": "<p>Object containing item data with access token</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Parameter Object-Example:",
          "content": "{\n  \"name\":\"Item 3\",\n  \"description\":\"Description 3\",\n  \"price\":\"1500.00\",\n  \"bid\":\"1600.00\",\n  \"closeDateTime\":\"2021-07-08 10:50\",\n  \"accessToken\":\"af874ho9s8dfush6\"\n}",
          "type": "Json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "http://localhost:8001/api/items/1"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Json",
            "optional": false,
            "field": "Object",
            "description": "<p>Object containing item data</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\n  \"id\":3,\n  \"name\":\"Item 3\",\n  \"description\":\"Description 3\",\n  \"price\":\"1500.00\",\n  \"bid\":\"1600.00\",\n  \"closeDateTime\":\"2021-07-08 10:50\",\n  \"isAutoBidEnabled\":false,\n  \"isClosed\":false,\n  \"isAwardNotified\":false,\n  \"awardedUserId\":null,\n  \"awardedUsername\":null,\n  \"awardedUserRoleId\":null,\n  \"awardedUserRoleName\":null,\n  \"awardedUserEmail\":null,\n  \"awardedUserFirstName\":null,\n  \"awardedUserLastName\":null\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "BadRequest",
            "description": "<p>Bad Request</p>"
          }
        ],
        "401": [
          {
            "group": "401",
            "optional": false,
            "field": "Unauthorized",
            "description": "<p>Unauthorized</p>"
          }
        ],
        "404": [
          {
            "group": "404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Not Found</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./ItemController.php",
    "groupTitle": "ITEM"
  },
  {
    "type": "get",
    "url": "http://localhost:8001/api/users/userDetails",
    "title": "User Details - Get",
    "description": "<p>Get User Details</p>",
    "name": "getUserDetails",
    "group": "USER",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "accessToken",
            "description": "<ul> <li>Access Token</li> </ul>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "include",
            "description": "<ul> <li>Include Parameters as Comma Seperated values <br />(Supported include parameters are, &quot;items&quot;, &quot;bids&quot;, &quot;awardedItems&quot;)</li> </ul>"
          }
        ]
      }
    },
    "sampleRequest": [
      {
        "url": "http://localhost:8001/api/users/userDetails"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Json",
            "optional": false,
            "field": "Object",
            "description": "<p>Object containing user details</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\n  \"id\": 3,\n  \"username\": \"user1\",\n  \"userRoleId\": 2,\n  \"userRoleName\": \"Regular\",\n  \"email\": \"user1@gmail.com\",\n  \"firstName\": \"Mike\",\n  \"lastName\": \"Smith\",\n  \"items\": [\n    {\n      \"id\": 1,\n      \"name\": \"Item 1\",\n      \"description\": \"Description 1\",\n      \"price\": \"1800.00\",\n      \"bid\": \"1950.00\",\n      \"closeDateTime\": \"2021-07-08 16:20\",\n      \"isClosed\": false,\n      \"isAwardNotified\": false,\n      \"itemStatus\": \"In progress\"\n    },\n    {\n      \"id\": 2,\n      \"name\": \"Item 4\",\n      \"description\": \"Description 4\",\n      \"price\": \"400.00\",\n      \"bid\": \"460.00\",\n      \"closeDateTime\": \"2021-07-07 21:45\",\n      \"isClosed\": true,\n      \"isAwardNotified\": true,\n      \"itemStatus\": \"Won\"\n    }\n  ],\n  \"bids\": [\n    {\n      \"id\": 2,\n      \"userId\": 3,\n      \"username\": \"user1\",\n      \"itemId\": 1,\n      \"itemName\": \"Item 1\",\n      \"itemStatus\": \"In progress\",\n      \"itemCloseDateTime\": \"2021-07-08 16:20\",\n      \"bid\": \"1950.00\",\n      \"isAutoBid\": false,\n      \"dateTime\": \"2021-07-07 21:12\"\n    },\n    {\n      \"id\": 5,\n      \"userId\": 3,\n      \"username\": \"user1\",\n      \"itemId\": 2,\n      \"itemName\": \"Item 4\",\n      \"itemStatus\": \"Won\",\n      \"itemCloseDateTime\": \"2021-07-07 21:45\",\n      \"bid\": \"460.00\",\n      \"isAutoBid\": false,\n      \"dateTime\": \"2021-07-07 21:37\"\n    }\n  ],\n  \"awardedItems\": [\n    {\n      \"id\": 2,\n      \"name\": \"Item 4\",\n      \"description\": \"Description 4\",\n      \"price\": \"400.00\",\n      \"bid\": \"460.00\",\n      \"closeDateTime\": \"2021-07-07 21:45\",\n      \"isClosed\": true,\n      \"isAwardNotified\": true,\n      \"winningBidId\": 5,\n      \"winningBid\": \"460.00\",\n      \"winningBidIsAutoBid\": false,\n      \"winningBidDateTime\": \"2021-07-07 21:37\"\n    }\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "BadRequest",
            "description": "<p>Bad Request</p>"
          }
        ],
        "401": [
          {
            "group": "401",
            "optional": false,
            "field": "Unauthorized",
            "description": "<p>Unauthorized</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./UserController.php",
    "groupTitle": "USER"
  },
  {
    "type": "put",
    "url": "http://localhost:8001/api/users/userDetails",
    "title": "User Details - Put",
    "description": "<p>Update User Details</p>",
    "name": "updateUserDetails",
    "group": "USER",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Json",
            "optional": false,
            "field": "Object",
            "description": "<p>Object containing user details with access token</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Parameter Object-Example:",
          "content": "{\n  \"password\":\"admin1\",\n  \"email\":\"admin1@gmail.com\",\n  \"firstName\":\"John\",\n  \"lastName\":\"Doe\",\n  \"accessToken\":\"af874ho9s8dfush6\"\n}",
          "type": "Json"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "http://localhost:8001/api/users/userDetails"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Json",
            "optional": false,
            "field": "Object",
            "description": "<p>Object containing user details</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "{\n  \"id\":1,\n  \"username\":\"admin1\",\n  \"userRoleId\":1,\n  \"userRoleName\":\"Admin\",\n  \"email\":\"admin1@gmail.com\",\n  \"firstName\":\"John\",\n  \"lastName\":\"Doe\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "optional": false,
            "field": "BadRequest",
            "description": "<p>Bad Request</p>"
          }
        ],
        "401": [
          {
            "group": "401",
            "optional": false,
            "field": "Unauthorized",
            "description": "<p>Unauthorized</p>"
          }
        ],
        "404": [
          {
            "group": "404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Not Found</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./UserController.php",
    "groupTitle": "USER"
  }
] });
