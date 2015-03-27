# yii-api-helper
Yii API Helper. We decided to make this php solution to establish secure communication between 2+ Yii1 applications.
Load the Helper in both yii applications.

To send POST request 
--------------------------
```php
$data = ['producto' => $producto];
$returnData = ApiHelper::sendRequest('http://example.com/controller/action', $data);
```

To get POST and send back the response.
--------------------------
```php
if (isset($_POST['token'])) {
  $sentData = ApiHelper::getRequestData($_POST['token']);
  echo json_encode(['success' => true]);
}
```
