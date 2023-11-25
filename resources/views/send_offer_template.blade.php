
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
            @font-face {
    font-family: 'Tajawal';
    src: url('https://fonts.googleapis.com/css?family=Tajawal') ;
    
    font-weight: normal;
    font-style: normal;
}
        * {
    margin: 0;
    padding: 0;
    font-family: 'Tajawal', sans-serif;
    direction:rtl;
      }

        .header2{
          background-color:#1DBBBE;
        background-image: url("/images/Pattern.png");
        background-repeat: no-repeat;
        background-position: center center;
        height: 40%;
        border-bottom-left-radius: 10px;
    }

    table{
        width: 60%;
        height: 100%;
        margin: auto;
        text-align:center;
    }
    .headContainer{
        display: flex;
        flex-direction: row;
        justify-content: space-evenly;
        align-items: center;
        padding: 18px 20px 18px 0px;
    }
    .headerImag{
        height: 50%;
        width: 50%;
    }
    .headTitle{
        color:white;
        font-size:22px;
        border-bottom: 1px solid #fff;
        border-bottom-width: 1px;
        padding-bottom: 5px;
    }
    .socialMedia{
     text-decoration:none;
     display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
    
    }
    .socialMedia li{
        list-style: none;
        padding:5px 10px 20px 10px;
    
    }
    .codeImag{
        margin-right:20px;
        width:10%;
        height: 10%;

    }
    </style>
</head>

<body>
<div style="overflow-x:auto;">

<table>
  <tr class="header2">
   <td  style="
    border-bottom-right-radius: 98px;
"> <div class="headContainer">
<h2 class="headTitle"> عرض السلة</h2>
<!-- <img src="/images/sale.png" class="headerImag"/> -->

</div>
</td>
  </tr>
  <tr >
        <td>
  <!-- <img src="/images/logo.png" width="30%" height="25%"/> -->
</td>
</tr>
  <tr>
        <td style=" text-align:center; font-weight:bold; display:inline">
 
      
     {{htmlspecialchars(trim(strip_tags($data['message'])))}} 
       <a href="{{ 'https://template.atlbha.com/' . $data['store_domain'] }}">{{$data['store_id']}}</a>
       <!-- <img src="/images/congratulations.png" class="codeImag" /> -->

     </td>

</tr>
 <tr>
    <td>
    
</td>
</tr>
  <tr style="
  display: flex;
justify-content: center;
align-items: center;
  text-align: center;
padding:10px
  ">
  <td  style="background-color:#B6BE34;
 padding:10px 20px 10px 20px ;
   border-radius: 35px;
   color:white;
   text-align:center;
   display: flex;
   justify-content: center;
  align-items: center;
  text-align: center;
  width:20%;
  margin:auto;
  ">
  
</td>
</tr>
  <tr style=" margin-bottom:60px; padding-top:20px; font-weight:500">
  <td style="text-align: center; padding:30px 0 30px 0">
  <span style="text-align: center;">ينتهي بتاريخ</span>
  <span >{{$data['discount_expire_date']}}</span>
</td>
</tr>
 
<tr style="background-color: #0000001A ; text-align: center;">
  <td style="text-align: center;"> 
  <!-- <img src="/images/logo.png" width="30%" height="25%"/> -->
  
  <div >
    
    <ul class="socialMedia">
      <li>{{$data['store_id']}}</li>  
     
</ul>
<ul class="socialMedia">
      <li>{{$data['store_email']}}</li>  
     
</ul>
</div>
  </td>
  </tr>
</table>

</div>    
</body>
</html>
