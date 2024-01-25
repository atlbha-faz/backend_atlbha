<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
<meta charset="utf-8">
</head>
@isset($data['type'])
<h2>الموضوع:	{{$data['type']}}</h2>
@endisset
<h2>الرسالة:	{{$data['message']}}</h2>
</html>