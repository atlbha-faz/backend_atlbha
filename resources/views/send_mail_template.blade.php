
@isset($data['type'])
<h2>الموضوع:	{{$data['type']}}</h2>
@endisset
<h2>الرسالة:	{{$data['message']}}</h2>
@isset($data['object_id'])
<h2>التاريخ:	{{$data['object_id']}}</h2>
@endisset