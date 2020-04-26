# Sabian-File-Manager
A base64-file upload manager for Laravel. Converts Base64 and stores it as a file

Example usage

```php
$file = SabianFileManager::getFileFromBase64($base64,"images","myImage","png");
if(is_array($file)){
$url = $file["url"];
$path = $file["path"];
}else{
die("Could not convert file");
}
```
