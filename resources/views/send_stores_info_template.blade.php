<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
<meta charset="utf-8">
<style>
        .number-direction {
            direction: ltr;
        }
    </style>
</head>
<body dir="rtl">
<h2>	  الاسم:{{$data['Contact_name']}}</h2>
<h2>رقم الجوال:	<span class="number-direction">{{$data['phonenumber']}}</span></h2>
<h2>البريد الالكتروني:{{$data['email']}}</h2>
<h2>	 اسم المتجر:{{$data['store_name']}}</h2>
</body>
</html>