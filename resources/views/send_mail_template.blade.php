<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
<meta charset="utf-8">
</head>
<body dir="rtl">
@isset($data['type'])
<h2>عنوان الرسالة:	{{$data['type']}}</h2>
@endisset
<h2>	{{preg_replace('/(?:<|&lt;).*?(?:>|&gt;)/', '',$data['message'])}}</h2>
</body>
</html>