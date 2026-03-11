@include("crudbooster::emails.header")

<p>Hello,</p>
<p>Someone with IP Address {{$_SERVER['REMOTE_ADDR'] ?? 'Unknown'}} at {{date('Y-m-d H:i:s')}} has been requested password, the following is your new password : </p>
<p>Password : {{$password ?? 'N/A'}}</p>

@include("crudbooster::emails.footer")