<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <!--[if !mso]><!-->
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!--<![endif]-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title></title>
  <!--[if !mso]><!-->
  <style type="text/css">
    @font-face {
              font-family: 'flama-condensed';
              font-weight: 100;
              src: url('http://assets.vervewine.com/fonts/FlamaCond-Medium.eot');
              src: url('http://assets.vervewine.com/fonts/FlamaCond-Medium.eot?#iefix') format('embedded-opentype'),
                    url('http://assets.vervewine.com/fonts/FlamaCond-Medium.woff') format('woff'),
                    url('http://assets.vervewine.com/fonts/FlamaCond-Medium.ttf') format('truetype');
          }
          @font-face {
              font-family: 'Muli';
              font-weight: 100;
              src: url('http://assets.vervewine.com/fonts/muli-regular.eot');
              src: url('http://assets.vervewine.com/fonts/muli-regular.eot?#iefix') format('embedded-opentype'),
                    url('http://assets.vervewine.com/fonts/muli-regular.woff2') format('woff2'),
                    url('http://assets.vervewine.com/fonts/muli-regular.woff') format('woff'),
                    url('http://assets.vervewine.com/fonts/muli-regular.ttf') format('truetype');
            }
          .address-description a {color: #000000 ; text-decoration: none;}
          @media (max-device-width: 480px) {
            .vervelogoplaceholder {
              height:83px ;
            }
          }
  </style>
  <!--<![endif]-->
  <!--[if (gte mso 9)|(IE)]>
    <style type="text/css">
        .address-description a {color: #000000 ; text-decoration: none;}
        table {border-collapse: collapse ;}
    </style>
    <![endif]-->
</head>

<body bgcolor="#e1e5e8" style="margin-top:0 ;margin-bottom:0 ;margin-right:0 ;margin-left:0 ;padding-top:0px;padding-bottom:0px;padding-right:0px;padding-left:0px;background-color:#e1e5e8;">
  <!--[if gte mso 9]>
<center>
<table width="600" cellpadding="0" cellspacing="0"><tr><td valign="top">
<![endif]-->
  <center style="width:100%;table-layout:fixed;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#e1e5e8;">
    <div style="max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto;">
      <table align="center" cellpadding="0" style="border-spacing:0;font-family:'Muli',Arial,sans-serif;color:#333333;Margin:0 auto;width:100%;max-width:600px;">
        <tbody>
        <tr>
        </tr>
        <tr>
        </tr>
         
          <!-- Start of Email Body-->
          <tr>
            <td class="one-column" style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;background-color:#ffffff;">
              <!--[if gte mso 9]>
                    <center>
                    <table width="80%" cellpadding="20" cellspacing="30"><tr><td valign="top">
                    <![endif]-->
              <table style="border-spacing:0;" width="100%">
                <tbody>
                  <tr>
                    <td align="center" class="inner" style="padding-top:15px;padding-bottom:15px;padding-right:30px;padding-left:30px;" valign="middle"><span class="sg-image" data-imagelibrary="%7B%22width%22%3A%22255%22%2C%22height%22%3A93%2C%22alt_text%22%3A%22Forgot%20Password%22%2C%22alignment%22%3A%22%22%2C%22border%22%3A0%2C%22src%22%3A%22https%3A//marketing-image-production.s3.amazonaws.com/uploads/35c763626fdef42b2197c1ef7f6a199115df7ff779f7c2d839bd5c6a8c2a6375e92a28a01737e4d72f42defcac337682878bf6b71a5403d2ff9dd39d431201db.png%22%2C%22classes%22%3A%7B%22sg-image%22%3A1%7D%7D"><img alt="Forgot Password" class="banner" height="100" src="icon.png" style="border-width: 0px; margin-top: 30px; width: 100px; height: 100px;" width="100"></span></td>
                  </tr>
                  <tr>
                  <td>Please see below the details of the trip ({{$expense['name']}})</td>
                  </tr>
                  <tr>
                        <table align="center">
                            <thead>
                            <tr>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Amount ({{$expense['currency']}})</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($expense_details as $expense_detail)
                            <tr>
                                <td>{{$expense_detail->category}}</td>
                                <td width="200" align="center" >{{$expense_detail->description }}</td>
                                <td>  {{$expense_detail->amount}}</td>
                                <td>{{   date('d-m-Y', strtotime($expense_detail->created_at)) }}</td>
                            </tr>
                            
                            @endforeach
                            </tbody>
                        </table>
                        <tr><td>Best Regards,</td></tr>
                        <tr><td>{{ $user->name}}</td></tr>
                  </tr>
                 
                </tbody>
              </table>
              <!--[if (gte mso 9)|(IE)]>
                    </td></tr></table>
                    </center>
                    <![endif]-->
            </td>
          </tr>
          <!-- End of Email Body-->
          <!-- whitespace -->
          <tr>
            <td height="40">
              <p style="line-height: 40px; padding: 0 0 0 0; margin: 0 0 0 0;">&nbsp;</p>

              <p>&nbsp;</p>
            </td>
          </tr>
          <!-- Social Media -->
          <tr>
            <td align="center" style="padding-bottom:0;padding-right:0;padding-left:0;padding-top:0px;" valign="middle"><span class="sg-image" data-imagelibrary="%7B%22width%22%3A%228%22%2C%22height%22%3A18%2C%22alt_text%22%3A%22Facebook%22%2C%22alignment%22%3A%22%22%2C%22border%22%3A0%2C%22src%22%3A%22https%3A//marketing-image-production.s3.amazonaws.com/uploads/0a1d076f825eb13bd17a878618a1f749835853a3a3cce49111ac7f18255f10173ecf06d2b5bd711d6207fbade2a3779328e63e26a3bfea5fe07bf7355823567d.png%22%2C%22link%22%3A%22%23%22%2C%22classes%22%3A%7B%22sg-image%22%3A1%7D%7D"><a href="#" target="_blank"><img alt="Facebook" height="18" src="https://marketing-image-production.s3.amazonaws.com/uploads/0a1d076f825eb13bd17a878618a1f749835853a3a3cce49111ac7f18255f10173ecf06d2b5bd711d6207fbade2a3779328e63e26a3bfea5fe07bf7355823567d.png" style="border-width: 0px; margin-right: 21px; margin-left: 21px; width: 8px; height: 18px;" width="8"></a></span>
              <!--[if gte mso 9]>&nbsp;&nbsp;&nbsp;<![endif]--><span class="sg-image" data-imagelibrary="%7B%22width%22%3A%2223%22%2C%22height%22%3A18%2C%22alt_text%22%3A%22Twitter%22%2C%22alignment%22%3A%22%22%2C%22border%22%3A0%2C%22src%22%3A%22https%3A//marketing-image-production.s3.amazonaws.com/uploads/6234335b200b187dda8644356bbf58d946eefadae92852cca49fea227cf169f44902dbf1698326466ef192bf122aa943d61bc5b092d06e6a940add1368d7fb71.png%22%2C%22link%22%3A%22%23%22%2C%22classes%22%3A%7B%22sg-image%22%3A1%7D%7D"><a href="#" target="_blank"><img alt="Twitter" height="18" src="https://marketing-image-production.s3.amazonaws.com/uploads/6234335b200b187dda8644356bbf58d946eefadae92852cca49fea227cf169f44902dbf1698326466ef192bf122aa943d61bc5b092d06e6a940add1368d7fb71.png" style="border-width: 0px; margin-right: 16px; margin-left: 16px; width: 23px; height: 18px;" width="23"></a></span>
              <!--[if gte mso 9]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]--><span class="sg-image" data-imagelibrary="%7B%22width%22%3A%2218%22%2C%22height%22%3A18%2C%22alt_text%22%3A%22Instagram%22%2C%22alignment%22%3A%22%22%2C%22border%22%3A0%2C%22src%22%3A%22https%3A//marketing-image-production.s3.amazonaws.com/uploads/650ae3aa9987d91a188878413209c1d8d9b15d7d78854f0c65af44cab64e6c847fd576f673ebef2b04e5a321dc4fed51160661f72724f1b8df8d20baff80c46a.png%22%2C%22link%22%3A%22%23%22%2C%22classes%22%3A%7B%22sg-image%22%3A1%7D%7D"><a href="#"><img alt="Instagram" height="18" src="https://marketing-image-production.s3.amazonaws.com/uploads/650ae3aa9987d91a188878413209c1d8d9b15d7d78854f0c65af44cab64e6c847fd576f673ebef2b04e5a321dc4fed51160661f72724f1b8df8d20baff80c46a.png" style="border-width: 0px; margin-right: 16px; margin-left: 16px; width: 18px; height: 18px;" width="18"></a></span></td>
          </tr>
          <!-- whitespace -->
          <tr>
            <td height="25">
              <p style="line-height: 25px; padding: 0 0 0 0; margin: 0 0 0 0;">&nbsp;</p>

              <p>&nbsp;</p>
            </td>
          </tr>
          <!-- Footer -->
          <tr>
            <td style="padding-top:0;padding-bottom:0;padding-right:30px;padding-left:30px;text-align:center;Margin-right:auto;Margin-left:auto;">
              <center>
                <p style="font-family:'Muli',Arial,sans-serif;Margin:0;text-align:center;Margin-right:auto;Margin-left:auto;font-size:15px;color:#a1a8ad;line-height:23px;">Problems or questions? Call us at
                  <nobr><a class="tel" href="tel:234800000000000" style="color:#a1a8ad;text-decoration:none;" target="_blank"><span style="white-space: nowrap">+23480 000 0000 000</span></a></nobr>
                </p>

                <p style="font-family:'Muli',Arial,sans-serif;Margin:0;text-align:center;Margin-right:auto;Margin-left:auto;font-size:15px;color:#a1a8ad;line-height:23px;">or email <a href="mailto:hello@vervewine.com" style="color:#a1a8ad;text-decoration:underline;" target="_blank">hello@eazyhoppers.com</a></p>

                <p style="font-family:'Muli',Arial,sans-serif;Margin:0;text-align:center;Margin-right:auto;Margin-left:auto;padding-top:10px;padding-bottom:0px;font-size:15px;color:#a1a8ad;line-height:23px;">© Eazy Hoppers <span style="white-space: nowrap">Lagos </span>, <span style="white-space: nowrap">Nigeria</span> <span style="white-space: nowrap">.</span></p>
              </center>
            </td>
          </tr>
          <!-- whitespace -->
          <tr>
            <td height="40">
              <p style="line-height: 40px; padding: 0 0 0 0; margin: 0 0 0 0;">&nbsp;</p>

              <p>&nbsp;</p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </center>
  <!--[if gte mso 9]>
</td></tr></table>
</center>
<![endif]-->


</body>