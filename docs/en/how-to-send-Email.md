- You can send E-mail by using this function =>  
 
 Function                        | Description                 |
| ------------------------------ | --------------------------- |
| CRUDBooster::sendEmail($config=[])  | You need to create an email template before use this helper. <br>$data = ['name'=>'John Doe','address'=>'Lorem ipsum dolor...']; CRUDBooster::sendEmail(['to'=>'john@gmail.com',<br>'data'=>$data,'template'=>'order_success','attachments'=>[]]);

- Note: You can send email for multiple emails CURDBooster::sendEmail(['to' => ['john@gmail.com', 'ahmad@gmail.com'], 'data' => $data, 'template' => "email_template_slug", 'attachments' => []]);

## Table Of Contents
- [Back To Index](./index.md)
