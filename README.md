Here is a Restful API Backend app with Symfony framework. There are five routes that can be tested via postman:
1. Method= GET, Route= /items
2. Method= POST, Route= /items
   - You need to give a value inside the body of the request to the key "item", example: "item": "strawberry"
4. Method= UPDATE, Route= /items/{{item}}
  - You need to write the item you want to update in the endpoint and then give a value inside the body of the request to the key "newItem", example: "newItem": "Raspberry"
6. Method= DELETE, Route= /items/{{item}}
  - You need to write the item you want to delete in the endpoint
7. Method= GET, Route= /clear-session
